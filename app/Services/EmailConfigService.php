<?php

namespace App\Services;

use App\Models\EmailService;
use Illuminate\Support\Facades\Config;
use Exception;

class EmailConfigService
{
    /**
     * Configure Laravel's mail driver based on the active email service
     * 
     * @param int|null $userId
     * @return void
     * @throws Exception
     */
    public static function configureMailer(?int $userId = null): void
    {
        $userId = $userId ?? auth()->id();

        if (!$userId) {
            // Fall back to .env configuration if no user is authenticated
            return;
        }

        // Get the active email service for the user
        $activeService = EmailService::where('user_id', $userId)
            ->where('is_active', true)
            ->first();

        // If no active service, try to get the latest one
        if (!$activeService) {
            $activeService = EmailService::where('user_id', $userId)
                ->latest()
                ->first();
        }

        if (!$activeService) {
            // No service configured, fall back to .env
            return;
        }

        $credentials = $activeService->credentials ?? [];
        $serviceType = $activeService->service_type;

        switch ($serviceType) {
            case 'smtp':
                self::configureSMTP($credentials);
                break;
            case 'mailchimp':
                self::configureMailchimp($credentials);
                break;
            case 'mailgun':
                self::configureMailgun($credentials);
                break;
            case 'postmark':
                self::configurePostmark($credentials);
                break;
            default:
                throw new Exception("Unknown email service type: $serviceType");
        }
    }

    /**
     * Configure SMTP driver
     */
    private static function configureSMTP(array $credentials): void
    {
        Config::set('mail.mailer', 'smtp');
        Config::set('mail.host', $credentials['smtp_host'] ?? '');
        Config::set('mail.port', $credentials['smtp_port'] ?? 587);
        Config::set('mail.username', $credentials['smtp_username'] ?? '');
        Config::set('mail.password', $credentials['smtp_password'] ?? '');
        Config::set('mail.encryption', $credentials['smtp_encryption'] ?? 'tls');
        Config::set('mail.from.address', $credentials['smtp_mail_from'] ?? env('MAIL_FROM_ADDRESS'));
        Config::set('mail.from.name', $credentials['smtp_from_name'] ?? env('APP_NAME'));

        // Set reply-to if available
        if (!empty($credentials['smtp_replay_to'])) {
            Config::set('mail.reply_to.address', $credentials['smtp_replay_to']);
        }
    }

    /**
     * Configure Mailchimp driver (uses API)
     */
    private static function configureMailchimp(array $credentials): void
    {
        Config::set('mail.mailer', 'mailchimp');
        Config::set('mailchimp.api_key', $credentials['mailchimp_api_key'] ?? '');
        Config::set('mailchimp.server_prefix', $credentials['mailchimp_server_prefix'] ?? '');
        Config::set('mail.from.address', env('MAIL_FROM_ADDRESS'));
        Config::set('mail.from.name', env('APP_NAME'));
    }

    /**
     * Configure Mailgun driver
     */
    private static function configureMailgun(array $credentials): void
    {
        Config::set('mail.mailer', 'mailgun');
        Config::set('mailgun.domain', $credentials['mailgun_domain'] ?? '');
        Config::set('mailgun.secret', $credentials['mailgun_api_key'] ?? '');
        Config::set('mailgun.endpoint', $credentials['mailgun_region'] === 'eu'
            ? 'api-eu.mailgun.net'
            : 'api.mailgun.net');
        Config::set('mail.from.address', env('MAIL_FROM_ADDRESS'));
        Config::set('mail.from.name', env('APP_NAME'));
    }

    /**
     * Configure Postmark driver
     */
    private static function configurePostmark(array $credentials): void
    {
        Config::set('mail.mailer', 'postmark');
        Config::set('postmark.token', $credentials['postmark_server_token'] ?? '');
        Config::set('mail.from.address', env('MAIL_FROM_ADDRESS'));
        Config::set('mail.from.name', env('APP_NAME'));
    }

    /**
     * Get the active email service for a user
     */
    public static function getActiveService(?int $userId = null): ?EmailService
    {
        $userId = $userId ?? auth()->id();

        if (!$userId) {
            return null;
        }

        return EmailService::where('user_id', $userId)
            ->where('is_active', true)
            ->first();
    }
}
