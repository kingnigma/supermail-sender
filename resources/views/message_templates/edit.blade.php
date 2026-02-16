@extends('layouts.app')

@section('title', 'Edit Message Template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Message Template</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('message-templates.update', $messageTemplate) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Template Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $messageTemplate->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Formatting Toolbar -->
                        <div class="mb-2 border p-2 bg-light rounded">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="formatText('bold')">
                                    <i class="bi bi-type-bold"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="formatText('italic')">
                                    <i class="bi bi-type-italic"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="formatText('underline')">
                                    <i class="bi bi-type-underline"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertLeft')">
                                    <i class="bi bi-text-left"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertCenter')">
                                    <i class="bi bi-text-center"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertRight')">
                                    <i class="bi bi-text-right"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertUnorderedList')">
                                    <i class="bi bi-list-ul"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertOrderedList')">
                                    <i class="bi bi-list-ol"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Message Field -->
                        <div class="mb-3">
                            <label for="content" class="form-label">Message Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="8" required>{{ old('content', $messageTemplate->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-3">
                            <label class="form-label">Message Preview</label>
                            <div class="border p-3 bg-light" id="preview">
                                {!! old('content', $messageTemplate->content) !!}
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('message-templates.index') }}" class="btn btn-secondary me-md-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Update Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatText(command) {
        const textarea = document.getElementById('content');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end);
        let before = '';
        let after = '';
        
        switch(command) {
            case 'bold':
                before = after = '**';
                break;
            case 'italic':
                before = after = '*';
                break;
            case 'underline':
                before = after = '_';
                break;
            case 'insertLeft':
                before = '{left}';
                after = '{/left}';
                break;
            case 'insertCenter':
                before = '{center}';
                after = '{/center}';
                break;
            case 'insertRight':
                before = '{right}';
                after = '{/right}';
                break;
            case 'insertUnorderedList':
                before = '- ';
                break;
            case 'insertOrderedList':
                before = '1. ';
                break;
        }
        
        textarea.value = textarea.value.substring(0, start) + before + selectedText + after + textarea.value.substring(end);
        updatePreview();
        textarea.focus();
        textarea.setSelectionRange(start + before.length, start + before.length + selectedText.length);
    }

    function updatePreview() {
        const content = document.getElementById('content').value;
        const preview = document.getElementById('preview');
        
        // Convert custom formatting to HTML
        let formatted = content
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/_(.*?)_/g, '<u>$1</u>')
            .replace(/\{left\}(.*?)\{\/left\}/g, '<div style="text-align: left;">$1</div>')
            .replace(/\{center\}(.*?)\{\/center\}/g, '<div style="text-align: center;">$1</div>')
            .replace(/\{right\}(.*?)\{\/right\}/g, '<div style="text-align: right;">$1</div>')
            .replace(/^- (.*$)/gm, '<li>$1</li>')
            .replace(/^1\. (.*$)/gm, '<li>$1</li>')
            .replace(/\n/g, '<br>');
            
        preview.innerHTML = formatted || 'Your formatted content will appear here...';
    }

    // Initialize preview and event listeners
    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
        document.getElementById('content').addEventListener('input', updatePreview);
    });
</script>
@endpush