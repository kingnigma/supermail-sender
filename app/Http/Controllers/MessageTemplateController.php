<?php

namespace App\Http\Controllers;

use App\Models\MessageTemplate;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    public function index()
    {
        $templates = MessageTemplate::latest()->get();
        return view('message_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('message_templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        // Convert custom formatting to HTML before saving
        $validated['content'] = $this->convertToHtml($validated['content']);

        MessageTemplate::create($validated);

        return redirect()->route('message-templates.index')
            ->with('success', 'Message template created successfully.');
    }

    public function edit(MessageTemplate $messageTemplate)
    {
        return view('message_templates.edit', compact('messageTemplate'));
    }

    public function update(Request $request, MessageTemplate $messageTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        // Convert custom formatting to HTML before saving
        $validated['content'] = $this->convertToHtml($validated['content']);

        $messageTemplate->update($validated);

        return redirect()->route('message-templates.index')
            ->with('success', 'Message template updated successfully.');
    }

    public function destroy(MessageTemplate $messageTemplate)
    {
        $messageTemplate->delete();

        return redirect()->route('message-templates.index')
            ->with('success', 'Message template deleted successfully.');
    }

    public function show(MessageTemplate $messageTemplate)
    {
        // Return the raw content without HTML tags for easy editing
        return response()->json([
            'message_content' => strip_tags($messageTemplate->content)
        ]);
    }

    private function convertToHtml($content)
    {
        return preg_replace([
            '/\*\*(.*?)\*\*/',
            '/\*(.*?)\*/',
            '/_(.*?)_/',
            '/\{left\}(.*?)\{\/left\}/',
            '/\{center\}(.*?)\{\/center\}/',
            '/\{right\}(.*?)\{\/right\}/',
            '/^- (.*$)/m',
            '/^1\. (.*$)/m',
            '/\n/'
        ], [
            '<strong>$1</strong>',
            '<em>$1</em>',
            '<u>$1</u>',
            '<div style="text-align: left;">$1</div>',
            '<div style="text-align: center;">$1</div>',
            '<div style="text-align: right;">$1</div>',
            '<li>$1</li>',
            '<li>$1</li>',
            '<br>'
        ], $content);
    }
}
