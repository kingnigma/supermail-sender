@extends('layouts.app')

@section('title', 'Invoice #' . str_pad($invoiceTemplate->invoice_number, 6, '0', STR_PAD_LEFT))

@section('content')
    <div class="invoice-container"
        style="max-width: 800px; margin: 0 auto; padding: 40px; font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; background: #fff; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 8px;">
        <!-- Header Section -->
        <div class="invoice-header"
            style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #eee;">
            <h1 style="margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50;">
                {{ $invoiceTemplate->heading ?? 'INVOICE' }}</h1>
            <p style="margin: 5px 0 0; color: #7f8c8d; font-size: 14px;">Invoice
                #{{ str_pad($invoiceTemplate->invoice_number, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Company and Client Info -->
        <div class="flex-container" style="display: flex; justify-content: space-between; margin-bottom: 30px;">
            <div class="from-address" style="width: 48%;">
                <h3
                    style="margin: 0 0 10px; font-size: 16px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                    From</h3>
                <p style="margin: 0; line-height: 1.6; color: #34495e;">
                    {{ $invoiceTemplate->heading ?? 'INVOICE' }}
                </p>
            </div>

            <div class="to-address" style="width: 48%;">
                <h3
                    style="margin: 0 0 10px; font-size: 16px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                    Bill To</h3>
                <p style="margin: 0; line-height: 1.6; color: #34495e;">
                    @if ($invoiceTemplate->address)
                        {!! nl2br(e($invoiceTemplate->address)) !!}
                    @else
                        Client information not specified
                    @endif
                </p>
            </div>
        </div>

        <!-- Invoice Meta -->
        <div class="invoice-meta"
            style="display: flex; justify-content: space-between; background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 30px;">
            <div>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Invoice Date:</strong>
                    {{ $invoiceTemplate->created_at->format('F j, Y') }}</p>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Due Date:</strong> Upon Receipt</p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 5px 0; font-size: 14px;"><strong>Amount Due:</strong></p>
                <p style="margin: 0; font-size: 24px; font-weight: 700; color: #2c3e50;">
                    ${{ number_format($invoiceTemplate->amount, 2) }}</p>
            </div>
        </div>

        <!-- Description Section -->
        <div class="description-section" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #eee;">
            <h3
                style="margin: 0 0 15px; font-size: 16px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                Description</h3>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 6px;">
                <p style="margin: 0; line-height: 1.6; color: #34495e;">
                    {!! preg_replace(
                        [
                            '/\*\*(.*?)\*\*/',
                            '/\*(.*?)\*/',
                            '/_(.*?)_/',
                            '/\{left\}(.*?)\{\/left\}/',
                            '/\{center\}(.*?)\{\/center\}/',
                            '/\{right\}(.*?)\{\/right\}/',
                            '/^- (.*$)/m',
                            '/^1\. (.*$)/m',
                            '/\n/',
                        ],
                        [
                            '<strong>$1</strong>',
                            '<em>$1</em>',
                            '<u>$1</u>',
                            '<div style="text-align: left;">$1</div>',
                            '<div style="text-align: center;">$1</div>',
                            '<div style="text-align: right;">$1</div>',
                            '<li>$1</li>',
                            '<li>$1</li>',
                            '<br>',
                        ],
                        $invoiceTemplate->description,
                    ) !!}


                    {{-- {!! nl2br(e($invoiceTemplate->description)) !!}</p> --}}
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="payment-summary" style="margin-bottom: 40px;">
            <h3
                style="margin: 0 0 15px; font-size: 16px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                Payment Summary</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 12px 15px; text-align: left; font-size: 14px; color: #7f8c8d;">Description</th>
                        <th style="padding: 12px 15px; text-align: right; font-size: 14px; color: #7f8c8d;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 15px; font-size: 14px;">{{ $invoiceTemplate->title }}</td>
                        <td style="padding: 12px 15px; text-align: right; font-size: 14px;">
                            ${{ number_format($invoiceTemplate->amount, 2) }}</td>
                    </tr>
                    <tr style="background: #f8f9fa; font-weight: 600;">
                        <td style="padding: 12px 15px; text-align: right; font-size: 14px;" colspan="1">Total Due:</td>
                        <td style="padding: 12px 15px; text-align: right; font-size: 16px;">
                            ${{ number_format($invoiceTemplate->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Instructions -->
        <div class="payment-instructions"
            style="background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 40px; border-left: 4px solid #3498db;">
            <h3 style="margin: 0 0 15px; font-size: 16px; color: #2c3e50; font-weight: 600;">Payment Instructions</h3>
            @if ($invoiceTemplate->payment_details)
                <p style="margin: 0 0 15px; line-height: 1.6; color: #34495e;">{!! nl2br(e($invoiceTemplate->payment_details)) !!}</p>
            @else
                <p style="margin: 0 0 15px; line-height: 1.6; color: #34495e;">Please contact us for payment instructions.
                </p>
            @endif

        </div>

        <!-- Footer -->
        <div class="invoice-footer"
            style="text-align: center; padding-top: 20px; border-top: 2px solid #eee; color: #7f8c8d; font-size: 12px;">
            <p style="margin: 5px 0;">Thank you for your business!</p>
            <p style="margin: 5px 0;">{{ $invoiceTemplate->heading ?? 'INVOICE' }} • Business Transaction Invoice</p>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons" style="display: flex; justify-content: space-between; margin-top: 40px;">
            <a href="{{ route('invoice-templates.index') }}" class="btn-back"
                style="display: inline-block; padding: 10px 20px; background: #f8f9fa; color: #34495e; text-decoration: none; border-radius: 4px; border: 1px solid #ddd; transition: all 0.3s ease;">
                &larr; Back to Invoices
            </a>
            <div>
                <a href="{{ route('invoice-templates.edit', $invoiceTemplate->id) }}" class="btn-edit"
                    style="display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px; transition: all 0.3s ease;">
                    Edit Invoice
                </a>
                <button onclick="window.print()" class="btn-print"
                    style="padding: 10px 20px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer; transition: all 0.3s ease;">
                    Print Invoice
                </button>
            </div>
        </div>
    </div>

@section('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .invoice-container,
            .invoice-container * {
                visibility: visible;
            }

            .invoice-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                box-shadow: none;
            }

            .action-buttons {
                display: none !important;
            }
        }

        .btn-back:hover {
            background: #e9ecef !important;
        }

        .btn-edit:hover {
            background: #2980b9 !important;
        }

        .btn-print:hover {
            background: #27ae60 !important;
        }

        a {
            transition: all 0.3s ease;
        }
    </style>
@endsection
@endsection

