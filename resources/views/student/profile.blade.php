@extends('layouts.app')

@section('content')
<style>
    .page-title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
    .page-sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; }
    .card-title { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
    .profile-header { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
    .profile-avatar-lg { width: 80px; height: 80px; border-radius: 50%; background: #E6F1FB; display: flex; align-items: center; justify-content: center; color: #0C447C; font-size: 28px; font-weight: 700; border: 3px solid #B5D4F4; flex-shrink: 0; }
    .profile-title { font-size: 20px; font-weight: 700; color: #1e293b; }
    .profile-reg { font-size: 13px; color: #64748b; margin-top: 4px; }
    .profile-badge { display: inline-block; background: #E6F1FB; color: #0C447C; font-size: 11px; padding: 3px 10px; border-radius: 20px; margin-top: 6px; font-weight: 500; }
    .info-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: 13px; color: #64748b; }
    .info-value { font-size: 13px; font-weight: 600; color: #1e293b; text-align: right; }
</style>

<div class="page-title">My Profile</div>
<div class="page-sub">Your personal and academic information.</div>

<div class="card">
    <div class="profile-header">
        <div class="profile-avatar-lg">JM</div>
        <div>
            <div class="profile-title">John Mwamba</div>
            <div class="profile-reg">Reg No: 2021-04-01234</div>
            <span class="profile-badge">Active Student</span>
        </div>
    </div>

    <div class="card-title">Academic Information</div>
    <div class="info-row">
        <span class="info-label">Full Name</span>
        <span class="info-value">John Mwamba</span>
    </div>
    <div class="info-row">
        <span class="info-label">Registration Number</span>
        <span class="info-value">2021-04-01234</span>
    </div>
    <div class="info-row">
        <span class="info-label">Programme</span>
        <span class="info-value">BSc Computer Science</span>
    </div>
    <div class="info-row">
        <span class="info-label">Year of Study</span>
        <span class="info-value">Year 4</span>
    </div>
    <div class="info-row">
        <span class="info-label">College</span>
        <span class="info-value">College of Informatics & Virtual Education</span>
    </div>
    <div class="info-row">
        <span class="info-label">Academic Year</span>
        <span class="info-value">2024/2025</span>
    </div>
    <div class="info-row">
        <span class="info-label">Student Status</span>
        <span class="info-value" style="color:#16a34a">Active</span>
    </div>
</div>

<div class="card">
    <div class="card-title">Contact Information</div>
    <div class="info-row">
        <span class="info-label">Email Address</span>
        <span class="info-value">j.mwamba@udom.ac.tz</span>
    </div>
    <div class="info-row">
        <span class="info-label">Phone Number</span>
        <span class="info-value">+255 712 345 678</span>
    </div>
</div>

@endsection