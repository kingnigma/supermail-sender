@extends('layouts.app')

@section('title', 'Invoice Templates')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Invoice Management</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="row">
        <!-- Invoice List -->
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Your Invoices</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                        <i class="bi bi-plus-lg"></i> New Invoice
                    </button>
                </div>
                <div class="card-body">
                    @if ($invoices->isEmpty())
                        <div class="alert alert-info">No invoices found. Create your first invoice!</div>
                    @else
                        <div class="list-group">
                            @foreach ($invoices as $invoice)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $invoice->title }} <code onclick="navigator.clipboard.writeText('{{ $invoice->invoice_number }}');alert('{{ $invoice->invoice_number }}'+' copied!')">#{{ $invoice->invoice_number }}</code></h6>

                                            <small class="text-muted">${{ number_format($invoice->amount, 2) }}</small>
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('invoice-templates.show', $invoice->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#editInvoiceModal{{ $invoice->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('invoice-templates.destroy', $invoice->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Invoice Modal -->
                                <div class="modal fade" id="editInvoiceModal{{ $invoice->id }}" tabindex="-1"
                                    aria-labelledby="editInvoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form method="POST"
                                                action="{{ route('invoice-templates.update', $invoice->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editInvoiceModalLabel{{ $invoice->id }}">
                                                        Edit Invoice</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Heading -->
                                                    <div class="mb-3">
                                                        <label for="edit_heading_{{ $invoice->id }}"
                                                            class="form-label">Invoice Heading</label>
                                                        <input type="text"
                                                            class="form-control @error('heading') is-invalid @enderror"
                                                            id="edit_heading_{{ $invoice->id }}" name="heading"
                                                            value="{{ old('heading', $invoice->heading) }}">
                                                        @error('heading')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Title -->
                                                    <div class="mb-3">
                                                        <label for="edit_title_{{ $invoice->id }}"
                                                            class="form-label">Invoice Title*</label>
                                                        <input type="text"
                                                            class="form-control @error('title') is-invalid @enderror"
                                                            id="edit_title_{{ $invoice->id }}" name="title"
                                                            value="{{ old('title', $invoice->title) }}" required>
                                                        @error('title')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- Date -->
                                                    <div class="mb-3">
                                                        <label for="edit_date_{{ $invoice->id }}"
                                                            class="form-label">Invoice Date*</label>
                                                        <input type="date"
                                                            class="form-control @error('date') is-invalid @enderror"
                                                            id="edit_date_{{ $invoice->id }}" name="date"
                                                            value="{{ old('date', $invoice->date) }}" required>
                                                        @error('date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Address -->
                                                    <div class="mb-3">
                                                        <label for="edit_address_{{ $invoice->id }}"
                                                            class="form-label">Billing Address</label>
                                                        <textarea class="form-control @error('address') is-invalid @enderror" id="edit_address_{{ $invoice->id }}"
                                                            name="address" rows="3">{{ old('address', $invoice->address) }}</textarea>
                                                        @error('address')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Description -->
                                                    <div class="mb-3">
                                                        <div>
                                                            <!-- Enhanced Formatting Toolbar -->
                                                            <div class="mb-2 border p-2 bg-light rounded">
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    <!-- Text Formatting -->
                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('bold')"
                                                                            title="Bold">
                                                                            <i class="bi bi-type-bold"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('italic')"
                                                                            title="Italic">
                                                                            <i class="bi bi-type-italic"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('underline')"
                                                                            title="Underline">
                                                                            <i class="bi bi-type-underline"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('strikethrough')"
                                                                            title="Strikethrough">
                                                                            <i class="bi bi-type-strikethrough"></i>
                                                                        </button>
                                                                    </div>

                                                                    <!-- Text Alignment -->
                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('alignLeft')"
                                                                            title="Align Left">
                                                                            <i class="bi bi-text-left"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('alignCenter')"
                                                                            title="Align Center">
                                                                            <i class="bi bi-text-center"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('alignRight')"
                                                                            title="Align Right">
                                                                            <i class="bi bi-text-right"></i>
                                                                        </button>
                                                                    </div>

                                                                    <!-- Lists -->
                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('insertUnorderedList')"
                                                                            title="Bullet List">
                                                                            <i class="bi bi-list-ul"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('insertOrderedList')"
                                                                            title="Numbered List">
                                                                            <i class="bi bi-list-ol"></i>
                                                                        </button>
                                                                    </div>

                                                                    <!-- Special Formatting -->
                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('insertLink')"
                                                                            title="Insert Link">
                                                                            <i class="bi bi-link-45deg"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('insertImage')"
                                                                            title="Insert Image">
                                                                            <i class="bi bi-image"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="formatText{{ $invoice->id }}('insertTable')"
                                                                            title="Insert Table">
                                                                            <i class="bi bi-table"></i>
                                                                        </button>
                                                                    </div>


                                                                </div>
                                                            </div>

                                                        </div>
                                                        <label for="edit_description_{{ $invoice->id }}"
                                                            class="form-label">Description*</label>
                                                        <textarea class="form-control @error('description') is-invalid @enderror" id="edit_description_{{ $invoice->id }}"
                                                            name="description" rows="3" required>{{ old('description', $invoice->description) }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- Preview Section -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Message Preview</label>
                                                        <div class="border p-3 bg-light"
                                                            id="preview_{{ $invoice->id }}">
                                                            {!! old('description', $invoice->description) !!}
                                                        </div>
                                                    </div>

                                                    <!-- Amount -->
                                                    <div class="mb-3">
                                                        <label for="edit_amount_{{ $invoice->id }}"
                                                            class="form-label">Amount*</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number"
                                                                class="form-control @error('amount') is-invalid @enderror"
                                                                id="edit_amount_{{ $invoice->id }}" name="amount"
                                                                value="{{ old('amount', $invoice->amount) }}"
                                                                min="0" step="0.01" required>
                                                            @error('amount')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <!-- Payment Details -->
                                                    <div class="mb-3">
                                                        <label for="edit_payment_details_{{ $invoice->id }}"
                                                            class="form-label">Payment Details</label>
                                                        <textarea class="form-control @error('payment_details') is-invalid @enderror"
                                                            id="edit_payment_details_{{ $invoice->id }}" name="payment_details" rows="3">{{ old('payment_details', $invoice->payment_details) }}</textarea>
                                                        @error('payment_details')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Invoice</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Invoice Preview -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Invoice Preview @if($selectedInvoice)(#{{ $selectedInvoice->invoice_number }})@endif</h5>
                </div>
                <div class="card-body">
                    @if (isset($selectedInvoice) && $selectedInvoice)
                        <div class="invoice-preview">
                            <h4>{{ $selectedInvoice->title }}</h4>
                            <p>{{ $selectedInvoice->heading }}</p>
                            <hr>
                            <p>{{ $selectedInvoice->description }}</p>
                            <p><strong>Amount:</strong> ${{ number_format($selectedInvoice->amount, 2) }}</p>
                            <hr>
                            <p>{{ $selectedInvoice->payment_details }}</p>
                        </div>
                    @else
                        <div class="alert alert-info">Select an invoice to preview or create a new one</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Invoice Modal -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-labelledby="createInvoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('invoice-templates.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createInvoiceModalLabel">Create New Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Heading -->
                        <div class="mb-3">
                            <label for="heading" class="form-label">Invoice Heading*</label>
                            <input type="text" class="form-control @error('heading') is-invalid @enderror"
                                id="heading" name="heading" value="{{ old('heading') }}">
                            @error('heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Invoice Title*</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="date" class="form-label">Invoice Date*</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                                name="date" value="{{ old('date') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Billing Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <div>
                                <!-- Enhanced Formatting Toolbar -->
                                <div class="mb-2 border p-2 bg-light rounded">
                                    <div class="d-flex flex-wrap gap-1">
                                        <!-- Text Formatting -->
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('bold')" title="Bold">
                                                <i class="bi bi-type-bold"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('italic')" title="Italic">
                                                <i class="bi bi-type-italic"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('underline')" title="Underline">
                                                <i class="bi bi-type-underline"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('strikethrough')" title="Strikethrough">
                                                <i class="bi bi-type-strikethrough"></i>
                                            </button>
                                        </div>

                                        <!-- Text Alignment -->
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('alignLeft')" title="Align Left">
                                                <i class="bi bi-text-left"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('alignCenter')" title="Align Center">
                                                <i class="bi bi-text-center"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('alignRight')" title="Align Right">
                                                <i class="bi bi-text-right"></i>
                                            </button>
                                        </div>

                                        <!-- Lists -->
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('insertUnorderedList')" title="Bullet List">
                                                <i class="bi bi-list-ul"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('insertOrderedList')" title="Numbered List">
                                                <i class="bi bi-list-ol"></i>
                                            </button>
                                        </div>

                                        <!-- Special Formatting -->
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('insertLink')" title="Insert Link">
                                                <i class="bi bi-link-45deg"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('insertImage')" title="Insert Image">
                                                <i class="bi bi-image"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="formatText('insertTable')" title="Insert Table">
                                                <i class="bi bi-table"></i>
                                            </button>
                                        </div>


                                    </div>
                                </div>

                            </div>
                            <label for="description" class="form-label">Description*</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Preview Section -->
                        <div class="mb-3">
                            <label class="form-label">Message Preview</label>
                            <div class="border p-3 bg-light" id="preview">
                                {!! old('description', 'Your formatted description will appear here...') !!}
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount*</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                    id="amount" name="amount" value="{{ old('amount') }}" min="0"
                                    step="0.01" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="mb-3">
                            <label for="payment_details" class="form-label">Payment Details</label>
                            <textarea class="form-control @error('payment_details') is-invalid @enderror" id="payment_details"
                                name="payment_details" rows="3">{{ old('payment_details') }}</textarea>
                            @error('payment_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function formatText(command) {
            const textarea = document.getElementById('description');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            let before = '';
            let after = '';

            switch (command) {
                case 'bold':
                    before = after = '**';
                    break;
                case 'italic':
                    before = after = '*';
                    break;
                case 'underline':
                    before = after = '_';
                    break;
                case 'strikethrough':
                    before = after = '~~';
                    break;
                case 'alignLeft':
                    before = '{left}';
                    after = '{/left}';
                    break;
                case 'alignCenter':
                    before = '{center}';
                    after = '{/center}';
                    break;
                case 'alignRight':
                    before = '{right}';
                    after = '{/right}';
                    break;
                case 'insertUnorderedList':
                    before = '- ';
                    break;
                case 'insertOrderedList':
                    before = '1. ';
                    break;
                case 'insertLink':
                    const url = prompt('Enter URL:', 'https://');
                    if (url) {
                        before = `[${selectedText || 'link'}](${url})`;
                        after = '';
                    }
                    break;
                case 'insertImage':
                    const imgUrl = prompt('Enter Image URL:', 'https://');
                    if (imgUrl) {
                        before = `![${selectedText || 'image'}](${imgUrl})`;
                        after = '';
                    }
                    break;
                case 'insertTable':
                    before = '| Header 1 | Header 2 |\n|----------|----------|\n| Cell 1   | Cell 2   |\n';
                    after = '';
                    break;
            }

            textarea.value = textarea.value.substring(0, start) + before + selectedText + after + textarea.value.substring(
                end);
            updatePreview();
            textarea.focus();
            textarea.setSelectionRange(start + before.length, start + before.length + selectedText.length);
        }

        function insertTag(tagName) {
            const textarea = document.getElementById('description');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const tag = `{${tagName}}`;

            textarea.value = textarea.value.substring(0, start) + tag + textarea.value.substring(end);
            updatePreview();
            textarea.focus();
            textarea.setSelectionRange(start + tag.length, start + tag.length);
        }

        function updatePreview() {
            const content = document.getElementById('description').value;
            const preview = document.getElementById('preview');

            // Convert custom formatting to HTML
            let formatted = content
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/_(.*?)_/g, '<u>$1</u>')
                .replace(/~~(.*?)~~/g, '<del>$1</del>')
                .replace(/\{left\}(.*?)\{\/left\}/g, '<div style="text-align: left;">$1</div>')
                .replace(/\{center\}(.*?)\{\/center\}/g, '<div style="text-align: center;">$1</div>')
                .replace(/\{right\}(.*?)\{\/right\}/g, '<div style="text-align: right;">$1</div>')
                .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2">$1</a>')
                .replace(/!\[(.*?)\]\((.*?)\)/g, '<img src="$2" alt="$1" style="max-width: 100%;">')
                .replace(/^- (.*$)/gm, '<li>$1</li>')
                .replace(/^1\. (.*$)/gm, '<li>$1</li>')
                // Highlight dynamic tags in m
                .replace(/\{(.*?)\}/g, '<span class="badge bg-info">{$1}</span>')
                .replace(/\n/g, '<br>');

            preview.innerHTML = formatted || 'Your formatted content will appear here...';
        }

        // Initialize preview and event listeners
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
            document.getElementById('description').addEventListener('input', updatePreview);
        });
    </script>

    @foreach ($invoices as $invoice)
        <script>
            function formatText{{ $invoice->id }}(command) {
                const textarea = document.getElementById('edit_description_{{ $invoice->id }}');
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const selectedText = textarea.value.substring(start, end);
                let before = '';
                let after = '';

                switch (command) {
                    case 'bold':
                        before = after = '**';
                        break;
                    case 'italic':
                        before = after = '*';
                        break;
                    case 'underline':
                        before = after = '_';
                        break;
                    case 'strikethrough':
                        before = after = '~~';
                        break;
                    case 'alignLeft':
                        before = '{left}';
                        after = '{/left}';
                        break;
                    case 'alignCenter':
                        before = '{center}';
                        after = '{/center}';
                        break;
                    case 'alignRight':
                        before = '{right}';
                        after = '{/right}';
                        break;
                    case 'insertUnorderedList':
                        before = '- ';
                        break;
                    case 'insertOrderedList':
                        before = '1. ';
                        break;
                    case 'insertLink':
                        const url = prompt('Enter URL:', 'https://');
                        if (url) {
                            before = `[${selectedText || 'link'}](${url})`;
                            after = '';
                        }
                        break;
                    case 'insertImage':
                        const imgUrl = prompt('Enter Image URL:', 'https://');
                        if (imgUrl) {
                            before = `![${selectedText || 'image'}](${imgUrl})`;
                            after = '';
                        }
                        break;
                    case 'insertTable':
                        before = '| Header 1 | Header 2 |\n|----------|----------|\n| Cell 1   | Cell 2   |\n';
                        after = '';
                        break;
                }

                textarea.value = textarea.value.substring(0, start) + before + selectedText + after + textarea.value.substring(
                    end);
                updatePreview{{ $invoice->id }}();
                textarea.focus();
                textarea.setSelectionRange(start + before.length, start + before.length + selectedText.length);
            }


            function updatePreview{{ $invoice->id }}() {
                const content = document.getElementById('edit_description_{{ $invoice->id }}').value;
                const preview = document.getElementById('preview_{{ $invoice->id }}');

                // Convert custom formatting to HTML
                let formatted = content
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.*?)\*/g, '<em>$1</em>')
                    .replace(/_(.*?)_/g, '<u>$1</u>')
                    .replace(/~~(.*?)~~/g, '<del>$1</del>')
                    .replace(/\{left\}(.*?)\{\/left\}/g, '<div style="text-align: left;">$1</div>')
                    .replace(/\{center\}(.*?)\{\/center\}/g, '<div style="text-align: center;">$1</div>')
                    .replace(/\{right\}(.*?)\{\/right\}/g, '<div style="text-align: right;">$1</div>')
                    .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2">$1</a>')
                    .replace(/!\[(.*?)\]\((.*?)\)/g, '<img src="$2" alt="$1" style="max-width: 100%;">')
                    .replace(/^- (.*$)/gm, '<li>$1</li>')
                    .replace(/^1\. (.*$)/gm, '<li>$1</li>')
                    // Highlight dynamic tags in m
                    .replace(/\{(.*?)\}/g, '<span class="badge bg-info">{$1}</span>')
                    .replace(/\n/g, '<br>');

                preview.innerHTML = formatted || 'Your formatted content will appear here...';
            }

            // Initialize preview and event listeners
            document.addEventListener('DOMContentLoaded', function() {
                updatePreview{{ $invoice->id }}();
                document.getElementById('edit_description_{{ $invoice->id }}').addEventListener('input',
                    updatePreview{{ $invoice->id }});
            });
        </script>
    @endforeach
@endpush
