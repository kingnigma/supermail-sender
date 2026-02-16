<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\EmailService;
use Illuminate\Database\Seeder;

class EmailServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Check if user already has an active email service
            $hasActive = EmailService::where('user_id', $user->id)
                ->where('is_active', true)
                ->exists();

            // If no active service, create a default SMTP one
            if (!$hasActive) {
                EmailService::create([
                    'user_id' => $user->id,
                    'service_type' => 'smtp',
                    'is_active' => true,
                    'credentials' => [
                        'smtp_host' => env('MAIL_HOST', 'localhost'),
                        'smtp_port' => env('MAIL_PORT', 587),
                        'smtp_username' => env('MAIL_USERNAME', ''),
                        'smtp_password' => env('MAIL_PASSWORD', ''),
                        'smtp_from_name' => env('APP_NAME', 'SuperMail'),
                        'smtp_mail_from' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
                        'smtp_replay_to' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
                        'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
                    ]
                ]);
            }
        }
    }
}
