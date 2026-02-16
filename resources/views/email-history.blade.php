@extends('layouts.app')

@section('title', 'Email History')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Email Campaign History</h2>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bi bi-funnel me-1"></i> Filters
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Sent</th>
                            <th>Failed</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->name }}</td>
                            <td>{{ $campaign->subject }}</td>
                            <td>{{ $campaign->sent_count }}</td>
                            <td>{{ $campaign->failed_count }}</td>
                            <td>
                                <span class="badge bg-{{ $campaign->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </td>
                            <td>{{ $campaign->sent_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('campaigns.show', $campaign->id) }}" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No email campaigns found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('email-history.index') }}" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Campaigns</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="statusFilter" class="form-label">Status</label>
                            <select class="form-select" id="statusFilter" name="status">
                                <option value="">All Statuses</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dateFrom" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="dateFrom" name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="mb-3">
                            <label for="dateTo" class="form-label">Date To</label>
                            <input type="date" class="form-control" id="dateTo" name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Campaign Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="campaignDetailsContent">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Load campaign details via AJAX
    $('#detailsModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const campaignId = button.data('campaign-id');
        const modal = $(this);
        
        // Show loading state
        modal.find('.modal-body').html('<div class="text-center py-4"><div class="spinner-border" role="status"></div></div>');
        
        // Load campaign details
        $.get(`/campaigns/${campaignId}/details`, function(data) {
            modal.find('.modal-body').html(data);
        }).fail(function() {
            modal.find('.modal-body').html('<div class="alert alert-danger">Failed to load campaign details</div>');
        });
    });
</script>
@endpush