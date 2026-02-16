<?php

/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 24/05/17
 * Time: 11:29 PM
 */

namespace App\Traits;

use App\Models\EmailService;

trait SmtpSettings
{

    public function setMailConfigs()
    {
        // $mailSettings = cache()->remember('mail_settings', 3600, function () {
        //     return EmailService::first();
        // });

        $mailSettings =  EmailService::first();

        if ($mailSettings && $mailSettings->service_type === 'smtp') {

            config([
                'mail.mailers.smtp.host' => $mailSettings->credentials['smtp_host'] ?? null,
                'mail.mailers.smtp.port' => $mailSettings->credentials['smtp_port'] ?? null,
                'mail.mailers.smtp.encryption' => $mailSettings->credentials['smtp_encryption'] ?? 'tls',
                'mail.mailers.smtp.username' => $mailSettings->credentials['smtp_username'] ?? null,
                'mail.mailers.smtp.password' => $mailSettings->credentials['smtp_password'] ?? null,
                'mail.from.address' => $mailSettings->credentials['smtp_mail_from'] ?? 'default@example.com',
                'mail.from.name' => $mailSettings->credentials['smtp_from_name'] ?? 'Default Sender',
                'mail.reply_to.address' => $mailSettings->credentials['smtp_replay_to'] ?? null,
                'mail.reply_to.name' => $mailSettings->credentials['smtp_from_name'] ?? null,
            ]);
            // info([
            //     'mail.mailers.smtp.host' => $mailSettings->credentials['smtp_host'] ?? null,
            //     'mail.mailers.smtp.port' => $mailSettings->credentials['smtp_port'] ?? null,
            //     'mail.mailers.smtp.encryption' => $mailSettings->credentials['smtp_encryption'] ?? 'tls',
            //     'mail.mailers.smtp.username' => $mailSettings->credentials['smtp_username'] ?? null,
            //     'mail.mailers.smtp.password' => $mailSettings->credentials['smtp_password'] ?? null,
            //     'mail.from.address' => $mailSettings->credentials['smtp_replay_to'] ?? 'default@example.com',
            //     'mail.from.name' => $mailSettings->credentials['smtp_from_name'] ?? 'Default Sender',
            //     'mail.reply_to.address' => $mailSettings->credentials['smtp_replay_to'] ?? null,
            //     'mail.reply_to.name' => $mailSettings->credentials['smtp_from_name'] ?? null,
            // ]);
        }

        // info(config('mail'));
        app()->forgetInstance('mail.manager');
    }
}
