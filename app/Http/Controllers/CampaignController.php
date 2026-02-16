<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\EmailHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    /**
     * Display a listing of all campaigns.
     */
    public function index()
    {
        $campaigns = Campaign::where('user_id', Auth::id())
            ->withCount(['emailHistories as sent_count' => function ($query) {
                $query->where('status', 'sent');
            }])
            ->withCount(['emailHistories as failed_count' => function ($query) {
                $query->where('status', 'failed');
            }])
            ->withCount(['emailHistories as clicked_count' => function ($query) {
                $query->whereNotNull('clicked_at');
            }])
            ->withCount(['emailHistories as opened_count' => function ($query) {
                $query->whereNotNull('opened_at');
            }])
            ->latest()
            ->paginate(10);

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Display the specified campaign with statistics and email history.
     */
    public function show(Campaign $campaign)
    {
        // Ensure user can only view their own campaigns
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        $emailHistories = EmailHistory::where('campaign_id', $campaign->id)
            ->latest()
            ->paginate(15);

        $stats = [
            'sent' => $campaign->emailHistories()->where('status', 'sent')->count(),
            'failed' => $campaign->emailHistories()->where('status', 'failed')->count(),
            'open_rate' => $campaign->emailHistories()->where('opened_at', '!=', null)->count(),
            'click_rate' => $campaign->emailHistories()->where('clicked_at', '!=', null)->count(),
        ];

        return view('campaigns.show', [
            'campaign' => $campaign,
            'emailHistories' => $emailHistories,
            'stats' => $stats
        ]);
    }
}
