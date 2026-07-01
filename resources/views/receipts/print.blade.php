<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $payment->receipt->receipt_number ?? $payment->reference_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 14px;
            margin: 20px;
            background-color: #fff;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        .header-left img {
            height: 60px;
            margin-right: 15px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            line-height: 1.2;
        }
        .logo-subtitle {
            font-size: 12px;
            color: #4b5563;
        }
        .header-right {
            text-align: right;
        }
        .header-right h1 {
            margin: 0 0 5px 0;
            font-size: 20px;
            color: #1e3a8a;
            letter-spacing: 1px;
        }
        .receipt-meta {
            font-size: 13px;
            color: #4b5563;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-title {
            background-color: #f3f4f6;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14px;
            border: 1px solid #e5e7eb;
            border-bottom: none;
            color: #1f2937;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
        }
        .info-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #4b5563;
            width: 30%;
        }
        .amount-box {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #166534;
            margin-top: 20px;
            padding: 15px;
            border: 2px solid #166534;
            background-color: #f0fdf4;
            display: inline-block;
            float: right;
            border-radius: 6px;
        }
        .signatures {
            margin-top: 80px;
            width: 100%;
            clear: both;
        }
        .signature-col {
            width: 50%;
        }
        .signature-line {
            border-top: 1px solid #9ca3af;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            margin-top: 45px;
            font-size: 12px;
            color: #4b5563;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        .no-print {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.15s ease-in-out;
        }
        .btn-primary {
            background-color: #2563eb;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            border-color: #d1d5db;
            margin-left: 10px;
        }
        .btn-secondary:hover {
            background-color: #e5e7eb;
        }

        @media print {
            body {
                margin: 0;
                background-color: #fff;
            }
            .container {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
        <a href="{{ route('invoices.show', $payment->invoice) }}" class="btn btn-secondary">Back to Invoice</a>
    </div>

    <div class="container">
        <div class="header">
            <div class="header-left">
                <img src="{{ asset('images/compssa_logo.png') }}" alt="COMPSSA Logo">
                <div>
                    <div class="logo-text">HTU COMPSSA</div>
                    <div class="logo-subtitle">Ho Technical University Computer Science Students Association</div>
                    <div class="logo-subtitle">Student Finance Management System</div>
                </div>
            </div>
            <div class="header-right">
                <h1>OFFICIAL RECEIPT</h1>
                <div class="receipt-meta">
                    <strong>Receipt No:</strong> {{ $payment->receipt->receipt_number ?? 'N/A' }}<br>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') }}<br>
                    <strong>Invoice Ref:</strong> {{ $payment->invoice->invoice_number }}
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-title">Received From</div>
            <table class="info-table">
                <tr>
                    <td class="label">Student Name:</td>
                    <td>{{ $payment->invoice->student->first_name }} {{ $payment->invoice->student->other_names }} {{ $payment->invoice->student->last_name }}</td>
                </tr>
                <tr>
                    <td class="label">Index Number:</td>
                    <td>{{ $payment->invoice->student->index_number }}</td>
                </tr>
                <tr>
                    <td class="label">Programme & Level:</td>
                    <td>{{ $payment->invoice->student->programme->name ?? 'N/A' }} - {{ $payment->invoice->student->currentLevel->name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="info-section">
            <div class="info-title">Payment Details</div>
            <table class="info-table">
                <tr>
                    <td class="label">Amount Received:</td>
                    <td style="font-weight: bold; font-size: 15px;">GHS {{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Payment Method:</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                </tr>
                <tr>
                    <td class="label">Transaction Ref:</td>
                    <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                </tr>
                @if($payment->notes)
                    <tr>
                        <td class="label">Notes:</td>
                        <td>{{ $payment->notes }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="amount-box">
            TOTAL PAID: GHS {{ number_format($payment->amount, 2) }}
        </div>

        <table class="signatures">
            <tr>
                <td class="signature-col">
                    <div class="signature-line">Student Signature</div>
                </td>
                <td class="signature-col" style="display: flex; justify-content: flex-end;">
                    <div class="signature-line">
                        Finance Officer<br>
                        <small style="font-weight: bold;">{{ $payment->recordedBy->name ?? 'Admin' }}</small>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            This receipt was generated automatically by the COMPSSA Student Finance Management System.<br>
            Please keep it safe as proof of payment.
        </div>
    </div>

</body>
</html>
