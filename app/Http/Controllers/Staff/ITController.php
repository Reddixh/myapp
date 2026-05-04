<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Clearance;
use App\Models\Department;
use App\Models\Penalty;
use App\Models\User;
use App\Models\ITEquipment;
use App\Models\StudentNotification;

class ITController extends Controller
{
    private function getDepartment()
    {
        return Department::where('slug', 'it')->first();
    }

    private function notify($userId, $title, $message, $type = 'info')
    {
        StudentNotification::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'from'    => 'IT Department',
        ]);
    }

    private function checkAutoClearance($userId)
    {
        $dept = $this->getDepartment();

        $hasUnreturnedEquipment = ITEquipment::where('user_id', $userId)
            ->whereIn('status', ['issued'])
            ->exists();

        $hasPenalties = Penalty::where('user_id', $userId)
            ->where('department_id', $dept->id)
            ->where('status', 'unpaid')
            ->exists();

        if (!$hasUnreturnedEquipment && !$hasPenalties) {
            Clearance::where('user_id', $userId)
                ->where('department_id', $dept->id)
                ->update([
                    'status'     => 'cleared',
                    'cleared_at' => now(),
                    'remarks'    => 'Auto-cleared: No unreturned equipment or pending IT penalties.',
                ]);

            $this->notify(
                $userId,
                '✅ IT Department Clearance Auto-Approved!',
                'Your IT clearance has been automatically approved. You have no unreturned equipment and no outstanding IT penalties.',
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

        $totalEquipment    = ITEquipment::count();
        $issuedEquipment   = ITEquipment::where('status', 'issued')->count();
        $returnedEquipment = ITEquipment::where('status', 'returned')->count();
        $lostEquipment     = ITEquipment::where('status', 'lost')->count();

        return view('staff.it.dashboard', compact(
            'pending', 'cleared', 'rejected',
            'totalEquipment', 'issuedEquipment',
            'returnedEquipment', 'lostEquipment'
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
        $equipment = ITEquipment::where('user_id', $userId)
                    ->latest()->get();

        return view('staff.it.student', compact(
            'student', 'clearance', 'penalties', 'equipment', 'dept'
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
                'remarks'    => 'Approved by IT Department.',
            ]);

        $this->notify(
            $userId,
            '✅ IT Department Clearance Approved!',
            'Your IT clearance has been approved. You may proceed to the next department.',
            'success'
        );

        return redirect()->route('it.dashboard')
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
            '❌ IT Department Clearance Rejected',
            'Your IT clearance was rejected. Reason: ' . $request->remarks,
            'danger'
        );

        return redirect()->route('it.dashboard')
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
            '⚠️ New Penalty from IT Department',
            'The IT Department has added a new charge: "' . $request->name . '" of TZS ' . number_format($request->amount) . '.',
            'warning'
        );

        return redirect()->route('it.student', $userId)
            ->with('success', 'Penalty added and student notified!');
    }

    public function addEquipment(Request $request, $userId)
    {
        $request->validate([
            'equipment_name' => 'required|string',
            'serial_number'  => 'nullable|string',
            'category'       => 'required|string',
            'issued_date'    => 'required|date',
            'due_date'       => 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        ITEquipment::create([
            'user_id'        => $userId,
            'equipment_name' => $request->equipment_name,
            'serial_number'  => $request->serial_number,
            'category'       => $request->category,
            'issued_date'    => $request->issued_date,
            'due_date'       => $request->due_date,
            'status'         => 'issued',
            'notes'          => $request->notes,
        ]);

        $this->notify(
            $userId,
            '💻 IT Equipment Issued',
            'The IT Department has issued equipment to you: "' . $request->equipment_name . '". Please return it before your clearance date.',
            'info'
        );

        return redirect()->route('it.student', $userId)
            ->with('success', 'Equipment recorded successfully!');
    }

    public function returnEquipment(Request $request, $equipmentId)
    {
        $equipment = ITEquipment::findOrFail($equipmentId);
        $equipment->update([
            'status'      => 'returned',
            'return_date' => now(),
        ]);

        $this->notify(
            $equipment->user_id,
            '💻 Equipment Returned Successfully',
            'Your equipment "' . $equipment->equipment_name . '" has been marked as returned. Thank you!',
            'success'
        );

        $autocleared = $this->checkAutoClearance($equipment->user_id);

        $msg = $autocleared
            ? 'Equipment returned and student auto-cleared!'
            : 'Equipment marked as returned.';

        return redirect()->route('it.student', $equipment->user_id)
            ->with('success', $msg);
    }

    public function markLost(Request $request, $equipmentId)
    {
        $equipment = ITEquipment::findOrFail($equipmentId);
        $equipment->update(['status' => 'lost']);

        $this->notify(
            $equipment->user_id,
            '⚠️ Equipment Marked as Lost',
            'Your equipment "' . $equipment->equipment_name . '" has been marked as lost. A penalty may be added to your account.',
            'warning'
        );

        return redirect()->route('it.student', $equipment->user_id)
            ->with('success', 'Equipment marked as lost.');
    }

    public function allEquipment()
    {
        $equipment = ITEquipment::with('user')
            ->orderByRaw("CASE status WHEN 'issued' THEN 0 WHEN 'lost' THEN 1 ELSE 2 END")
            ->latest()->get();

        return view('staff.it.equipment', compact('equipment'));
    }
}