@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Contacts for Group: {{ $contactGroup->name }}</h1>
        <!--<a href=" /* {{ route('contact-groups.index') }} */ " class="btn btn-secondary">Back to Groups</a>-->
        <a href="../../contact" class="btn btn-secondary">Back to Groups</a>
    </div>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addContactModal">
        Add Contact
    </button>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Company Name</th>
                    <th>Company Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                <tr>
                    <td>{{ $contact->full_name }}</td>
                    <td>{{ $contact->company_name }}</td>
                    <td>{{ $contact->company_email }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editContactModal{{ $contact->id }}">
                            Edit
                        </button>
                        <form action="{{ route('contact-groups.destroy-contact', $contact->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Contact Modal -->
                <div class="modal fade" id="editContactModal{{ $contact->id }}" tabindex="-1" aria-labelledby="editContactModalLabel{{ $contact->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('contact-groups.update-contact', $contact->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editContactModalLabel{{ $contact->id }}">Edit Contact</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="full_name{{ $contact->id }}" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="full_name{{ $contact->id }}" name="full_name" value="{{ $contact->full_name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="company_name{{ $contact->id }}" class="form-label">Company Name</label>
                                        <input type="text" class="form-control" id="company_name{{ $contact->id }}" name="company_name" value="{{ $contact->company_name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="company_email{{ $contact->id }}" class="form-label">Company Email</label>
                                        <input type="email" class="form-control" id="company_email{{ $contact->id }}" name="company_email" value="{{ $contact->company_email }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update Contact</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
        {{ $contacts->links() }}
    </div>
</div>

<!-- Add Contact Modal -->
<div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="addContactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('contact-groups.add-contact', $contactGroup->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addContactModalLabel">Add New Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="company_email" class="form-label">Company Email</label>
                        <input type="email" class="form-control" id="company_email" name="company_email" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Contact</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection