<?php

namespace App\Http\Controllers;

use App\Models\InvoiceTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceTemplateController extends Controller
{
    /**
     * Display a listing of invoice templates
     */
    /*public function index()
    {
        $invoices = InvoiceTemplate::latest()->get();
        return view('invoice-templates.index', compact('invoices'));
    }*/

    public function index()
    {
        $invoices = InvoiceTemplate::latest()->get();
        return view('invoice-templates.index', [
            'invoices' => $invoices,
            'selectedInvoice' => $invoices->first() // Select first invoice by default
        ]);
    }

    /**
     * Show the form for creating a new invoice template
     */
    public function create()
    {
        return view('invoice-templates.create');
    }

    /**
     * Store a newly created invoice template
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'heading' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_details' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $template = InvoiceTemplate::create($request->all() + [
            'invoice_number' => rand(111111, 999999)
        ]);

        return redirect()->route('invoice-templates.index')
            ->with('success', 'Invoice template saved successfully!');
    }

    /**
     * Display the specified invoice template
     */
    public function show(InvoiceTemplate $invoiceTemplate)
    {
        return view('invoice-templates.show', compact('invoiceTemplate'));
    }

    /**
     * Show the form for editing an invoice template
     */
    public function edit(InvoiceTemplate $invoiceTemplate)
    {
        return view('invoice-templates.edit', compact('invoiceTemplate'));
    }

    /**
     * Update the specified invoice template
     */
    public function update(Request $request, InvoiceTemplate $invoiceTemplate)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'heading' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_details' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $invoiceTemplate->update($request->all());

        return redirect()->route('invoice-templates.index')
            ->with('success', 'Invoice template updated successfully!');
    }

    /**
     * Remove the specified invoice template
     */
    public function destroy(InvoiceTemplate $invoiceTemplate)
    {
        $invoiceTemplate->delete();

        return redirect()->route('invoice-templates.index')
            ->with('success', 'Invoice template deleted successfully!');
    }
}
