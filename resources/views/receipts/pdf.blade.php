<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $payment->receipt->receipt_number ?? $payment->reference_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.5; font-size: 14px; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .logo { font-size: 24px; font-weight: bold; color: #2563eb; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #666; }
        .receipt-details { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .receipt-details td { padding: 5px; }
        .title { font-size: 20px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1e3a8a; }
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table th { text-align: left; background-color: #f3f4f6; padding: 8px; border: 1px solid #e5e7eb; }
        .info-table td { padding: 8px; border: 1px solid #e5e7eb; }
        .amount-box { text-align: right; font-size: 18px; font-weight: bold; color: #166534; margin-top: 20px; padding: 15px; border: 2px solid #166534; background-color: #f0fdf4; display: inline-block; float: right; }
        .footer { clear: both; text-align: center; margin-top: 50px; font-size: 12px; color: #666; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .signatures { margin-top: 60px; width: 100%; }
        .signature-line { border-top: 1px solid #333; width: 200px; text-align: center; padding-top: 5px; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="header">
            <div class="logo">HTU COMPSSA</div>
            <div class="subtitle">Ho Technical University Computer Science Students Association</div>
            <div class="subtitle">Student Finance Management System</div>
        </div>

        <table class="receipt-details">
            <tr>
                <td width="50%">
                    <div class="title">OFFICIAL RECEIPT</div>
                </td>
                <td width="50%" style="text-align: right;">
                    <strong>Receipt No:</strong> {{ $payment->receipt->receipt_number ?? 'N/A' }}<br>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') }}<br>
                    <strong>Invoice Ref:</strong> {{ $payment->invoice->invoice_number }}
                </td>
            </tr>
        </table>

        <table class="info-table" cellspacing="0">
            <tr>
                <th colspan="2">Received From</th>
            </tr>
            <tr>
                <td width="30%"><strong>Student Name:</strong></td>
                <td>{{ $payment->invoice->student->first_name }} {{ $payment->invoice->student->last_name }}</td>
            </tr>
            <tr>
                <td><strong>Student ID:</strong></td>
                <td>{{ $payment->invoice->student->student_number }}</td>
            </tr>
            <tr>
                <td><strong>Programme:</strong></td>
                <td>{{ $payment->invoice->student->programme->name ?? 'N/A' }} ({{ $payment->invoice->student->currentLevel->name ?? 'N/A' }})</td>
            </tr>
        </table>

        <table class="info-table" cellspacing="0">
            <tr>
                <th colspan="2">Payment Details</th>
            </tr>
            <tr>
                <td width="30%"><strong>Amount Received:</strong></td>
                <td><strong>GHS {{ number_format($payment->amount, 2) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Payment Method:</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
            </tr>
            <tr>
                <td><strong>Transaction Ref:</strong></td>
                <td>{{ $payment->reference_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Notes:</strong></td>
                <td>{{ $payment->notes ?? 'N/A' }}</td>
            </tr>
        </table>

        <div class="amount-box">
            TOTAL PAID: GHS {{ number_format($payment->amount, 2) }}
        </div>

        <table class="signatures">
            <tr>
                <td>
                    <div class="signature-line">
                        Student Signature
                    </div>
                </td>
                <td align="right">
                    <div class="signature-line" style="float: right;">
                        Finance Officer<br>
                        <small>{{ $payment->recordedBy->name ?? 'Admin' }}</small>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            This receipt is generated automatically by the COMPSSA Finance System.<br>
            Please keep it safe for future reference.
        </div>

    </div>
</body>
</html>
