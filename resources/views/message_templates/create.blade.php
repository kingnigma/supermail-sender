@extends('layouts.app')

@section('title', 'Create Message Template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create New Message Template</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('message-templates.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Template Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Enhanced Formatting Toolbar -->
                        <div class="mb-2 border p-2 bg-light rounded">
                            <div class="d-flex flex-wrap gap-1">
                                <!-- Text Formatting -->
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('bold')" title="Bold">
                                        <i class="bi bi-type-bold"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('italic')" title="Italic">
                                        <i class="bi bi-type-italic"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('underline')" title="Underline">
                                        <i class="bi bi-type-underline"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('strikethrough')" title="Strikethrough">
                                        <i class="bi bi-type-strikethrough"></i>
                                    </button>
                                </div>

                                <!-- Text Alignment -->
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('alignLeft')" title="Align Left">
                                        <i class="bi bi-text-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('alignCenter')" title="Align Center">
                                        <i class="bi bi-text-center"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('alignRight')" title="Align Right">
                                        <i class="bi bi-text-right"></i>
                                    </button>
                                </div>

                                <!-- Lists -->
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertUnorderedList')" title="Bullet List">
                                        <i class="bi bi-list-ul"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertOrderedList')" title="Numbered List">
                                        <i class="bi bi-list-ol"></i>
                                    </button>
                                </div>

                                <!-- Special Formatting -->
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertLink')" title="Insert Link">
                                        <i class="bi bi-link-45deg"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertImage')" title="Insert Image">
                                        <i class="bi bi-image"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertTable')" title="Insert Table">
                                        <i class="bi bi-table"></i>
                                    </button>
                                </div>
                                
                                <!-- Dynamic Tags Dropdown -->
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Insert Dynamic Tag">
                                        <i class="bi bi-tags"></i> Dynamic Tags
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="insertTag('name')">{name} - Full Name</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="insertTag('email')">{email} -  Email Address</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="insertTag('company_name')">{company_name} - Company Name</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Message Field -->
                        <div class="mb-3">
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="8"
                                      placeholder="Write your message here..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-3">
                            <label class="form-label">Message Preview</label>
                            <div class="border p-3 bg-light" id="preview">
                                {!! old('content', 'Your formatted content will appear here...') !!}
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('message-templates.index') }}" class="btn btn-secondary me-md-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Save Template
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
        
        textarea.value = textarea.value.substring(0, start) + before + selectedText + after + textarea.value.substring(end);
        updatePreview();
        textarea.focus();
        textarea.setSelectionRange(start + before.length, start + before.length + selectedText.length);
    }

    function insertTag(tagName) {
        const textarea = document.getElementById('content');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const tag = `{${tagName}}`;
        
        textarea.value = textarea.value.substring(0, start) + tag + textarea.value.substring(end);
        updatePreview();
        textarea.focus();
        textarea.setSelectionRange(start + tag.length, start + tag.length);
    }

    function updatePreview() {
        const content = document.getElementById('content').value;
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
            // Highlight dynamic tags in preview
            .replace(/\{(.*?)\}/g, '<span class="badge bg-info">{$1}</span>')
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