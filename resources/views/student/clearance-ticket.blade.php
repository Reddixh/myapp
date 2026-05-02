<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clearance Ticket — {{ $user->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f4f8; padding: 20px; }
        .print-btn { background: #185FA5; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; margin-bottom: 20px; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: #185FA5; text-decoration: none; font-size: 13px; font-weight: 600; margin-right: 12px; }
        .ticket {
            background: #fff;
            max-width: 750px;
            margin: 0 auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }
        .ticket-header {
            background: linear-gradient(135deg, #0C447C, #185FA5);
            padding: 28px 32px;
            text-align: center;
            color: #fff;
        }
        .ticket-logo { font-size: 40px; margin-bottom: 8px; }
        .ticket-uni { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .ticket-sub { font-size: 13px; color: #B5D4F4; }
        .ticket-title {
            background: #f8fafc;
            border-bottom: 2px solid #185FA5;
            padding: 16px 32px;
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            color: #185FA5;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .ticket-body { padding: 28px 32px; }
        .student-info { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px; padding: 16px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; }
        .info-item { }
        .info-label { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
        .info-value { font-size: 14px; font-weight: 600; color: #1e293b; }
        .dept-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .dept-table th { background: #f1f5f9; padding: 10px 14px; font-size: 12px; color: #64748b; text-align: left; border: 1px solid #e2e8f0; }
        .dept-table td { padding: 10px 14px; font-size: 13px; border: 1px solid #e2e8f0; }
        .status-cleared { color: #16a34a; font-weight: 600; }
        .status-pending { color: #ca8a04; font-weight: 600; }
        .status-ns { color: #94a3b8; }
        .sig-section { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 24px; }
        .sig-box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; text-align: center; }
        .sig-line { border-top: 1px solid #1e293b; margin: 30px 16px 8px; }
        .sig-label { font-size: 12px; color: #64748b; }
        .sig-title { font-size: 11px; color: #94a3b8; margin-top: 3px; }
        .ticket-footer { background: #f8fafc; padding: 16px 32px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 11px; color: #94a3b8; }
        .not-cleared-banner { background: #fee2e2; border: 1px solid #fca5a5; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; text-align: center; font-size: 14px; color: #991b1b; font-weight: 600; }
        .cleared-banner { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; text-align: center; font-size: 14px; color: #166534; font-weight: 600; }
        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none; }
            .ticket { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

<div class="no-print" style="max-width:750px;margin:0 auto 16px">
    <a href="{{ route('clearance') }}" class="back-link">← Back to Clearance</a>
    <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>
</div>

@if(!$allCleared)
    <div class="not-cleared-banner" style="max-width:750px;margin:0 auto 16px">
        ⚠️ Your clearance is not yet complete. This ticket will be valid once all departments are cleared.
    </div>
@else
    <div class="cleared-banner" style="max-width:750px;margin:0 auto 16px">
        ✅ All departments cleared! Your clearance ticket is ready.
    </div>
@endif

<div class="ticket">
    {{-- HEADER --}}
    <div class="ticket-header">
        <div class="ticket-logo">🎓</div>
        <div class="ticket-uni">University of Dodoma</div>
        <div class="ticket-sub">Dodoma, Tanzania | www.udom.ac.tz</div>
    </div>

    <div class="ticket-title">Official Student Clearance Certificate</div>

    <div class="ticket-body">
        {{-- STUDENT INFO --}}
        <div class="student-info">
            <div class="info-item">
                <div class="info-label">Full Name</div>
                <div class="info-value">{{ $user->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Registration Number</div>
                <div class="info-value">{{ $user->reg_number }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Programme</div>
                <div class="info-value">{{ $user->programme }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Year of Study</div>
                <div class="info-value">Year {{ $user->year }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">College</div>
                <div class="info-value">{{ $user->college }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Academic Year</div>
                <div class="info-value">2024/2025</div>
            </div>
        </div>

        {{-- DEPARTMENT STATUS TABLE --}}
        <table class="dept-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date Cleared</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $dept)
                    @php
                        $clearance = $clearances->get($dept->id);
                        $status    = $clearance ? $clearance->status : 'not_started';
                    @endphp
                    <tr>
                        <td>{{ $dept->order }}</td>
                        <td>{{ $dept->name }}</td>
                        <td>
                            @if($status === 'cleared')
                                <span class="status-cleared">✅ Cleared</span>
                            @elseif($status === 'pending')
                                <span class="status-pending">⏳ Pending</span>
                            @else
                                <span class="status-ns">— Not Started</span>
                            @endif
                        </td>
                        <td>
                            @if($status === 'cleared' && $clearance->cleared_at)
                                {{ $clearance->cleared_at->format('d M Y') }}
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $clearance ? $clearance->remarks : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- SIGNATURE SECTION --}}
        <div class="sig-section">
            <div class="sig-box">
                <div style="font-size:12px;color:#94a3b8;margin-bottom:24px">
                    @if($allCleared) ✅ Digitally signed @else 🔒 Pending signature @endif
                </div>
                <div class="sig-line"></div>
                <div class="sig-label" style="font-weight:700">Bursary Officer</div>
                <div class="sig-title">University of Dodoma</div>
            </div>
            <div class="sig-box">
                <div style="font-size:12px;color:#94a3b8;margin-bottom:24px">
                    @if($allCleared) ✅ Digitally approved @else 🔒 Pending approval @endif
                </div>
                <div class="sig-line"></div>
                <div class="sig-label" style="font-weight:700">Head of Department</div>
                <div class="sig-title">{{ $user->college }}</div>
            </div>
        </div>

        <div style="margin-top:20px;padding:14px;background:#f8fafc;border-radius:8px;font-size:12px;color:#64748b;text-align:center">
            This document was generated on {{ now()->format('d M Y, h:i A') }} by the UDOM Student Clearance System.
            @if($allCleared)
                This certificate is valid and officially signed.
            @else
                This certificate is <strong>NOT YET VALID</strong> — clearance is incomplete.
            @endif
        </div>
    </div>

    <div class="ticket-footer">
        University of Dodoma | P.O. Box 259, Dodoma, Tanzania | Tel: +255 26 296 1000 | www.udom.ac.tz
    </div>
</div>

</body>
</html>