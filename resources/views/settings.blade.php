@extends('layouts.app')

@section('title', 'Email API Settings')

@section('content')
<style>
    .api-service-button {
        display: inline-block;
        padding: 12px 20px;
        margin: 8px 4px;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #667eea;
    }

    .api-service-button:hover {
        border-color: #667eea;
        background: #f7fafc;
    }

    .api-service-button.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .api-configuration {
        margin-top: 30px;
        padding: 25px;
        background: #f7fafc;
        border-radius: 12px;
        border-left: 4px solid #667eea;
    }

    .api-configuration h5 {
        color: #2d3748;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .test-api-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        border: none;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .test-api-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
</style>

<div style="margin-bottom: 30px;">
    <h2 style="color: #2d3748; font-weight: 700; margin-bottom: 5px;">Email API Configuration</h2>
    <p style="color: #718096; margin: 0;">Configure your preferred email service provider for sending campaigns</p>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('settings.email-api.update') }}">
            @csrf
            @method('PUT')

            <!-- Active Email Service Selection -->
            <div class="mb-4">
                <label class="form-label"><i class="bi bi-server me-2"></i>Select Email Service</label>
                <div style="padding: 15px; background: #f7fafc; border-radius: 8px;">
                    @foreach ($emailServices as $service)
                        <label class="api-service-button {{ $activeServiceId == $service['id'] ? 'active' : '' }}">
                            <input type="radio" name="active_service" value="{{ $service['id'] }}" 
                                   {{ $activeServiceId == $service['id'] ? 'checked' : '' }} 
                                   style="margin-right: 8px;">
                            <i class="bi {{ $service['icon'] }} me-2"></i>{{ $service['name'] }}
                            @if ($activeServiceId == $service['id'])
                                <span style="margin-left: 8px; font-size: 0.85rem;">✓ Active</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Mailchimp API Configuration -->
            <div class="api-configuration {{ $activeServiceId != 'mailchimp' ? 'd-none' : '' }}" id="mailchimp-config">
                <h5><i class="bi bi-envelope me-2"></i>Mailchimp API Settings</h5>
                <div class="mb-3">
                    <label for="mailchimp_api_key" class="form-label">API Key</label>
                    <input type="password" class="form-control @error('mailchimp_api_key') is-invalid @enderror"
                        id="mailchimp_api_key" name="mailchimp_api_key"
                        value="{{ old('mailchimp_api_key', $apiCredentials['mailchimp_api_key'] ?? '') }}"
                        placeholder="Enter your Mailchimp API key">
                    @error('mailchimp_api_key')
                        <div class="invalid-feedback"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="mailchimp_server_prefix" class="form-label">Server Prefix</label>
                    <input type="text" class="form-control @error('mailchimp_server_prefix') is-invalid @enderror"
                        id="mailchimp_server_prefix" name="mailchimp_server_prefix"
                        value="{{ old('mailchimp_server_prefix', $apiCredentials['mailchimp_server_prefix'] ?? '') }}"
                        placeholder="e.g., us1, us2">
                    @error('mailchimp_server_prefix')
                        <div class="invalid-feedback"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
                <button type="button" class="btn btn-sm test-api-btn" data-service="mailchimp">
                    <i class="bi bi-plug me-1"></i> Test Connection
                </button>
            </div>

            <!-- SMTP Configuration -->
            <div class="api-configuration {{ $activeServiceId != 'smtp' ? 'd-none' : '' }}" id="smtp-config">
                <h5><i class="bi bi-envelope me-2"></i>SMTP Settings</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="smtp_host" class="form-label">SMTP Host</label>
                            <input type="text" class="form-control @error('smtp_host') is-invalid @enderror"
                                id="smtp_host" name="smtp_host"
                                value="{{ old('smtp_host', $apiCredentials['smtp_host'] ?? '') }}"
                                placeholder="smtp.example.com">
                            @error('smtp_host')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="smtp_port" class="form-label">Port</label>
                            <input type="number" class="form-control @error('smtp_port') is-invalid @enderror"
                                id="smtp_port" name="smtp_port"
                                value="{{ old('smtp_port', $apiCredentials['smtp_port'] ?? '587') }}" placeholder="587">
                            @error('smtp_port')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="smtp_username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('smtp_username') is-invalid @enderror"
                                id="smtp_username" name="smtp_username"
                                value="{{ old('smtp_username', $apiCredentials['smtp_username'] ?? '') }}"
                                placeholder="SMTP username">
                            @error('smtp_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="smtp_password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('smtp_password') is-invalid @enderror"
                                id="smtp_password" name="smtp_password"
                                value="{{ old('smtp_password', $apiCredentials['smtp_password'] ?? '') }}"
                                placeholder="SMTP password">
                            @error('smtp_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- new field --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="smtp_from_name" class="form-label">From name</label>
                            <input type="text" class="form-control @error('smtp_from_name') is-invalid @enderror"
                                id="smtp_from_name" name="smtp_from_name"
                                value="{{ old('smtp_from_name', $apiCredentials['smtp_from_name'] ?? '') }}"
                                placeholder="SMTP Mail Received From User name">
                            @error('smtp_from_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="smtp_mail_from" class="form-label">Sender Email </label>
                            <input type="text" class="form-control @error('smtp_mail_from') is-invalid @enderror"
                                id="smtp_mail_from" name="smtp_mail_from"
                                value="{{ old('smtp_replay_to', $apiCredentials['smtp_mail_from'] ?? '') }}"
                                placeholder="SMTP mail from">
                            @error('smtp_mail_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- new field end --}}
                     <div class="row">
                         <div class="col-md-6 mb-3">
                            <label for="smtp_replay_to" class="form-label">Replay to Mail</label>
                            <input type="text" class="form-control @error('smtp_replay_to') is-invalid @enderror"
                                id="smtp_replay_to" name="smtp_replay_to"
                                value="{{ old('smtp_replay_to', $apiCredentials['smtp_replay_to'] ?? '') }}"
                                placeholder="SMTP replay to">
                            @error('smtp_replay_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="smtp_encryption" class="form-label">Encryption</label>
                            <select class="form-select @error('smtp_encryption') is-invalid @enderror" id="smtp_encryption"
                                name="smtp_encryption">
                                <option value="tls"
                                    {{ old('smtp_encryption', $apiCredentials['smtp_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>
                                    TLS</option>
                                <option value="ssl"
                                    {{ old('smtp_encryption', $apiCredentials['smtp_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>
                                    SSL</option>
                                <option value=""
                                    {{ old('smtp_encryption', $apiCredentials['smtp_encryption'] ?? '') == '' ? 'selected' : '' }}>
                                    None</option>
                            </select>
                            @error('smtp_encryption')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary test-api-btn" data-service="smtp">
                        <i class="bi bi-plug me-1"></i> Test Connection
                    </button>
                </div>

                <!-- Mailgun Configuration -->
                <div class="api-configuration {{ $activeServiceId != 'mailgun' ? 'd-none' : '' }}" id="mailgun-config">
                    <h5 class="mb-3"><i class="bi bi-send me-2"></i> Mailgun API</h5>
                    <div class="mb-3">
                        <label for="mailgun_domain" class="form-label">Domain</label>
                        <input type="text" class="form-control @error('mailgun_domain') is-invalid @enderror"
                            id="mailgun_domain" name="mailgun_domain"
                            value="{{ old('mailgun_domain', $apiCredentials['mailgun_domain'] ?? '') }}"
                            placeholder="yourdomain.com">
                        @error('mailgun_domain')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="mailgun_api_key" class="form-label">API Key</label>
                        <input type="password" class="form-control @error('mailgun_api_key') is-invalid @enderror"
                            id="mailgun_api_key" name="mailgun_api_key"
                            value="{{ old('mailgun_api_key', $apiCredentials['mailgun_api_key'] ?? '') }}"
                            placeholder="Enter your Mailgun API key">
                        @error('mailgun_api_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="mailgun_region" class="form-label">Region</label>
                        <select class="form-select @error('mailgun_region') is-invalid @enderror" id="mailgun_region"
                            name="mailgun_region">
                            <option value="us"
                                {{ old('mailgun_region', $apiCredentials['mailgun_region'] ?? '') == 'us' ? 'selected' : '' }}>
                                United States</option>
                            <option value="eu"
                                {{ old('mailgun_region', $apiCredentials['mailgun_region'] ?? '') == 'eu' ? 'selected' : '' }}>
                                Europe</option>
                        </select>
                        @error('mailgun_region')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary test-api-btn" data-service="mailgun">
                        <i class="bi bi-plug me-1"></i> Test Connection
                    </button>
                </div>

                <!-- Postmark Configuration -->
                <div class="api-configuration {{ $activeServiceId != 'postmark' ? 'd-none' : '' }}" id="postmark-config">
                    <h5 class="mb-3"><i class="bi bi-envelope-paper me-2"></i> Postmark API</h5>
                    <div class="mb-3">
                        <label for="postmark_api_key" class="form-label">API Key</label>
                        <input type="password" class="form-control @error('postmark_api_key') is-invalid @enderror"
                            id="postmark_api_key" name="postmark_api_key"
                            value="{{ old('postmark_api_key', $apiCredentials['postmark_api_key'] ?? '') }}"
                            placeholder="Enter your Postmark API key">
                        @error('postmark_api_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="postmark_server_token" class="form-label">Server Token</label>
                        <input type="password" class="form-control @error('postmark_server_token') is-invalid @enderror"
                            id="postmark_server_token" name="postmark_server_token"
                            value="{{ old('postmark_server_token', $apiCredentials['postmark_server_token'] ?? '') }}"
                            placeholder="Enter your server token">
                        @error('postmark_server_token')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary test-api-btn" data-service="postmark">
                        <i class="bi bi-plug me-1"></i> Test Connection
                    </button>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-outline-success px-4" id="activateBtn">
                        <i class="bi bi-lightning-fill me-1"></i> Activate Selected
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Save Settings
                    </button>
                </div>

                <!-- Hidden form for activation -->
                <form id="activateForm" method="POST" action="{{ route('settings.email-api.activate') }}" style="display: none;">
                    @csrf
                    <input type="hidden" id="activateServiceInput" name="service_type" value="">
                </form>
            </form>
        </div>
    </div>

    <!-- Test Connection Modal -->
    <div class="modal fade" id="testConnectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Testing Connection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="testConnectionResult">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Testing API connection...</p>
                    </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Show/hide API configuration sections based on selected service
            const serviceRadios = document.querySelectorAll('input[name="active_service"]');

            serviceRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.api-configuration').forEach(el => {
                        el.classList.add('d-none');
                    });

                    const configId = this.value + '-config';
                    const configElement = document.getElementById(configId);
                    if (configElement) {
                        configElement.classList.remove('d-none');
                    }
                });
            });

            // Test API connection buttons
            document.querySelectorAll('.test-api-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const service = this.dataset.service;
                    const modal = new bootstrap.Modal(document.getElementById(
                        'testConnectionModal'));
                    modal.show();

                    // Collect form data for the service
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                        .content);
                    formData.append('service', service);

                    // Add service-specific fields
                    if (service === 'mailchimp') {
                        formData.append('api_key', document.getElementById('mailchimp_api_key')
                            .value);
                        formData.append('server_prefix', document.getElementById(
                            'mailchimp_server_prefix').value);
                    } else if (service === 'smtp') {
                        formData.append('host', document.getElementById('smtp_host').value);
                        formData.append('port', document.getElementById('smtp_port').value);
                        formData.append('username', document.getElementById('smtp_username').value);
                        formData.append('password', document.getElementById('smtp_password').value);
                        formData.append('encryption', document.getElementById('smtp_encryption')
                            .value);
                    } else if (service === 'mailgun') {
                        formData.append('domain', document.getElementById('mailgun_domain').value);
                        formData.append('api_key', document.getElementById('mailgun_api_key')
                        .value);
                        formData.append('region', document.getElementById('mailgun_region').value);
                    } else if (service === 'postmark') {
                        formData.append('api_key', document.getElementById('postmark_api_key')
                            .value);
                        formData.append('server_token', document.getElementById(
                            'postmark_server_token').value);
                    }

                    // Test the connection via AJAX
                    fetch('{{ route('settings.email-api.test') }}', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            const resultElement = document.getElementById(
                                'testConnectionResult');
                            if (data.success) {
                                resultElement.innerHTML = `
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill"></i> Connection successful!
                                <p class="mt-2 mb-0">${data.message}</p>
                            </div>
                        `;
                            } else {
                                resultElement.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle-fill"></i> Connection failed
                                <p class="mt-2 mb-0">${data.message}</p>
                            </div>
                        `;
                            }
                        })
                        .catch(error => {
                            document.getElementById('testConnectionResult').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i> An error occurred while testing connection
                        </div>
                    `;
                        });
                });
            });

            // Activate button handler
            document.getElementById('activateBtn').addEventListener('click', function() {
                const selectedService = document.querySelector('input[name="active_service"]:checked');
                
                if (!selectedService) {
                    alert('Please select an email service first.');
                    return;
                }

                const serviceType = selectedService.value;
                document.getElementById('activateServiceInput').value = serviceType;
                document.getElementById('activateForm').submit();
            });
        });
    </script>
@endpush
