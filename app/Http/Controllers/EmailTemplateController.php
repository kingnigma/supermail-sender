<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    public function create()
    {
        return view('email-templates');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Save the template (example implementation)
        $template = EmailTemplate::updateOrCreate(
            ['name' => $request->name],
            ['content' => $request->content]
        );

        return redirect()->route('email-templates.edit', $template)
            ->with('success', 'Template saved successfully!');
    }

    public function edit(EmailTemplate $template)
    {
        return view('email-templates', ['template' => $template]);
    }

    public function show(EmailTemplate $template)
    {
        return response()->json([
            'message_content' => $template->content
        ]);
    }
}
