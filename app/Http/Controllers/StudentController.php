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
        $user       = Auth::user();
        $department = Department::findOrFail($departmentId);
        $departments = Department::orderBy('order')->get();
        $clearances  = Clearance::where('user_id', $user->id)->get()->keyBy('department_id');

        // Check if previous department is cleared
        $previousDept = Department::where('order', $department->order - 1)->first();
        if ($previousDept) {
            $prevClearance = $clearances->get($previousDept->id);
            if (!$prevClearance || $prevClearance->status !== 'cleared') {
                return back()->with('error', 'You must complete ' . $previousDept->name . ' first!');
            }
        }

        // Create or update clearance
        Clearance::updateOrCreate(
            ['user_id' => $user->id, 'department_id' => $departmentId],
            ['status' => 'pending', 'requested_at' => now()]
        );

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
}