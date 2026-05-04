<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Clearance;
use App\Models\Department;
use App\Models\Penalty;
use App\Models\User;
use App\Models\ConductRecord;
use App\Models\StudentNotification;

class DeanController extends Controller
{
    private function getDepartment()
    {
        return Department::where('slug', 'dean')->first();
    }

    private function notify($userId, $title, $message, $type = 'info')
    {
        StudentNotification::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'from'    => 'Dean of Students Office',
        ]);
    }

    private function checkAutoClearance($userId)
    {
        $dept = $this->getDepartment();

        $hasOpenRecords = ConductRecord::where('user_id', $userId)
            ->where('status', 'open')
            ->exists();

        $hasPenalties = Penalty::where('user_id', $userId)
            ->where('department_id', $dept->id)
            ->where('status', 'unpaid')
            ->exists();

        if (!$hasOpenRecords && !$hasPenalties) {
            Clearance::where('user_id', $userId)
                ->where('department_id', $dept->id)
                ->update([
                    'status'     => 'cleared',
                    'cleared_at' => now(),
                    'remarks'    => 'Auto-cleared: No open conduct records or pending penalties.',
                ]);

            $this->notify(
                $userId,
                '✅ Dean of Students Clearance Auto-Approved!',
                'Your clearance from the Dean of Students office has been automatically approved. You have no open conduct records or pending penalties.',
                'success'
            );

            return true;
        }

        return false;
    }

    public function dashboard()
    {
        $dept = $this->getDepartment();

        $pending  = Clearance::with('user')
                    ->where('department_id', $dept->id)
                    ->where('status', 'pending')
                    ->latest()->get();
        $cleared  = Clearance::with('user')
                    ->where('department_id', $dept->id)
                    ->where('status', 'cleared')
                    ->latest()->get();
        $rejected = Clearance::with('user')
                    ->where('department_id', $dept->id)
                    ->where('status', 'rejected')
                    ->latest()->get();

        $totalStudents   = User::where('role', 'student')->count();
        $openRecords     = ConductRecord::where('status', 'open')->count();
        $resolvedRecords = ConductRecord::where('status', 'resolved')->count();

        return view('staff.dean.dashboard', compact(
            'pending', 'cleared', 'rejected',
            'totalStudents', 'openRecords', 'resolvedRecords'
        ));
    }

    public function showStudent($userId)
    {
        $dept         = $this->getDepartment();
        $student      = User::findOrFail($userId);
        $clearance    = Clearance::where('user_id', $userId)
                        ->where('department_id', $dept->id)
                        ->first();
        $penalties    = Penalty::with('controlNumber')
                        ->where('user_id', $userId)
                        ->where('department_id', $dept->id)
                        ->get();
        $conductRecords = ConductRecord::where('user_id', $userId)
                        ->latest()->get();

        return view('staff.dean.student', compact(
            'student', 'clearance', 'penalties',
            'conductRecords', 'dept'
        ));
    }

    public function approve($userId)
    {
        $dept = $this->getDepartment();
        Clearance::where('user_id', $userId)
            ->where('department_id', $dept->id)
            ->update([
                'status'     => 'cleared',
                'cleared_at' => now(),
                'remarks'    => 'Approved by Dean of Students.',
            ]);

        $this->notify(
            $userId,
            '✅ Dean of Students Clearance Approved!',
            'Your clearance from the Dean of Students office has been approved. You may proceed to the next department.',
            'success'
        );

        return redirect()->route('dean.dashboard')
            ->with('success', 'Student clearance approved!');
    }

    public function reject(Request $request, $userId)
    {
        $request->validate(['remarks' => 'required|string']);
        $dept = $this->getDepartment();

        Clearance::where('user_id', $userId)
            ->where('department_id', $dept->id)
            ->update([
                'status'  => 'rejected',
                'remarks' => $request->remarks,
            ]);

        $this->notify(
            $userId,
            '❌ Dean of Students Clearance Rejected',
            'Your clearance request was rejected by the Dean of Students office. Reason: ' . $request->remarks,
            'danger'
        );

        return redirect()->route('dean.dashboard')
            ->with('success', 'Student clearance rejected.');
    }

    public function addPenalty(Request $request, $userId)
    {
        $request->validate([
            'name'     => 'required|string',
            'type'     => 'required|in:penalty,outstanding_bill',
            'amount'   => 'required|numeric|min:1',
            'due_date' => 'nullable|date',
        ]);

        $dept = $this->getDepartment();
        Penalty::create([
            'user_id'       => $userId,
            'department_id' => $dept->id,
            'name'          => $request->name,
            'type'          => $request->type,
            'amount'        => $request->amount,
            'status'        => 'unpaid',
            'due_date'      => $request->due_date,
        ]);

        $this->notify(
            $userId,
            '⚠️ New Penalty from Dean of Students',
            'The Dean of Students office has added a new charge: "' . $request->name . '" of TZS ' . number_format($request->amount) . '.',
            'warning'
        );

        return redirect()->route('dean.student', $userId)
            ->with('success', 'Penalty added and student notified!');
    }

    public function addConductRecord(Request $request, $userId)
    {
        $request->validate([
            'title'         => 'required|string',
            'description'   => 'required|string',
            'type'          => 'required|in:misconduct,academic,financial,other',
            'severity'      => 'required|in:low,medium,high',
            'incident_date' => 'required|date',
        ]);

        ConductRecord::create([
            'user_id'       => $userId,
            'title'         => $request->title,
            'description'   => $request->description,
            'type'          => $request->type,
            'severity'      => $request->severity,
            'status'        => 'open',
            'incident_date' => $request->incident_date,
        ]);

        $this->notify(
            $userId,
            '⚠️ Conduct Record Added',
            'A new conduct record has been added to your file by the Dean of Students office: "' . $request->title . '". Please visit the office for more information.',
            'warning'
        );

        return redirect()->route('dean.student', $userId)
            ->with('success', 'Conduct record added and student notified!');
    }

    public function resolveRecord(Request $request, $recordId)
    {
        $record = ConductRecord::findOrFail($recordId);
        $record->update([
            'status'       => 'resolved',
            'resolved_date' => now(),
        ]);

        $autocleared = $this->checkAutoClearance($record->user_id);

        $this->notify(
            $record->user_id,
            '✅ Conduct Record Resolved',
            'Your conduct record "' . $record->title . '" has been marked as resolved by the Dean of Students office.',
            'success'
        );

        $msg = $autocleared
            ? 'Record resolved and student auto-cleared!'
            : 'Conduct record marked as resolved.';

        return redirect()->route('dean.student', $record->user_id)
            ->with('success', $msg);
    }

    public function allRecords()
    {
        $records = ConductRecord::with('user')
            ->orderByRaw("CASE status WHEN 'open' THEN 0 ELSE 1 END")
            ->latest()->get();

        return view('staff.dean.records', compact('records'));
    }
}