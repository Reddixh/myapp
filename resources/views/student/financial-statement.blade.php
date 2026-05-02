@extends('layouts.app')

@section('content')
<style>
    .page-title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
    .page-sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; }
    .card-title { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
    .summary-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin-bottom: 16px; }
    .summary-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; text-align: center; }
    .summary-num { font-size: 20px; font-weight: 700; }
    .summary-label { font-size: 11px; color: #64748b; margin-top: 3px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f1f5f9; padding: 10px 14px; font-size: 11px; color: #64748b; text-align: left; border: 1px solid #e2e8f0; text-transform: uppercase; }
    td { padding: 12px 14px; font-size: 13px; border: 1px solid #e2e8f0; color: #1e293b; }
    tr:nth-child(even) td { background: #f8fafc; }
    .type-penalty { background: #fee2e2; color: #991b1b; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
    .type-outstanding_bill { background: #fef9c3; color: #92400e; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
    .status-paid { color: #16a34a; font-weight: 600; }
    .status-unpaid { color: #ef4444; font-weight: 600; }
    .back-link { display: inline-flex; align-items: center; gap: 6px; color: #185FA5; text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
    .print-btn { background: #185FA5; color: #fff; border: none; padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; margin-bottom: 16px; margin-left: 10px; }
    @media print { .no-print { display: none; } }
</style>

<div class="no-print" style="display:flex;align-items:center">
    <a href="{{ route('clearance') }}" class="back-link">← Back to Clearance</a>
    <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>
</div>

<div class="page-title">Financial Statement</div>
<div class="page-sub">Full record of all your financial transactions with the university.</div>

{{-- SUMMARY --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-num" style="color:#1e293b">TZS {{ number_format($totalAmount) }}</div>
        <div class="summary-label">Total Billed</div>
    </div>
    <div class="summary-card">
        <div class="summary-num" style="color:#16a34a">TZS {{ number_format($totalPaid) }}</div>
        <div class="summary-label">Total Paid</div>
    </div>
    <div class="summary-card">
        <div class="summary-num" style="color:#ef4444">TZS {{ number_format($totalUnpaid) }}</div>
        <div class="summary-label">Outstanding Balance</div>
    </div>
</div>

{{-- STATEMENT TABLE --}}
<div class="card">
    <div class="card-title">All Transactions</div>
    @if($penalties->count() > 0)
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Type</th>
                <th>Department</th>
                <th>Amount (TZS)</th>
                <th>Control Number</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penalties as $index => $penalty)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="font-weight:600">{{ $penalty->name }}</td>
                <td>
                    <span class="type-{{ $penalty->type }}">
                        {{ $penalty->type === 'penalty' ? 'Penalty' : 'Outstanding Bill' }}
                    </span>
                </td>
                <td>{{ $penalty->department->name }}</td>
                <td style="font-weight:700">{{ number_format($penalty->amount) }}</td>
                <td>
                    @if($penalty->controlNumber && $penalty->controlNumber->control_number)
                        <span style="font-size:12px;color:#0C447C;background:#E6F1FB;padding:2px 8px;border-radius:20px">
                            {{ $penalty->controlNumber->control_number }}
                        </span>
                    @else
                        <span style="color:#94a3b8;font-size:12px">Not issued</span>
                    @endif
                </td>
                <td>{{ $penalty->due_date ? $penalty->due_date->format('d M Y') : '—' }}</td>
                <td>
                    @if($penalty->status === 'paid')
                        <span class="status-paid">✅ Paid</span>
                    @else
                        <span class="status-unpaid">⏳ Unpaid</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="font-weight:700;text-align:right;background:#f8fafc">Total Outstanding:</td>
                <td style="font-weight:700;color:#ef4444;background:#f8fafc">TZS {{ number_format($totalUnpaid) }}</td>
                <td colspan="3" style="background:#f8fafc"></td>
            </tr>
        </tfoot>
    </table>
    @else
        <div style="text-align:center;padding:40px;color:#94a3b8">
            🎉 No financial records found!
        </div>
    @endif
</div>

<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px 16px;font-size:12px;color:#64748b;text-align:center">
    Statement generated on {{ now()->format('d M Y, h:i A') }} | UDOM Student Clearance System
</div>

@endsection