@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div style="position: relative; z-index: 1;">
    <!-- Stats Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Total Contacts Card -->
        <div class="stat-card primary">
            <i class="bi bi-people" style="font-size: 2.5rem; color: #667eea; margin-bottom: 15px;"></i>
            <div class="stat-value" style="color: #667eea;">{{ $totalContacts }}</div>
            <div class="stat-label">Total Contacts</div>
            <a href="/contact" class="btn btn-sm btn-outline-primary mt-3" style="width: 100%;">
                <i class="bi bi-arrow-right me-1"></i> View All
            </a>
        </div>

        <!-- Emails Sent Card -->
        <div class="stat-card success">
            <i class="bi bi-send" style="font-size: 2.5rem; color: #10b981; margin-bottom: 15px;"></i>
            <div class="stat-value" style="color: #10b981;">{{ $totalEmailCampaigns }}</div>
            <div class="stat-label">Campaigns Sent</div>
            <a href="/send_email" class="btn btn-sm btn-outline-success mt-3" style="width: 100%; color: #10b981; border-color: #10b981;">
                <i class="bi bi-plus-lg me-1"></i> Send New
            </a>
        </div>

        <!-- Templates Card -->
        <div class="stat-card warning">
            <i class="bi bi-envelope" style="font-size: 2.5rem; color: #f59e0b; margin-bottom: 15px;"></i>
            <div class="stat-value" style="color: #f59e0b;">{{ $totalMessageTemplates }}</div>
            <div class="stat-label">Email Templates</div>
            <a href="message-templates" class="btn btn-sm btn-outline-warning mt-3" style="width: 100%; color: #f59e0b; border-color: #f59e0b;">
                <i class="bi bi-gear me-1"></i> Manage
            </a>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="card">
        <div class="card-header">
            <i class="bi bi-clock-history me-2"></i> Recent Activity
        </div>
        <div class="card-body">
            @if(isset($recentActivities) && $recentActivities->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($recentActivities as $activity)
                        <div style="padding: 12px 15px; border-left: 3px solid #667eea; background: #f7fafc; border-radius: 6px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div style="flex: 1;">
                                    <h6 class="mb-1" style="color: #2d3748; font-weight: 600;">{{ $activity->activity_title }}</h6>
                                    <p class="mb-0" style="color: #718096; font-size: 0.9rem;">{{ $activity->description }}</p>
                                </div>
                                <small style="color: #a0aec0; white-space: nowrap; margin-left: 15px; font-weight: 500;">
                                    {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px 20px; color: #a0aec0;">
                    <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    <p style="margin: 0;">No recent activity yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
