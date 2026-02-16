@extends('layouts.app')

@section('title', 'Contact Groups')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <a href="{{ route('contact.index') }}" class="btn btn-sm btn-outline-success ms-3">
                <i class="bi bi-arrow-left"></i> Back to Contacts
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-header">
            <h2>Contact Group Created</h2>
        </div>
        <div class="card-body">
            <p>Your contact group has been successfully processed.</p>
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('contact.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Back to All Contacts
                </a>
                <a href="{{ route('contact-groups.show', $group->id ?? '') }}" class="btn btn-success">
                    <i class="bi bi-eye"></i> View This Group
                </a>
            </div>
        </div>
    </div>
</div>
@endsection