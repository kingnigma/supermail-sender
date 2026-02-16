<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\MessageTemplate;
use App\Models\Activity;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalContacts = Contact::count();
        $totalEmailCampaigns = Campaign::where('status', 'completed')->count();
        $totalEmailTemplates = EmailTemplate::count();
        $totalMessageTemplates = MessageTemplate::count();
        
        // Fetch recent activities with user info
        $recentActivities = Activity::with(['user' => function($query) {
                $query->select('id', 'name');
            }])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get(['id', 'user_id', 'type', 'description', 'created_at']);

        return view('dashboard', compact('totalContacts', 'totalEmailCampaigns', 'totalMessageTemplates', 'recentActivities'));
    }
}