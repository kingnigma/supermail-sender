@extends('layouts.app')

@section('title', 'Edit Invoice #' . str_pad($invoiceTemplate->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Invoice</h2>
        <a href="{{ route('invoice-templates.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Invoices
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('invoice-templates.update', $invoiceTemplate->id) }}">
                @csrf
                @method('PUT')

                <!-- Heading -->
                <div class="mb-4">
                    <label for="heading" class="form-label">Invoice Heading</label>
                    <input type="text" class="form-control @error('heading') is-invalid @enderror" 
                           id="heading" name="heading" 
                           value="{{ old('heading', $invoiceTemplate->heading) }}"
                           placeholder="e.g. INVOICE">
                    @error('heading')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="form-label">Invoice Title*</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" 
                           value="{{ old('title', $invoiceTemplate->title) }}"
                           placeholder="e.g. Website Development Services" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label for="address" class="form-label">Billing Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" name="address" rows="3"
                              placeholder="Client's billing address">{{ old('address', $invoiceTemplate->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label">Description*</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="5"
                              placeholder="Detailed description of services/products" required>{{ old('description', $invoiceTemplate->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="mb-4">
                    <label for="amount" class="form-label">Amount*</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount"
                               value="{{ old('amount', $invoiceTemplate->amount) }}"
                               min="0" step="0.01" placeholder="0.00" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="mb-4">
                    <label for="payment_details" class="form-label">Payment Details</label>
                    <textarea class="form-control @error('payment_details') is-invalid @enderror" 
                              id="payment_details" name="payment_details" rows="3"
                              placeholder="Bank details, payment terms, etc.">{{ old('payment_details', $invoiceTemplate->payment_details) }}</textarea>
                    @error('payment_details')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Update Invoice
                    </button>
                    
                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                        <i class="bi bi-trash me-1"></i> Delete Invoice
                    </button>
                </div>
            </form>

            <!-- Delete Form (Hidden) -->
            <form id="deleteForm" action="{{ route('invoice-templates.destroy', $invoiceTemplate->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this invoice? This action cannot be undone.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection
@endsection