@extends('layouts.app')

@section('title', 'My Email Campaigns')

@section('content')
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th><i class="bi bi-envelope me-2"></i>Campaign Name</th>
                <th><i class="bi bi-chat-left me-2"></i>Subject</th>
                <th class="text-center"><i class="bi bi-check-circle me-2"></i>Sent</th>
                <th class="text-center"><i class="bi bi-exclamation-circle me-2"></i>Failed</th>
                <th class="text-center"><i class="bi bi-hand-index me-2"></i>Clicked</th>
                <th class="text-center"><i class="bi bi-eye me-2"></i>Opened</th>
                <th>Status</th>
                <th><i class="bi bi-calendar-event me-2"></i>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($campaigns as $campaign)
                <tr>
                    <td><span style="font-weight: 600; color: #2d3748;">{{ $campaign->name }}</span></td>
                    <td style="color: #718096;">{{ $campaign->subject }}</td>
                    <td class="text-center"><span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">{{ $campaign->sent_count }}</span></td>
                    <td class="text-center"><span class="badge bg-danger">{{ $campaign->failed_count }}</span></td>
                    <td class="text-center"><span class="badge bg-info">{{ $campaign->clicked_count }}</span></td>
                    <td class="text-center"><span class="badge bg-success">{{ $campaign->opened_count }}</span></td>
                    <td>
                        @if($campaign->status === 'completed')
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>
                        @elseif($campaign->status === 'pending')
                            <span class="badge bg-warning"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                        @else
                            <span class="badge bg-info">{{ ucfirst($campaign->status) }}</span>
                        @endif
                    </td>
                    <td style="color: #718096;">{{ $campaign->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('campaigns.show', $campaign->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Details
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 2.5rem; color: #cbd5e0; display: block; margin-bottom: 10px;"></i>
                        <p style="color: #a0aec0; font-weight: 500;">No campaigns found</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($campaigns->hasPages())
    <div style="margin-top: 20px; text-align: center;">
        {{ $campaigns->links('pagination::bootstrap-4') }}
    </div>
@endif

@endsection
