<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CampaignEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ContactGroup;
use App\Models\MessageTemplate;
use App\Models\InvoiceTemplate;
use App\Models\Contact;
use App\Models\Campaign;
use App\Models\EmailHistory;
use App\Models\Activity;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'contact_group_id' => 'required|integer|exists:contact_groups,id',
            'message_template' => 'required|exists:message_templates,id',
            'message' => 'required|string',
            'invoice_template' => 'nullable|integer|exists:invoice_templates,id',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the campaign record
        $campaign = Campaign::create([
            'name' => $request->campaign_name,
            'subject' => $request->subject,
            'contact_group_id' => $request->contact_group_id,
            'message_template_id' => $request->message_template,
            'message' => $request->message,
            'invoice_template_id' => $request->invoice_template,
            'status' => 'processing',
            'user_id' => auth()->id(),
        ]);

        // Store the file attachment if present
        $attachmentPath = null;
        /*if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('campaign_attachments');
            $campaign->update(['attachment_path' => $attachmentPath]);
        }*/
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $originalName = $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('campaign_attachments', $originalName);
            $campaign->update(['attachment_path' => $attachmentPath]);
        }

        // Get all contacts in the selected group
        $contactGroup = ContactGroup::findOrFail($request->contact_group_id);
        $contacts = $contactGroup->contacts;

        foreach ($contacts as $contact) {
            try {
                // Replace dynamic tags in the message
                $personalizedMessage = $this->replaceTags($request->message, $contact);

                // Generate invoice PDF if selected
                $invoiceAttachment = null;
                if ($request->invoice_template) {
                    $invoiceAttachment = $this->generateInvoicePdf($request->invoice_template, $contact);
                }

                // Prepare attachments
                $attachments = [];
                if ($invoiceAttachment) {
                    $attachments[] = [
                        'file' => $invoiceAttachment['path'],
                        'options' => [
                            'as' => $invoiceAttachment['name'],
                            'mime' => $invoiceAttachment['mime'],
                        ],
                    ];
                }

                if ($campaign->attachment_path) {
                    $attachments[] = Storage::path($campaign->attachment_path);
                }

                // Skip if no email address
                if (empty($contact->company_email)) {
                    continue;
                }

                // Send email
                Mail::to($contact->company_email)
                    ->send(new CampaignEmail(
                        $request->subject,
                        $personalizedMessage,
                        $attachments
                    ));

                // Record email history
                EmailHistory::create([
                    'campaign_id' => $campaign->id,
                    'recipient_email' => $contact->company_email,
                    'recipient_name' => $contact->name,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                // Record activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'type' => 'email_sent',
                    'subject_id' => $campaign->id,
                    'subject_type' => Campaign::class,
                    'description' => "Sent email to {$contact->email} for campaign {$campaign->name}",
                ]);

            } catch (\Exception $e) {
                // Log failed email
                EmailHistory::create([
                    'campaign_id' => $campaign->id,
                    'recipient_email' => $contact->company_email,
                    'recipient_name' => $contact->name,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'sent_at' => now(),
                ]);

                // Record failed activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'type' => 'email_failed',
                    'subject_id' => $campaign->id,
                    'subject_type' => Campaign::class,
                    'description' => "Failed to send email to {$contact->email}: " . $e->getMessage(),
                ]);
            }
        }

        // Update campaign status
        $sentCount = $campaign->emailHistories()->where('status', 'sent')->count();
        $failedCount = $campaign->emailHistories()->where('status', 'failed')->count();

        $campaign->update([
            'status' => 'completed',
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
            'completed_at' => now(),
        ]);

        // Record completion activity
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'campaign_completed',
            'subject_id' => $campaign->id,
            'subject_type' => Campaign::class,
            'description' => "Completed campaign {$campaign->name}. Sent: $sentCount, Failed: $failedCount",
        ]);

        return redirect()->route('campaigns.show', $campaign->id)
            ->with('success', 'Email campaign has been sent successfully!');
    }

    public function showSendMailForm()
    {
        $contactGroups = ContactGroup::where('user_id', auth()->id())
            ->withCount('contacts')
            ->get();
        $messageTemplates = MessageTemplate::all();
        $invoices = InvoiceTemplate::all();
        return view('sendmail', compact('contactGroups', 'messageTemplates', 'invoices'));
    }

    protected function replaceTags($content, Contact $contact)
    {
        $replacements = [
            '{name}' => $contact->full_name,
            '{email}' => $contact->company_email,
            '{company_name}' => $contact->company_name,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    public function campaignProgress(Request $request)
    {
        return response()->stream(function () {
            $lastId = 0;

            while (true) {
                // Get the latest campaign status for the authenticated user
                $campaign = Campaign::where('user_id', auth()->id())
                    ->orderBy('id', 'desc')
                    ->first();

                if ($campaign && $campaign->id != $lastId) {
                    $data = [
                        'status' => $campaign->status,
                        'sent_count' => $campaign->sent_count,
                        'failed_count' => $campaign->failed_count,
                        'total' => $campaign->contactGroup->contacts->count(),
                        'progress' => $campaign->sent_count + $campaign->failed_count,
                    ];

                    echo "data: " . json_encode($data) . "\n\n";
                    ob_flush();
                    flush();
                    $lastId = $campaign->id;
                }

                // Break the loop if the client aborted the connection
                if (connection_aborted()) {
                    break;
                }

                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    protected function generateInvoicePdf($invoiceTemplateId, Contact $contact)
    {
        try {
            $invoice = InvoiceTemplate::findOrFail($invoiceTemplateId);

            // Ensure invoices directory exists
            Storage::makeDirectory('invoices');

            // Generate a unique filename
            $filename = 'invoices/' . Str::slug($invoice->title) . '_' . Str::slug($contact->company_email) . '_' . now()->format('YmdHis') . '.pdf';

            // Render the invoice view with contact data
            // Render the invoice view with contact data
        $pdf = Pdf::loadView('invoices.show', [
            'invoice' => $invoice,
            'contact' => $contact
        ]);

            // Save the PDF to storage
            Storage::put($filename, $pdf->output());

            // Return the full path for email attachment
        return [
            'path' => Storage::path($filename),
            'name' => 'Invoice_' . $invoice->title . '.pdf',
            'mime' => 'application/pdf',
        ];

        } catch (\Exception $e) {
            Log::error('Failed to generate invoice PDF: ' . $e->getMessage());
            return null;
        }
    }
}
