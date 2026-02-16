<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $invoice->title }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; background: #fff; }
        .invoice-container { max-width: 800px; margin: 0 auto; padding: 40px; }
        .invoice-header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #eee; }
        .flex-container { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .invoice-meta { display: flex; justify-content: space-between; background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 12px 15px; text-align: left; font-size: 14px; color: #7f8c8d; background: #f8f9fa; }
        td { padding: 12px 15px; font-size: 14px; border-bottom: 1px solid #eee; }
        .payment-instructions { background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 40px; border-left: 4px solid #3498db; }
        .invoice-footer { text-align: center; padding-top: 20px; border-top: 2px solid #eee; color: #7f8c8d; font-size: 12px; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <div class="invoice-header">
            <h1 style="margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50;">{{ $invoice->heading ?? 'EVERY URBAN REALTORS' }}</h1>
            <p style="margin: 5px 0 0; color: #7f8c8d; font-size: 14px;">Invoice #{{ $invoice->id }}</p>
        </div>

        <!-- Company and Client Info -->
        <div class="flex-container">
            <div style="width: 48%;">
                <h3 style="margin: 0 0 10px; font-size: 16px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">From</h3>
                <p style="margin: 0; line-height: 1.6; color: #34495e;">
                    {{ $invoice->heading ?? 'EVERY URBAN REALTORS' }}
                </p>
            </div>
            
            <div style="width: 48%;">
                <h3 style="margin: 0 0 10px; font-size: 16px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Bill To</h3>
                <p style="margin: 0; line-height: 1.6; color: #34495e;">
                    {{ $contact->company_name }}<br>
                    {{ $contact->full_name }}<br>
                    {{ $contact->company_email }}
                </p>
            </div>
        </div>

        <!-- Invoice Meta -->
        <div class="invoice-meta">
            <div>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Invoice Date:</strong> {{ now()->format('F j, Y') }}</p>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Due Date:</strong> Upon Receipt</p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 5px 0; font-size: 14px;"><strong>Amount Due:</strong></p>
                <p style="margin: 0; font-size: 24px; font-weight: 700; color: #2c3e50;">${{ number_format($invoice->amount, 2) }}</p>
            </div>
        </div>

        <!-- Description Section -->
        <div style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #eee;">
            <h3 style="margin: 0 0 15px; font-size: 16px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Description</h3>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 6px;">
                <p style="margin: 0; line-height: 1.6; color: #34495e;">{{ $invoice->description }}</p>
            </div>
        </div>

        <!-- Payment Summary -->
        <div style="margin-bottom: 40px;">
            <h3 style="margin: 0 0 15px; font-size: 16px; color: #2c3e50; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Payment Summary</h3>
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
                        <td style="text-align: right; font-size: 16px;">${{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Instructions -->
        <div class="payment-instructions">
            <h3 style="margin: 0 0 15px; font-size: 16px; color: #2c3e50; font-weight: 600;">Payment Instructions</h3>
            <p style="margin: 0 0 15px; line-height: 1.6; color: #34495e;">{!! nl2br(e($invoice->payment_details)) !!}</p>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p style="margin: 5px 0;">Thank you for your business!</p>
            <p style="margin: 5px 0;">{{ $invoice->heading ?? 'EVERY URBAN REALTORS' }} • Business Transaction Invoice</p>
        </div>
    </div>
</body>
</html>