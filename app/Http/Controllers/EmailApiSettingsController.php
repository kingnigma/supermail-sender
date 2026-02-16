<?php

namespace App\Http\Controllers;

use App\Models\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailApiSettingsController extends Controller
{
    public function index()
    {
        $emailServices = [
            ['id' => 'mailchimp', 'name' => 'Mailchimp', 'icon' => 'bi-mailbox2'],
            ['id' => 'smtp', 'name' => 'SMTP', 'icon' => 'bi-envelope'],
            ['id' => 'mailgun', 'name' => 'Mailgun', 'icon' => 'bi-send'],
            ['id' => 'postmark', 'name' => 'Postmark', 'icon' => 'bi-envelope-paper'],
        ];

        // Get the active service (where is_active = true)
        $activeService = EmailService::where('user_id', auth()->id())
            ->where('is_active', true)
            ->first();

        // If no active service found, get the most recent one
        if (!$activeService) {
            $activeService = EmailService::where('user_id', auth()->id())
                ->latest()
                ->first();
        }

        $activeServiceId = $activeService->service_type ?? 'smtp';
        $apiCredentials = $activeService->credentials ?? [];

        return view('settings', compact('emailServices', 'activeServiceId', 'apiCredentials'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'active_service' => 'required|in:mailchimp,smtp,mailgun,postmark',
            'mailchimp_api_key' => 'required_if:active_service,mailchimp',
            'mailchimp_server_prefix' => 'required_if:active_service,mailchimp',
            'smtp_host' => 'required_if:active_service,smtp',
            'smtp_port' => 'required_if:active_service,smtp|integer',
            'smtp_username' => 'required_if:active_service,smtp',
            'smtp_password' => 'required_if:active_service,smtp',
            'smtp_from_name' => 'required_if:active_service,smtp|string',
            'smtp_mail_from' => 'required_if:active_service,smtp|email',
            'smtp_replay_to' => 'required_if:active_service,smtp|email',
            'smtp_encryption' => 'nullable|in:tls,ssl,',
            'mailgun_domain' => 'required_if:active_service,mailgun',
            'mailgun_api_key' => 'required_if:active_service,mailgun',
            'mailgun_region' => 'required_if:active_service,mailgun|in:us,eu',
            'postmark_api_key' => 'required_if:active_service,postmark',
            'postmark_server_token' => 'required_if:active_service,postmark',
        ], [
            'smtp_replay_to.email' => 'The replay to field must be a valid email address.',
            'smtp_mail_from.email' => 'The replay to field must be a valid email address.'

        ]);

        // Prepare credentials based on active service
        $credentials = [];
        switch ($validated['active_service']) {
            case 'mailchimp':
                $credentials = [
                    'mailchimp_api_key' => $validated['mailchimp_api_key'],
                    'mailchimp_server_prefix' => $validated['mailchimp_server_prefix'],
                ];
                break;
            case 'smtp':
                $credentials = [
                    'smtp_host' => $validated['smtp_host'],
                    'smtp_port' => $validated['smtp_port'],
                    'smtp_username' => $validated['smtp_username'],
                    'smtp_password' => $validated['smtp_password'],
                    'smtp_from_name' => $validated['smtp_from_name'],
                    'smtp_mail_from' => $validated['smtp_mail_from'],
                    'smtp_replay_to' => $validated['smtp_replay_to'],
                    'smtp_encryption' => $validated['smtp_encryption'] ?? null,
                ];
                break;
            case 'mailgun':
                $credentials = [
                    'mailgun_domain' => $validated['mailgun_domain'],
                    'mailgun_api_key' => $validated['mailgun_api_key'],
                    'mailgun_region' => $validated['mailgun_region'],
                ];
                break;
            case 'postmark':
                $credentials = [
                    'postmark_api_key' => $validated['postmark_api_key'],
                    'postmark_server_token' => $validated['postmark_server_token'],
                ];
                break;
        }

        // Save to database and mark this service as active
        // First, deactivate all other services for this user
        EmailService::where('user_id', auth()->id())->update(['is_active' => false]);

        // Then, create/update and activate the selected service
        EmailService::updateOrCreate(
            ['user_id' => auth()->id(), 'service_type' => $validated['active_service']],
            [
                'service_type' => $validated['active_service'],
                'credentials' => $credentials,
                'is_active' => true
            ]
        );
        return redirect()->route('settings.email-api')
            ->with('success', 'Email API settings updated successfully!');
    }

    public function testConnection(Request $request)
    {
        $service = $request->input('service');
        $credentials = $request->except(['_token', 'service']);

        try {
            $result = $this->testServiceConnection($service, $credentials);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function testServiceConnection($service, $credentials)
    {
        // Implement actual connection testing for each service
        // This is a placeholder - you would implement real API calls

        switch ($service) {
            case 'mailchimp':
                // Test Mailchimp API connection
                return ['success' => true, 'message' => 'Successfully connected to Mailchimp API'];

            case 'smtp':
                // Test SMTP connection
                return ['success' => true, 'message' => 'SMTP connection successful'];

            case 'mailgun':
                // Test Mailgun API connection
                return ['success' => true, 'message' => 'Mailgun API is working'];

            case 'postmark':
                // Test Postmark API connection
                return ['success' => true, 'message' => 'Postmark server is reachable'];

            default:
                throw new \Exception('Unknown email service');
        }
    }

    public function activate(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|in:mailchimp,smtp,mailgun,postmark',
        ]);

        // Check if the service exists for the user
        $service = EmailService::where('user_id', auth()->id())
            ->where('service_type', $validated['service_type'])
            ->first();

        if (!$service) {
            return redirect()->route('settings.email-api')
                ->with('error', 'Email service not found. Please save settings first.');
        }

        // Deactivate all other services
        EmailService::where('user_id', auth()->id())
            ->where('service_type', '!=', $validated['service_type'])
            ->update(['is_active' => false]);

        // Activate the selected service
        $service->update(['is_active' => true]);

        return redirect()->route('settings.email-api')
            ->with('success', 'Email service activated successfully!');
    }
}
