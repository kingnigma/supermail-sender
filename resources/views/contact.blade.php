@extends('layouts.app')

@section('title', 'Contacts')

@section('content')
<style>
    .btn-outline-success:hover {
        background: #10b981;
        border-color: #10b981;
        color: white !important;
    }
    .pagination {
        margin-bottom: 0;
    }
</style>

<!-- Page Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div></div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGroupModal">
        <i class="bi bi-plus-lg me-2"></i> Create New Group
    </button>
</div>

@if($groups->isEmpty())
    <div class="card text-center py-5">
        <div class="card-body">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e0; margin-bottom: 20px; display: block;"></i>
            <h4 style="color: #718096; font-weight: 600;">No Contact Groups Yet</h4>
            <p style="color: #a0aec0; margin-bottom: 20px;">Create your first contact group to get started</p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                <i class="bi bi-plus-lg me-2"></i> Create Your First Group
            </button>
        </div>
    </div>
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-folder me-2"></i>Group Name</th>
                        <th>Description</th>
                        <th class="text-center"><i class="bi bi-people me-2"></i>Contacts</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $group)
                    <tr>
                        <td>
                            <span style="font-weight: 600; color: #2d3748;">{{ $group->name }}</span>
                        </td>
                        <td style="color: #718096;">{{ $group->description ?? '—' }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $group->contacts_count }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('contact-groups.show', $group->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($groups->hasPages())
            <div class="card-footer text-center">
                {{ $groups->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endif

<!-- Create Group Modal -->
<div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('contact-groups.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createGroupModalLabel"><i class="bi bi-plus-circle me-2"></i>Create New Contact Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label"><i class="bi bi-tag me-2"></i>Group Name <span style="color: #dc3545;">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="e.g., VIP Clients" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label"><i class="bi bi-chat-left me-2"></i>Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Add a description for this group..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label"><i class="bi bi-file-earmark me-2"></i>Contact File <span style="color: #dc3545;">*</span></label>
                        <input type="file" class="form-control" id="file" name="file" accept=".txt,.csv" required>
                        <div style="font-size: 0.85rem; color: #718096; margin-top: 8px;">
                            <strong>Accepted formats:</strong> .txt or .csv (Max 2MB)<br>
                            <strong>Format:</strong> Full Name, Company Name, Company Email
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Create Group
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection