<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Clearance;
use App\Models\Department;
use App\Models\Penalty;
use App\Models\ControlNumber;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user        = Auth::user();
        $departments = Department::orderBy('order')->get();
        $clearances  = Clearance::where('user_id', $user->id)->get()->keyBy('department_id');
        $penalties   = Penalty::where('user_id', $user->id)->where('status', 'unpaid')->count();

        $cleared   = $clearances->where('status', 'cleared')->count();
        $pending   = $clearances->where('status', 'pending')->count();
        $notStarted = $departments->count() - $cleared - $pending;
        $progress  = $departments->count() > 0
            ? round(($cleared / $departments->count()) * 100)
            : 0;

        return view('student.dashboard', compact(
            'user', 'departments', 'clearances',
            'cleared', 'pending', 'notStarted',
            'progress', 'penalties'
        ));
    }

    public function clearance()
    {
        $user        = Auth::user();
        $departments = Department::orderBy('order')->get();
        $clearances  = Clearance::where('user_id', $user->id)->get()->keyBy('department_id');
        $penalties   = Penalty::where('user_id', $user->id)->where('status', 'unpaid')->count();

        $cleared  = $clearances->where('status', 'cleared')->count();
        $progress = $departments->count() > 0
            ? round(($cleared / $departments->count()) * 100)
            : 0;

        return view('student.clearance', compact(
            'user', 'departments', 'clearances',
            'cleared', 'progress', 'penalties'
        ));
    }

    public function requestClearance($departmentId)
   {
    $user        = Auth::user();
    $department  = Department::findOrFail($departmentId);
    $departments = Department::orderBy('order')->get();
    $clearances  = Clearance::where('user_id', $user->id)->get()->keyBy('department_id');

    // Check previous department is cleared
    $previousDept = Department::where('order', $department->order - 1)->first();
    if ($previousDept) {
        $prevClearance = $clearances->get($previousDept->id);
        if (!$prevClearance || $prevClearance->status !== 'cleared') {
            return back()->with('error', 'You must complete ' . $previousDept->name . ' clearance first!');
        }
    }

    // Check if already requested or cleared
    $existing = $clearances->get($departmentId);
    if ($existing && in_array($existing->status, ['pending', 'cleared'])) {
        return back()->with('error', 'You have already requested clearance for this department.');
    }

    // ── LIBRARY AUTO-CLEARANCE LOGIC ──────────────────────
    if ($department->slug === 'library') {
        $hasUnreturnedBooks = \App\Models\BorrowedBook::where('user_id', $user->id)
            ->whereIn('status', ['borrowed', 'overdue'])
            ->exists();

        $hasUnpaidPenalties = \App\Models\Penalty::where('user_id', $user->id)
            ->where('department_id', $department->id)
            ->where('status', 'unpaid')
            ->exists();

        if (!$hasUnreturnedBooks && !$hasUnpaidPenalties) {
            // Auto-clear
            Clearance::updateOrCreate(
                ['user_id' => $user->id, 'department_id' => $departmentId],
                [
                    'status'       => 'cleared',
                    'requested_at' => now(),
                    'cleared_at'   => now(),
                    'remarks'      => 'Auto-cleared: No borrowed books or pending library penalties.',
                ]
            );

            \App\Models\StudentNotification::create([
                'user_id' => $user->id,
                'title'   => '✅ Library Clearance Auto-Approved!',
                'message' => 'Your library clearance was automatically approved. You have no borrowed books and no outstanding library penalties.',
                'type'    => 'success',
                'from'    => 'Library Department',
            ]);

            return back()->with('success', '✅ Library clearance automatically approved! You may proceed to the next department.');
        } else {
            // Has issues — send to librarian as pending
            Clearance::updateOrCreate(
                ['user_id' => $user->id, 'department_id' => $departmentId],
                [
                    'status'       => 'pending',
                    'requested_at' => now(),
                ]
            );

            \App\Models\StudentNotification::create([
                'user_id' => $user->id,
                'title'   => '⏳ Library Clearance Pending Review',
                'message' => 'Your library clearance request has been sent to the librarian for review. You have ' .
                    ($hasUnreturnedBooks ? 'unreturned books ' : '') .
                    ($hasUnpaidPenalties ? 'and unpaid penalties ' : '') .
                    'that need to be resolved.',
                'type'    => 'warning',
                'from'    => 'Library Department',
            ]);

            return back()->with('warning', '⚠️ Your request has been sent to the librarian. You have pending issues that need to be resolved before clearance can be approved.');
        }
    }

    // ── DEAN AUTO-CLEARANCE LOGIC ─────────────────────────
if ($department->slug === 'dean') {
    $hasOpenRecords = \App\Models\ConductRecord::where('user_id', $user->id)
        ->where('status', 'open')
        ->exists();

    $hasPenalties = \App\Models\Penalty::where('user_id', $user->id)
        ->where('department_id', $department->id)
        ->where('status', 'unpaid')
        ->exists();

    if (!$hasOpenRecords && !$hasPenalties) {
        Clearance::updateOrCreate(
            ['user_id' => $user->id, 'department_id' => $departmentId],
            [
                'status'       => 'cleared',
                'requested_at' => now(),
                'cleared_at'   => now(),
                'remarks'      => 'Auto-cleared: No open conduct records or pending penalties.',
            ]
        );

        \App\Models\StudentNotification::create([
            'user_id' => $user->id,
            'title'   => '✅ Dean of Students Clearance Auto-Approved!',
            'message' => 'Your clearance from the Dean of Students office was automatically approved. No conduct issues found.',
            'type'    => 'success',
            'from'    => 'Dean of Students Office',
        ]);

        return back()->with('success', '✅ Dean of Students clearance automatically approved!');
    } else {
        Clearance::updateOrCreate(
            ['user_id' => $user->id, 'department_id' => $departmentId],
            ['status' => 'pending', 'requested_at' => now()]
        );

        \App\Models\StudentNotification::create([
            'user_id' => $user->id,
            'title'   => '⏳ Dean of Students Clearance Pending',
            'message' => 'Your clearance request has been sent to the Dean of Students office for review.',
            'type'    => 'warning',
            'from'    => 'Dean of Students Office',
        ]);

        return back()->with('warning', '⚠️ Your request has been sent to the Dean of Students office for review.');
    }
}

  // ── IT AUTO-CLEARANCE LOGIC ───────────────────────
if ($department->slug === 'it') {
    $hasEquipment = \App\Models\ITEquipment::where('user_id', $user->id)
        ->where('status', 'issued')
        ->exists();

    $hasPenalties = \App\Models\Penalty::where('user_id', $user->id)
        ->where('department_id', $department->id)
        ->where('status', 'unpaid')
        ->exists();

    if (!$hasEquipment && !$hasPenalties) {
        Clearance::updateOrCreate(
            ['user_id' => $user->id, 'department_id' => $departmentId],
            [
                'status'       => 'cleared',
                'requested_at' => now(),
                'cleared_at'   => now(),
                'remarks'      => 'Auto-cleared: No unreturned IT equipment or penalties.',
            ]
        );

        \App\Models\StudentNotification::create([
            'user_id' => $user->id,
            'title'   => '✅ IT Department Clearance Auto-Approved!',
            'message' => 'Your IT clearance was automatically approved. No unreturned equipment found.',
            'type'    => 'success',
            'from'    => 'IT Department',
        ]);

        return back()->with('success', '✅ IT clearance automatically approved!');
    } else {
        Clearance::updateOrCreate(
            ['user_id' => $user->id, 'department_id' => $departmentId],
            ['status' => 'pending', 'requested_at' => now()]
        );

        \App\Models\StudentNotification::create([
            'user_id' => $user->id,
            'title'   => '⏳ IT Clearance Pending Review',
            'message' => 'Your IT clearance request has been sent for review. You have pending IT issues.',
            'type'    => 'warning',
            'from'    => 'IT Department',
        ]);

        return back()->with('warning', '⚠️ Your request has been sent to the IT Department for review.');
    }
}

// ── DEFAULT — other departments go pending ─────────────
Clearance::updateOrCreate(
    ['user_id' => $user->id, 'department_id' => $departmentId],
    ['status' => 'pending', 'requested_at' => now()]
);

return back()->with('success', 'Clearance request sent to ' . $department->name . '!');

    return back()->with('success', 'Clearance request sent to ' . $department->name . '!');
    }

    public function controlNumbers()
    {
        $user        = Auth::user();
        $penalties   = Penalty::with('controlNumber', 'department')
                        ->where('user_id', $user->id)
                        ->get();
        $unpaidCount  = $penalties->where('status', 'unpaid')->count();
        $unpaidTotal  = $penalties->where('status', 'unpaid')->sum('amount');
        $paidCount    = $penalties->where('status', 'paid')->count();

        return view('student.control-numbers', compact(
            'user', 'penalties', 'unpaidCount',
            'unpaidTotal', 'paidCount'
        ));
    }

    public function requestControlNumber()
    {
        $user        = Auth::user();
        $departments = Department::all();
        $penalties   = Penalty::where('user_id', $user->id)
                        ->where('status', 'unpaid')
                        ->whereDoesntHave('controlNumber')
                        ->get();

        return view('student.request-control-number', compact(
            'user', 'departments', 'penalties'
        ));
    }

    public function submitControlNumber(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'penalty_id'  => 'required|exists:penalties,id',
        ]);

        ControlNumber::updateOrCreate(
            ['penalty_id' => $request->penalty_id],
            [
                'user_id' => Auth::id(),
                'status'  => 'requested',
            ]
        );

        return back()->with('success', 'Control number request submitted successfully!');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'));
    }

    public function downloadClearance()
  {
    $user        = Auth::user();
    $departments = Department::orderBy('order')->get();
    $clearances  = Clearance::where('user_id', $user->id)->get()->keyBy('department_id');
    $allCleared  = $clearances->where('status', 'cleared')->count() === $departments->count();

    return view('student.clearance-ticket', compact(
        'user', 'departments', 'clearances', 'allCleared'
    ));
   }

    public function financialStatement()
   {
    $user      = Auth::user();
    $penalties = Penalty::with('controlNumber', 'department')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

    $totalPaid    = $penalties->where('status', 'paid')->sum('amount');
    $totalUnpaid  = $penalties->where('status', 'unpaid')->sum('amount');
    $totalAmount  = $penalties->sum('amount');

    return view('student.financial-statement', compact(
        'user', 'penalties',
        'totalPaid', 'totalUnpaid', 'totalAmount'
    ));
    }

    public function markNotifsRead()
{
    \App\Models\StudentNotification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json(['success' => true]);
}

public function allNotifications()
{
    $notifications = \App\Models\StudentNotification::where('user_id', Auth::id())
        ->latest()
        ->paginate(20);

    return view('student.notifications', compact('notifications'));
}
}