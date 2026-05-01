@extends('layouts.app')

@section('content')
<style>
    .page-title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
    .page-sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; }
    .card-title { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
    .alert-box { background: #fee2e2; border: 1px solid #fca5a5; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #991b1b; }
    .success-box { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #166534; }
    .summary-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin-bottom: 16px; }
    .summary-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; text-align: center; }
    .summary-num { font-size: 22px; font-weight: 700; }
    .summary-label { font-size: 11px; color: #64748b; margin-top: 3px; }
    .bill-item { border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; margin-bottom: 10px; }
    .bill-item.penalty { border-left: 4px solid #ef4444; }
    .bill-item.outstanding_bill { border-left: 4px solid #f59e0b; }
    .bill-item.paid { border-left: 4px solid #22c55e; opacity: 0.75; }
    .bill-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
    .bill-name { font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 3px; }
    .bill-type { font-size: 11px; padding: 2px 8px; border-radius: 20px; display: inline-block; margin-bottom: 4px; }
    .type-penalty { background: #fee2e2; color: #991b1b; }
    .type-outstanding_bill { background: #fef9c3; color: #92400e; }
    .type-paid { background: #dcfce7; color: #166534; }
    .bill-dept { font-size: 11px; color: #64748b; }
    .bill-amount { font-size: 18px; font-weight: 700; color: #ef4444; }
    .bill-item.paid .bill-amount { color: #16a34a; }
    .bill-bottom { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding-top: 10px; border-top: 1px solid #f1f5f9; }
    .ctrl-num-box { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .ctrl-label { font-size: 11px; color: #64748b; }
    .ctrl-number { font-size: 13px; font-weight: 600; color: #0C447C; background: #E6F1FB; padding: 4px 12px; border-radius: 20px; }
    .ctrl-missing { font-size: 12px; color: #94a3b8; font-style: italic; }
    .btn-copy { background: #f1f5f9; color: #64748b; border: none; padding: 6px 12px; border-radius: 8px; font-size: 12px; cursor: pointer; }
    .btn-request-ctrl { background: #185FA5; color: #fff; border: none; padding: 7px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; }
    .btn-request-ctrl:hover { background: #0C447C; }
</style>

<div class="page-title">Penalties & Outstanding Bills</div>
<div class="page-sub">View all your penalties and bills with their control numbers.</div>

@if(session('success'))
    <div class="success-box">✅ {{ session('success') }}</div>
@endif

@if($unpaidCount > 0)
<div class="alert-box">
    🚨 You have <strong>{{ $unpaidCount }} unpaid bill(s)</strong> totalling
    <strong>TZS {{ number_format($unpaidTotal) }}</strong>.
    Clear all payments to proceed with clearance.
</div>
@endif

{{-- SUMMARY --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-num" style="color:#ef4444">{{ $unpaidCount }}</div>
        <div class="summary-label">Unpaid</div>
    </div>
    <div class="summary-card">
        <div class="summary-num" style="color:#f59e0b">TZS {{ number_format($unpaidTotal) }}</div>
        <div class="summary-label">Total Due</div>
    </div>
    <div class="summary-card">
        <div class="summary-num" style="color:#22c55e">{{ $paidCount }}</div>
        <div class="summary-label">Paid</div>
    </div>
</div>

{{-- BILLS --}}
<div class="card">
    <div class="card-title">All Bills & Penalties</div>

    @forelse($penalties as $penalty)
        @php
            $ctrl = $penalty->controlNumber;
            $isPaid = $penalty->status === 'paid';
        @endphp
        <div class="bill-item {{ $isPaid ? 'paid' : $penalty->type }}">
            <div class="bill-top">
                <div>
                    <div class="bill-name">{{ $penalty->name }}</div>
                    @if($isPaid)
                        <span class="bill-type type-paid">Paid ✓</span>
                    @else
                        <span class="bill-type type-{{ $penalty->type }}">
                            {{ $penalty->type === 'penalty' ? 'Penalty' : 'Outstanding Bill' }}
                        </span>
                    @endif
                    <div class="bill-dept">{{ $penalty->department->name }}</div>
                </div>
                <div class="bill-amount">TZS {{ number_format($penalty->amount) }}</div>
            </div>
            <div class="bill-bottom">
                <div class="ctrl-num-box">
                    <span class="ctrl-label">Control No:</span>
                    @if($ctrl && $ctrl->control_number)
                        <span class="ctrl-number">{{ $ctrl->control_number }}</span>
                        @if(!$isPaid)
                            <button class="btn-copy"
                                onclick="copyCtrl('{{ $ctrl->control_number }}', this)">
                                📋 Copy
                            </button>
                        @endif
                    @elseif($ctrl && $ctrl->status === 'requested')
                        <span class="ctrl-missing">Requested — pending issuance</span>
                    @else
                        <span class="ctrl-missing">Not yet issued</span>
                    @endif
                </div>
                @if(!$isPaid && (!$ctrl || !$ctrl->control_number && $ctrl->status !== 'requested'))
                    <a href="{{ route('request.control') }}" class="btn-request-ctrl">
                        Request Control No.
                    </a>
                @elseif($isPaid)
                    <span style="font-size:11px;color:#16a34a">
                        ✅ Paid on {{ $penalty->paid_at ? $penalty->paid_at->format('d M Y') : 'N/A' }}
                    </span>
                @else
                    @if($penalty->due_date)
                        <span style="font-size:11px;color:#64748b">
                            Expires: {{ $penalty->due_date->format('d M Y') }}
                        </span>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <div style="text-align:center;padding:30px;color:#94a3b8">
            🎉 No penalties or outstanding bills found!
        </div>
    @endforelse
</div>

<script>
function copyCtrl(number, btn) {
    navigator.clipboard.writeText(number);
    btn.textContent = '✅ Copied!';
    setTimeout(() => btn.textContent = '📋 Copy', 2000);
}
</script>

@endsection