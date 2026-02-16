@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Campaign: {{ $campaign->name }}</h1>
        <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary">Back to Campaigns</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Sent</h5>
                    <p class="card-text display-6">{{ $stats['sent'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Failed</h5>
                    <p class="card-text display-6">{{ $stats['failed'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Open Rate</h5>
                    <p class="card-text display-6">{{ $stats['open_rate'] }} ({{ $stats['sent'] > 0 ? round(($stats['open_rate']/$stats['sent'])*100) : 0 }}%)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Click Rate</h5>
                    <p class="card-text display-6">{{ $stats['click_rate'] }} ({{ $stats['sent'] > 0 ? round(($stats['click_rate']/$stats['sent'])*100) : 0 }}%)</p>
                </div>
            </div>
        </div>
    </div>

    <h3>Email History</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Status</th>
                    <th>Sent At</th>
                    <th>Opened</th>
                    <th>Clicked</th>
                </tr>
            </thead>
            <tbody>
                @foreach($emailHistories as $history)
                <tr>
                    <td>{{ $history->recipient_email }}</td>
                    <td>
                        <span class="badge bg-{{ $history->status === 'sent' ? 'success' : 'danger' }}">
                            {{ ucfirst($history->status) }}
                        </span>
                    </td>
                    <td>{{ $history->sent_at ? \Carbon\Carbon::parse($history->sent_at)->format('M d, Y H:i') : 'Not sent' }}</td>
                    <td>{{ $history->opened_at ? \Carbon\Carbon::parse($history->opened_at)->format('M d, Y H:i') : 'Not opened' }}</td>
                    <td>{{ $history->clicked_at ? \Carbon\Carbon::parse($history->clicked_at)->format('M d, Y H:i') : 'Not clicked' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{ $emailHistories->links() }}
</div>
@endsection