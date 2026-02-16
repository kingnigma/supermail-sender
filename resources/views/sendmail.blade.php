@extends('layouts.app')

@section('title', 'Send Email')

@section('content')
    <div class="form-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Create Email Campaign</h2>
                <p class="text-muted mb-0">Fill out the form below to send your campaign</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>

        <form method="POST" action="{{ route('send.email') }}" enctype="multipart/form-data" id="campaignForm">
            @csrf

            <!-- Campaign Name -->
            <div class="mb-4">
                <label for="campaignName" class="form-label">Campaign Name</label>
                <input type="text" class="form-control @error('campaign_name') is-invalid @enderror"
                       id="campaignName" name="campaign_name"
                       value="{{ old('campaign_name') }}"
                       placeholder="Enter campaign name" required>
                @error('campaign_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Subject -->
            <div class="mb-4">
                <label for="emailSubject" class="form-label">Email Subject</label>
                <input type="text" class="form-control @error('subject') is-invalid @enderror"
                       id="emailSubject" name="subject"
                       value="{{ old('subject') }}"
                       placeholder="Enter email subject" required>
                @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Contact List Category -->
            <div class="mb-4">
                <label for="contactGroup" class="form-label">Select Contact Group</label>
                <select class="form-select @error('contact_group_id') is-invalid @enderror"
                        id="contactGroup" name="contact_group_id" required>
                    <option selected disabled value="">Choose contact group...</option>
                    @if(isset($contactGroups) && $contactGroups->count() > 0)
                        @foreach($contactGroups as $group)
                            <option value="{{ $group->id }}" {{ old('contact_group_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->name }} ({{ $group->contacts_count ?? 0 }} contacts)
                            </option>
                        @endforeach
                    @else
                        <option disabled>No contact groups available</option>
                    @endif
                </select>
                @error('contact_group_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Message Template -->
            <div class="mb-4">
                <label for="messageTemplate" class="form-label">Message Template</label>
                <select class="form-select @error('message_template') is-invalid @enderror"
                        id="messageTemplate" name="message_template" required>
                    <option selected disabled value="">Select message template...</option>
                    @foreach($messageTemplates as $template)
                        <option value="{{ $template->id }}" {{ old('message_template') == $template->id ? 'selected' : '' }}>
                            {{ $template->name }}
                        </option>
                    @endforeach
                </select>
                @error('message_template')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Message -->
            <div class="mb-4">
                <label for="emailMessage" class="form-label">Message</label>
                <textarea class="form-control @error('message') is-invalid @enderror"
                          id="emailMessage" name="message" rows="6"
                          placeholder="Write your email content here..." required>{{ old('message') }}</textarea>
                <div class="form-text">You can use these dynamic tags: {name}, {email}, {company_name}</div>
                @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Invoice Template -->
            <div class="mb-4">
                <label for="invoiceTemplate" class="form-label">Invoice Template</label>
                <select class="form-select @error('invoice_template') is-invalid @enderror"
                        id="invoiceTemplate" name="invoice_template">
                    <option value="">None (No invoice selected)</option>
                    @if(isset($invoices) && $invoices->count() > 0)
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" {{ old('invoice_template') == $invoice->id ? 'selected' : '' }}>
                                {{ $invoice->title }} ({{ $invoice->formatted_amount }})
                            </option>
                        @endforeach
                    @else
                        <option disabled>No invoice templates available</option>
                    @endif
                </select>
                @error('invoice_template')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- File Attachment -->
            <div class="mb-4">
                <label for="fileAttachment" class="form-label">Attach File</label>
                <input class="form-control @error('attachment') is-invalid @enderror"
                       type="file" id="fileAttachment" name="attachment">
                <div class="form-text">Maximum file size: 5MB (PDF, DOC, JPG, PNG)</div>
                @error('attachment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="button" class="btn btn-outline-secondary me-md-2" id="saveDraftBtn">
                    <i class="bi bi-file-earmark me-1"></i> Save Draft
                </button>
                <button type="submit" class="btn btn-primary" id="sendCampaignBtn">
                    <i class="bi bi-send me-1"></i> Send Campaign
                </button>
            </div>
        </form>
    </div>

    <!-- Progress Modal -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="progressModalLabel">Sending Campaign</h5>
                </div>
                <div class="modal-body">
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                             role="progressbar" id="campaignProgress"
                             style="width: 0%"></div>
                    </div>
                    <div id="progressText">Preparing to send emails...</div>
                    <div id="currentRecipient" class="small text-muted mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const templateSelect = document.getElementById('messageTemplate');
            const messageField = document.getElementById('emailMessage');

            // Load template content when selected
            templateSelect.addEventListener('change', function() {
                const templateId = this.value;

                if (templateId) {
                    fetch(`/message-templates/${templateId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.content) {
                            messageField.value = data.content;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error loading template. Please try again.');
                    });
                }
            });

            // Show progress modal when form is submitted
            const campaignForm = document.getElementById('campaignForm');
            const progressModal = new bootstrap.Modal(document.getElementById('progressModal'));

            campaignForm.addEventListener('submit', function(e) {
                const sendBtn = document.getElementById('sendCampaignBtn');
                sendBtn.disabled = true;
                sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';

                progressModal.show();

                // Set up EventSource to receive progress updates
                const eventSource = new EventSource('/campaign-progress');

                eventSource.onmessage = function(event) {
                    const data = JSON.parse(event.data);
                    const total = data.total || 1;
                    const progress = Math.round((data.progress / total) * 100);

                    // Update progress bar
                    document.getElementById('campaignProgress').style.width = `${progress}%`;

                    // Update status text
                    document.getElementById('progressText').textContent =
                        `Sending emails: ${data.sent_count} sent, ${data.failed_count} failed`;

                    // Update status
                    if (data.status === 'completed') {
                        eventSource.close();
                        progressModal.hide();
                        if (data.campaign_id) {
                            // window.location.href = "{{ route('campaigns.index') }}/" + data.campaign_id;
                        } else {
                            // window.location.href = "{{ route('campaigns.index') }}";
                        }
                    }
                };

                eventSource.onerror = function() {
                    eventSource.close();
                    progressModal.hide();
                    alert('Connection error occurred. Please check the campaign status.');
                    window.location.href = "{{ route('campaigns.index') }}";
                };
            });
        });
    </script>
    @endpush
@endsection
