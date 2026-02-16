<?php

use App\Mail\CampaignEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactGroupController;
use App\Http\Controllers\EmailHistoryController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\InvoiceTemplateController;
use App\Http\Controllers\EmailApiSettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/kill-queue-workers', function () {
//     $process = Process::fromShellCommandline("ps aux | grep 'artisan queue:work' | grep -v grep");
//     $process->run();

//     $output = $process->getOutput();
//     $lines = array_filter(explode("\n", $output));

//     $killed = [];

//     foreach ($lines as $line) {
//         // Get the PID (2nd column)
//         $parts = preg_split('/\s+/', $line);
//         $pid = $parts[1] ?? null;

//         if ($pid) {
//             exec("kill $pid");
//             $killed[] = $pid;
//         }
//     }

//     return response()->json([
//         'killed_pids' => $killed,
//         'status' => 'Queue workers stopped.'
//     ]);
// });

Route::get('/queue-workers', function () {
    // This will count how many artisan queue:work processes are running
    $process = Process::fromShellCommandline("ps aux | grep 'artisan queue:work' | grep -v grep");
    $process->run();

    $output = $process->getOutput();
    $lines = array_filter(explode("\n", $output));
    $count = count($lines);

    if ($count == 0) {
        exec('php artisan queue:work > /dev/null &');
    }
    return response()->redirectTo('/dashboard')->with('status', 'Queue worker started successfully.');
    // return response()->json([
    //     'running_workers' => $count,
    //     'details' => $lines
    // ]);
});

Route::get('/start-queue-jobs-from-routes', function () {
    //first priority
    //    exec('php artisan queue:work > /dev/null &');
    //    exec('php artisan queue:restart && php artisan queue:work > /dev/null &');

    return 'Queue job is active now and queue worker has been started.';
});
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Message Templates
Route::resource('message-templates', \App\Http\Controllers\MessageTemplateController::class)
    ->except(['show'])
    ->middleware('auth');

Route::get('/api/message-templates/{template}', [\App\Http\Controllers\MessageTemplateController::class, 'show'])
    ->name('api.message-templates.show')
    ->middleware('auth');

// Settings
Route::middleware(['auth'])->prefix('settings')->group(function () {
    Route::get('/email-api', [EmailApiSettingsController::class, 'index'])->name('settings.email-api');
    Route::put('/email-api', [EmailApiSettingsController::class, 'update'])->name('settings.email-api.update');
    Route::post('/email-api/test', [EmailApiSettingsController::class, 'testConnection'])->name('settings.email-api.test');
    Route::post('/email-api/activate', [EmailApiSettingsController::class, 'activate'])->name('settings.email-api.activate');
});

// Email Campaigns
Route::middleware(['auth'])->group(function () {
    // New campaign routes
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaign-progress', [EmailController::class, 'campaignProgress'])->name('campaign.progress');

    // Updated email sending routes
    Route::get('/send_email', [EmailController::class, 'showSendMailForm'])->name('send.email.view');
    Route::post('/send_email', [EmailController::class, 'send'])->name('send.email');
});

// Email Templates
Route::middleware(['auth'])->group(function () {
    Route::get('/email-templates', [EmailTemplateController::class, 'create'])->name('email-templates.create');
    Route::post('/email-templates', [EmailTemplateController::class, 'store'])->name('email-templates.store');
    Route::get('/email-templates/{template}/edit', [EmailTemplateController::class, 'edit'])->name('email-templates.edit');
    Route::get('/api/email-templates/{template}', [EmailTemplateController::class, 'show'])->name('api.email-templates.show');

    Route::get('/message-templates/{id}', function ($id) {
        $template = App\Models\MessageTemplate::findOrFail($id);
        return response()->json(['content' => $template->content]);
    });
});

// Invoice Templates
Route::middleware(['auth'])->group(function () {
    Route::get('/invoice-templates', function () {
        return view('invoice-templates');
    })->name('invoice-templates.create');

    Route::post('/invoice-templates', [InvoiceTemplateController::class, 'store'])->name('invoice-templates.store');
    Route::resource('invoice-templates', InvoiceTemplateController::class);
});

// Email History & Campaign Management
Route::middleware(['auth'])->group(function () {
    Route::get('/email-history', [EmailHistoryController::class, 'index'])->name('email-history.index');
    Route::get('/campaigns/{campaign}/details', [EmailHistoryController::class, 'details'])->name('campaigns.details');
    Route::post('/campaigns/{campaign}/resend', [EmailHistoryController::class, 'resend'])->name('campaigns.resend');
});

// Contact Groups
Route::middleware(['auth'])->prefix('contact')->group(function () {
    Route::get('/', [ContactGroupController::class, 'index'])->name('contact-groups.index');
    Route::post('/', [ContactGroupController::class, 'store'])->name('contact-groups.store');
    Route::get('/{contactGroup}', [ContactGroupController::class, 'show'])->name('contact-groups.show');

    // Contact routes
    Route::post('/{contactGroup}/contacts', [ContactGroupController::class, 'addContact'])->name('contact-groups.add-contact');
    Route::put('/contacts/{contact}', [ContactGroupController::class, 'updateContact'])->name('contact-groups.update-contact');
    Route::delete('/contacts/{contact}', [ContactGroupController::class, 'destroyContact'])->name('contact-groups.destroy-contact');
    Route::resource('contact-groups', ContactGroupController::class);
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
