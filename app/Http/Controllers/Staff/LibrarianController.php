<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Clearance;
use App\Models\Department;
use App\Models\Penalty;
use App\Models\User;
use App\Models\ControlNumber;

class LibrarianController extends Controller
{
    private function getDepartment()
    {
        return Department::where('slug', 'library')->first();
    }

    public function dashboard()
    {
        $dept     = $this->getDepartment();
        $pending  = Clearance::with('user')
                    ->where('department_id', $dept->id)
                    ->where('status', 'pending')
                    ->latest()
                    ->get();
        $cleared  = Clearance::with('user')
                    ->where('department_id', $dept->id)
                    ->where('status', 'cleared')
                    ->latest()
                    ->get();
        $rejected = Clearance::with('user')
                    ->where('department_id', $dept->id)
                    ->where('status', 'rejected')
                    ->latest()
                    ->get();

        $pendingCount  = $pending->count();
        $clearedCount  = $cleared->count();
        $rejectedCount = $rejected->count();

        return view('staff.librarian.dashboard', compact(
            'pending', 'cleared', 'rejected',
            'pendingCount', 'clearedCount', 'rejectedCount'
        ));
    }

    public function showStudent($userId)
    {
        $dept      = $this->getDepartment();
        $student   = User::findOrFail($userId);
        $clearance = Clearance::where('user_id', $userId)
                    ->where('department_id', $dept->id)
                    ->first();
        $penalties = Penalty::with('controlNumber')
                    ->where('user_id', $userId)
                    ->where('department_id', $dept->id)
                    ->get();

        return view('staff.librarian.student', compact(
            'student', 'clearance', 'penalties', 'dept'
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
                'remarks'    => 'Approved by librarian',
            ]);

        return redirect('/staff/dashboard')
            ->with('success', 'Student clearance approved successfully!');
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

        return redirect('/staff/dashboard')
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

        return redirect("/staff/student/{$userId}")
            ->with('success', 'Penalty added successfully!');
    }

    public function issueControlNumber(Request $request, $penaltyId)
    {
        $request->validate([
            'control_number' => 'required|string|unique:control_numbers,control_number',
            'expires_at'     => 'nullable|date',
        ]);

        ControlNumber::updateOrCreate(
            ['penalty_id' => $penaltyId],
            [
                'user_id'        => Penalty::find($penaltyId)->user_id,
                'control_number' => $request->control_number,
                'status'         => 'issued',
                'expires_at'     => $request->expires_at,
            ]
        );

        return back()->with('success', 'Control number issued successfully!');
    }
}