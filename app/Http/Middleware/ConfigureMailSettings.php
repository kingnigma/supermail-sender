<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\EmailService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigureMailSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (auth()->check()) {
        //     $mailSettings = EmailService::where('user_id', auth()->id())->first();

        //     if ($mailSettings && $mailSettings->service_type === 'smtp') {

        //         config([
        //             'mail.mailers.smtp.host' => $mailSettings->credentials['smtp_host'] ?? null,
        //             'mail.mailers.smtp.port' => $mailSettings->credentials['smtp_port'] ?? null,
        //             'mail.mailers.smtp.encryption' => $mailSettings->credentials['smtp_encryption'] ?? 'tls',
        //             'mail.mailers.smtp.username' => $mailSettings->credentials['smtp_username'] ?? null,
        //             'mail.mailers.smtp.password' => $mailSettings->credentials['smtp_password'] ?? null,
        //             // 'mail.from.address' => $mailSettings->credentials['smtp_replay_to'] ?? null,
        //             'mail.from.name' => $mailSettings->credentials['smtp_from_name'] ?? null,
        //             'mail.reply_to.address' => $mailSettings->credentials['smtp_replay_to'] ?? null,
        //             'mail.reply_to.name' => $mailSettings->credentials['smtp_from_name'] ?? null,
        //         ]);
        //     }
        // }
        return $next($request);
    }
}
