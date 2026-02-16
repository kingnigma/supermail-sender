<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice - {{ $invoice->title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background: #fff;
        }

        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 14px;
        }

        .invoice-header {
            margin-bottom: 14px;
            padding-bottom: 14px;
            border-bottom: 2px solid #eee;
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .invoice-meta {
            position: relative;
            top: 0;
            left: 0;
            right: 0;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 12px 15px;
            text-align: left;
            font-size: 13px;
            color: #7f8c8d;
            background: #f8f9fa;
        }

        td {
            padding: 12px 15px;
            font-size: 13px;
            border-bottom: 1px solid #eee;
        }

        .payment-instructions {
            background: #f8f9fa;
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }

        .invoice-footer {
            text-align: center;
            padding-top: 14px;
            border-top: 2px solid #eee;
            color: #7f8c8d;
            font-size: 12px;
        }

        p {
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <div class="invoice-header">
            <div style="width: 20%; float: left;">
                <img src="{{ asset('/logo.jpg') }}" alt="{{ config('app.name') }}" height="60px;" width="60px">
            </div>
            <div style="width: 80%; margin-left:auto">
                <h1 style="margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; text-indent:-5em">
                    {{ $invoice->heading ?? 'EVERY URBAN REALTORS' }}
                </h1>
                <p style="margin: 5px 0 0; color: #7f8c8d; font-size: 14px;"> {{ $invoice->address }}</p>
            </div>

        </div>

        <!-- Company and Client Info -->
        <div class="flex-container">
            <div style="width: 48%;">
                <h3
                    style="margin: 0 0 10px 0; font-size: 15px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                    Bill To</h3>
                <p style="margin: 0; line-height: 1.6; color: #34495e;">
                    {{ $contact->company_name }}<br>
                    {{ $contact->full_name }}<br>
                    {{-- {{ $invoice->address }} --}}
                </p>
            </div>
        </div>

        <!-- Invoice Meta -->
        <div class="invoice-meta">
            <div style=" width: 50%;margin-right: auto;">
                <p style="margin: 5px 0; font-size: 13px;"> <strong>Invoice #: </strong>
                    {{ $invoice->invoice_number ? $invoice->invoice_number : rand(1111, 999999) }}</p>
                <p style="margin: 5px 0; font-size: 13px;"><strong>Invoice Date:</strong>
                    {{ $invoice->date ? $invoice->date->format('F j, Y') : now()->format('F j, Y') }}
                </p>
                <p style="margin: 5px 0; font-size: 13px;"><strong>Due Date:</strong> Upon Receipt</p>
            </div>
            <div style="text-align: right;width: 50%;position: absolute;right: 20px;top: 20px;">
                <p style="margin: 5px 0; font-size: 13px;"><strong>Amount Due:</strong></p>
                <p style="margin: 0; font-size: 14px; font-weight: 700; color: #2c3e50;">
                    ${{ number_format($invoice->amount, 2) }}</p>
            </div>
        </div>

        <!-- Description Section -->
        <div style="margin-bottom: 16px; border-bottom: 2px solid #eee;">
            <h3
                style=" margin: 0 0 8px 0; font-size: 15px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                Description</h3>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 6px;">
                <p style="margin: 0; color: #34495e;">{!! preg_replace(
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
                    $invoice->description,
                ) !!}</p>
            </div>
        </div>

        <!-- Payment Summary -->
        <div style="margin-bottom: 30px;">
            <h3
                style=" margin: 0 0 8px; font-size: 15px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                Payment Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $invoice->title }}</td>
                        <td style="text-align: right;">${{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                    <tr style="background: #f8f9fa; font-weight: 600;">
                        <td style="text-align: right;" colspan="1">Total Due:</td>
                        <td style="text-align: right; font-size: 15px;">${{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Instructions -->
        <div class="payment-instructions">
            <h3 style=" margin: 0 0 8px 0; font-size: 14px; color: #2c3e50; font-weight: 600;">Payment Instructions</h3>
            <p style=" margin: 0 0 8px 0; color: #34495e;">{!! nl2br(e($invoice->payment_details)) !!}</p>
        </div>
    </div>
</body>

</html>
