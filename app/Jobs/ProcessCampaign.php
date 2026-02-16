<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\Contact;
use App\Models\EmailHistory;
use App\Models\Activity;
use App\Mail\CampaignEmail;
use App\Services\EmailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function handle()
    {
        try {
            // Configure the mail driver based on the user's active email service settings
            EmailConfigService::configureMailer($this->campaign->user_id);

            $this->campaign->update(['status' => 'processing']);

            $contacts = $this->campaign->contactGroup->contacts;
            $totalContacts = $contacts->count();
            $processed = 0;

            foreach ($contacts as $contact) {
                // Skip contacts without email addresses
                if (empty($contact->company_email)) {
                    continue;
                }

                try {
                    // Prepare the email content with dynamic tags
                    $message = $this->replaceTags($this->campaign->message, $contact);

                    // Generate invoice PDF if selected
                    $invoiceAttachment = null;
                    if ($this->campaign->invoice_template_id) {
                        $invoiceAttachment = $this->generateInvoicePdf($contact);
                    }

                    // Prepare attachments
                    $attachments = [];
                    if ($invoiceAttachment) {
                        $attachments[] = $invoiceAttachment;
                    }

                    if ($this->campaign->attachment_path) {
                        $attachments[] = Storage::path($this->campaign->attachment_path);
                    }

                    // Send email
                    Mail::to($contact->company_email)
                        ->send(new CampaignEmail(
                            $this->campaign->subject,
                            $message,
                            $attachments
                        ));

                    // Record email history
                    EmailHistory::create([
                        'campaign_id' => $this->campaign->id,
                        'recipient_email' => $contact->company_email, // Changed from email
                        'recipient_name' => $contact->full_name, // Changed from name
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);

                    // Record activity
                    Activity::create([
                        'user_id' => $this->campaign->user_id,
                        'type' => 'email_sent',
                        'subject_id' => $this->campaign->id,
                        'subject_type' => Campaign::class,
                        'description' => "Sent email to {$contact->company_email} for campaign {$this->campaign->name}",
                    ]);

                    $processed++;
                } catch (\Exception $e) {
                    // Log failed email
                    EmailHistory::create([
                        'campaign_id' => $this->campaign->id,
                        'recipient_email' => $contact->company_email, // Changed from email
                        'recipient_name' => $contact->full_name, // Changed from name
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                        'sent_at' => now(),
                    ]);

                    // Record failed activity
                    Activity::create([
                        'user_id' => $this->campaign->user_id,
                        'type' => 'email_failed',
                        'subject_id' => $this->campaign->id,
                        'subject_type' => Campaign::class,
                        'description' => "Failed to send email to {$contact->company_email}: " . $e->getMessage(),
                    ]);
                }

                // Small delay to prevent rate limiting
                if ($processed % 10 === 0) {
                    sleep(1);
                }
            }

            // Update campaign status
            $this->campaign->update([
                'status' => 'completed',
                'sent_count' => $processed,
                'failed_count' => $totalContacts - $processed,
                'completed_at' => now(),
            ]);

            // Record completion activity
            Activity::create([
                'user_id' => $this->campaign->user_id,
                'type' => 'campaign_completed',
                'subject_id' => $this->campaign->id,
                'subject_type' => Campaign::class,
                'description' => "Completed campaign {$this->campaign->name}. Sent: $processed, Failed: " . ($totalContacts - $processed),
            ]);
        } catch (\Exception $e) {
            $this->campaign->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Activity::create([
                'user_id' => $this->campaign->user_id,
                'type' => 'campaign_failed',
                'subject_id' => $this->campaign->id,
                'subject_type' => Campaign::class,
                'description' => "Failed to process campaign {$this->campaign->name}: " . $e->getMessage(),
            ]);

            throw $e; // Re-throw for job retry
        }
    }

    protected function replaceTags($content, Contact $contact)
    {
        $replacements = [
            '{name}' => $contact->name,
            '{email}' => $contact->company_email,
            '{company_name}' => $contact->company_name,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    protected function generateInvoicePdf(Contact $contact)
    {
        $invoice = $this->campaign->invoiceTemplate;

        // Generate a unique filename
        $filename = 'invoices/' . Str::slug($invoice->title) . '_' . Str::slug($contact->company_email) . '_' . now()->format('YmdHis') . '.pdf';

        // Render the invoice view with contact data
        $pdf = Pdf::loadView('invoice-templates.layout', [
            'invoiceTemplate' => $invoice,
            'contact' => $contact,
            'forEmail' => true,
        ]);

        // Save the PDF to storage
        Storage::put($filename, $pdf->output());

        return Storage::path($filename);
    }
}
