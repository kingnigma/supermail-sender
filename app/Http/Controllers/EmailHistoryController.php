<?php

namespace App\Http\Controllers;

use App\Models\EmailCampaign;
use Illuminate\Http\Request;

class EmailHistoryController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = EmailCampaign::with('template')
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function($query, $date) {
                return $query->where('sent_at', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                return $query->where('sent_at', '<=', $date);
            })
            ->orderBy('sent_at', 'desc')
            ->paginate(15);

        return view('email-history', compact('campaigns'));
    }

    public function details(EmailCampaign $campaign)
    {
        return view('partials.campaign-details', compact('campaign'));
    }

    public function resend(EmailCampaign $campaign)
    {
        // Implement resend logic here
        return back()->with('success', 'Campaign has been queued for resending');
    }
}