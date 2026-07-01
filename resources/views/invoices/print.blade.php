<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #1f2937;
            margin: 20px;
            background: #fff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            padding: 30px;
            border-radius: 8px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        .header-left img {
            height: 65px;
            margin-right: 15px;
        }
        .org-name {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
            line-height: 1.3;
        }
        .org-subtitle {
            font-size: 11px;
            color: #4b5563;
        }
        .invoice-meta {
            text-align: right;
            font-size: 12px;
        }
        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            background-color: #f3f4f6;
            padding: 7px 10px;
            border: 1px solid #e5e7eb;
            border-bottom: none;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-body {
            border: 1px solid #e5e7eb;
            padding: 12px;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .label { color: #6b7280; font-size: 11px; margin-bottom: 2px; }
        .value { font-weight: 600; font-size: 13px; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background-color: #f9fafb;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
        }
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f3f4f6;
        }
        .total-row td {
            font-weight: bold;
            font-size: 15px;
            border-top: 2px solid #374151;
            background: #f9fafb;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-unpaid { background: #fee2e2; color: #991b1b; }
        .status-partial { background: #fef9c3; color: #854d0e; }
        .status-paid { background: #dcfce7; color: #166534; }
        .summary-box {
            float: right;
            width: 240px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
            margin-top: 10px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 12px;
            font-size: 12px;
            border-bottom: 1px solid #f3f4f6;
        }
        .summary-row.balance {
            font-weight: bold;
            font-size: 15px;
            background: #f9fafb;
            border-top: 2px solid #374151;
        }
        .balance-due { color: #dc2626; }
        .balance-paid { color: #16a34a; }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
            clear: both;
        }
        .no-print {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            max-width: 800px;
            margin: 0 auto 20px;
        }
        .btn {
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
        }
        .btn-primary { background-color: #2563eb; color: #fff; }
        .btn-secondary { background-color: #f3f4f6; color: #374151; border-color: #d1d5db; }

        @media print {
            .no-print { display: none; }
            body { margin: 0; }
            .container { border: none; padding: 0; box-shadow: none; max-width: 100%; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">Back to Invoice</a>
    </div>

    <div class="container">

        {{-- Header --}}
        <div class="header">
            <div class="header-left">
                <img src="{{ asset('images/compssa_logo.png') }}" alt="COMPSSA Logo">
                <div>
                    <div class="org-name">HTU COMPSSA</div>
                    <div class="org-subtitle">Ho Technical University</div>
                    <div class="org-subtitle">Computer Science Students Association</div>
                    <div class="org-subtitle">Student Finance Management System</div>
                </div>
            </div>
            <div class="invoice-meta">
                <div class="invoice-title">Invoice</div>
                <div><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</div>
                <div><strong>Session:</strong> {{ $invoice->academicSession->name }}</div>
                <div><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</div>
                <div style="margin-top: 8px;">
                    <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
                </div>
            </div>
        </div>

        {{-- Student Info --}}
        <div class="section">
            <div class="section-title">Billed To</div>
            <div class="section-body">
                <div class="grid-2">
                    <div>
                        <div class="label">Student Name</div>
                        <div class="value">{{ $invoice->student->first_name }} {{ $invoice->student->other_names }} {{ $invoice->student->last_name }}</div>
                    </div>
                    <div>
                        <div class="label">Index Number</div>
                        <div class="value">{{ $invoice->student->index_number }}</div>
                    </div>
                    <div>
                        <div class="label">Programme</div>
                        <div class="value">{{ $invoice->student->programme->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="label">Level</div>
                        <div class="value">{{ $invoice->student->currentLevel->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Invoice Items --}}
        <div class="section">
            <div class="section-title">Invoice Items</div>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->due->category_name }}</td>
                            <td style="text-align: right;">GHS {{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td style="text-align: right; padding-right: 20px;">Total Amount:</td>
                        <td style="text-align: right;">GHS {{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Summary --}}
        <div class="summary-box">
            <div class="summary-row">
                <span>Total Billed</span>
                <span>GHS {{ number_format($invoice->total_amount, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Total Paid</span>
                <span style="color: #16a34a;">GHS {{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
            <div class="summary-row balance">
                <span>Balance Due</span>
                <span class="{{ $invoice->balance > 0 ? 'balance-due' : 'balance-paid' }}">GHS {{ number_format($invoice->balance, 2) }}</span>
            </div>
        </div>

        {{-- Payment History --}}
        @if($invoice->payments->count() > 0)
            <div class="section" style="clear: both; margin-top: 30px;">
                <div class="section-title">Payment History</div>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th style="text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $payment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td>{{ $payment->reference_number }}</td>
                                <td style="text-align: right; color: #16a34a; font-weight: bold;">+ GHS {{ number_format($payment->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="footer" style="{{ $invoice->payments->count() > 0 ? '' : 'margin-top: 80px;' }}">
            This invoice was generated by the COMPSSA Student Finance Management System.<br>
            Printed on {{ now()->format('F j, Y, g:i a') }}
        </div>

    </div>

</body>
</html>
