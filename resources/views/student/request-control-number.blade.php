@extends('layouts.app')

@section('content')
<style>
    .page-title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
    .page-sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 24px; margin-bottom: 16px; }
    .card-title { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 18px; }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .form-input { width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #1e293b; background: #fff; outline: none; }
    .form-input:focus { border-color: #185FA5; box-shadow: 0 0 0 3px #E6F1FB; }
    select.form-input { cursor: pointer; }
    textarea.form-input { resize: vertical; min-height: 90px; }
    .form-hint { font-size: 11px; color: #94a3b8; margin-top: 4px; }
    .btn-submit { background: #185FA5; color: #fff; border: none; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 8px; }
    .btn-submit:hover { background: #0C447C; }
    .info-box { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #1e40af; }
    .success-box { display: none; background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 16px; text-align: center; margin-bottom: 16px; }
    .success-box .success-icon { font-size: 32px; margin-bottom: 8px; }
    .success-box .success-title { font-size: 16px; font-weight: 700; color: #166534; margin-bottom: 4px; }
    .success-box .success-sub { font-size: 13px; color: #16a34a; }
</style>

<div class="page-title">Request Control Number</div>
<div class="page-sub">Submit a request for a missing or new control number.</div>

<div class="info-box">
    ℹ️ Control numbers are issued by the Bursary. After submitting your request, you will receive your control number within <strong>1-2 working days</strong>.
</div>

{{-- SUCCESS MESSAGE --}}
<div class="success-box" id="successBox">
    <div class="success-icon">✅</div>
    <div class="success-title">Request Submitted Successfully!</div>
    <div class="success-sub">Your control number request has been sent. You will be notified once it is issued.</div>
</div>

{{-- FORM --}}
<div class="card" id="requestForm">
    <div class="card-title">Request Details</div>

    <div class="form-group">
        <label class="form-label">Payment Type</label>
        <select class="form-input" id="paymentType">
            <option value="">Select payment type...</option>
            <option>Library Fine</option>
            <option>Hostel Damage Fee</option>
            <option>Tuition Fee Balance</option>
            <option>Late Registration Fee</option>
            <option>Other Penalty</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label">Amount (TZS)</label>
        <input class="form-input" type="number" id="amount" placeholder="e.g. 15000">
        <div class="form-hint">Enter the exact amount as shown on your fee statement.</div>
    </div>

    <div class="form-group">
        <label class="form-label">Department</label>
        <select class="form-input" id="department">
            <option value="">Select department...</option>
            <option>Library</option>
            <option>Bursary</option>
            <option>Dean of Students</option>
            <option>IT Department</option>
            <option>HoD Office</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label">Reason / Description</label>
        <textarea class="form-input" id="reason" placeholder="Briefly describe why you need this control number..."></textarea>
        <div class="form-hint">Be specific — this helps the Bursary process your request faster.</div>
    </div>

    <button class="btn-submit" onclick="submitRequest()">Submit Request</button>
</div>

<script>
function submitRequest() {
    const type = document.getElementById('paymentType').value;
    const amount = document.getElementById('amount').value;
    const dept = document.getElementById('department').value;
    const reason = document.getElementById('reason').value;

    if(!type || !amount || !dept || !reason) {
        alert('Please fill in all fields before submitting.');
        return;
    }

    document.getElementById('requestForm').style.display = 'none';
    document.getElementById('successBox').style.display = 'block';
}
</script>

@endsection