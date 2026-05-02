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
use App\Models\BorrowedBook;
use App\Models\StudentNotification;

class LibrarianController extends Controller
{
    private function getDepartment()
    {
        return Department::where('slug', 'library')->first();
    }

    private function notify($userId, $title, $message, $type = 'info')
    {
        StudentNotification::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'from'    => 'Library Department',
        ]);
    }

    private function checkAutoClearance($userId)
    {
        $dept = $this->getDepartment();

        $hasBooks    = BorrowedBook::where('user_id', $userId)
                        ->where('status', 'borrowed')
                        ->exists();
        $hasPenalties = Penalty::where('user_id', $userId)
                        ->where('department_id', $dept->id)
                        ->where('status', 'unpaid')
                        ->exists();

        if (!$hasBooks && !$hasPenalties) {
            Clearance::where('user_id', $userId)
                ->where('department_id', $dept->id)
                ->update([
                    'status'     => 'cleared',
                    'cleared_at' => now(),
                    'remarks'    => 'Auto-cleared by system — no borrowed books or pending penalties.',
                ]);

            $this->notify(
                $userId,
                '✅ Library Clearance Auto-Approved!',
                'Your library clearance has been automatically approved because you have no borrowed books and no outstanding penalties.',
                'success'
            );

            return true;
        }

        return false;
    }

    public function dashboard()
    {
        $dept = $this->getDepartment();

        // Update overdue books
        BorrowedBook::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

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

        $totalBooks    = BorrowedBook::count();
        $overdueBooks  = BorrowedBook::where('status', 'overdue')->count();
        $returnedBooks = BorrowedBook::where('status', 'returned')->count();

        return view('staff.librarian.dashboard', compact(
            'pending', 'cleared', 'rejected',
            'totalBooks', 'overdueBooks', 'returnedBooks'
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
        $books     = BorrowedBook::where('user_id', $userId)
                    ->orderBy('status')
                    ->latest()
                    ->get();

        return view('staff.librarian.student', compact(
            'student', 'clearance', 'penalties', 'books', 'dept'
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
                'remarks'    => 'Approved by librarian.',
            ]);

        $this->notify(
            $userId,
            '✅ Library Clearance Approved!',
            'Your library clearance request has been approved by the librarian. You may proceed to the next department.',
            'success'
        );

        return redirect()->route('staff.dashboard')
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
            '❌ Library Clearance Rejected',
            'Your library clearance request was rejected. Reason: ' . $request->remarks . '. Please visit the library to resolve this issue.',
            'danger'
        );

        return redirect()->route('staff.dashboard')
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
            '⚠️ New Penalty Added by Library',
            'The library has added a new penalty: ' . $request->name . ' of TZS ' . number_format($request->amount) . '. Please visit the library or pay using the control number provided.',
            'warning'
        );

        return redirect()->route('staff.student', $userId)
            ->with('success', 'Penalty added and student notified!');
    }

    public function addBook(Request $request, $userId)
    {
        $request->validate([
            'book_title'  => 'required|string',
            'author'      => 'required|string',
            'isbn'        => 'nullable|string',
            'borrow_date' => 'required|date',
            'due_date'    => 'required|date|after:borrow_date',
        ]);

        BorrowedBook::create([
            'user_id'     => $userId,
            'book_title'  => $request->book_title,
            'author'      => $request->author,
            'isbn'        => $request->isbn,
            'borrow_date' => $request->borrow_date,
            'due_date'    => $request->due_date,
            'status'      => 'borrowed',
        ]);

        $this->notify(
            $userId,
            '📚 Book Borrowing Recorded',
            'A book has been recorded under your library account: "' . $request->book_title . '" by ' . $request->author . '. Due date: ' . $request->due_date,
            'info'
        );

        return redirect()->route('staff.student', $userId)
            ->with('success', 'Book added successfully!');
    }

    public function returnBook(Request $request, $bookId)
    {
        $book = BorrowedBook::findOrFail($bookId);
        $book->update([
            'status'      => 'returned',
            'return_date' => now(),
        ]);

        $this->notify(
            $book->user_id,
            '📗 Book Returned Successfully',
            'Your book "' . $book->book_title . '" has been marked as returned. Thank you!',
            'success'
        );

        // Check auto clearance
        $autocleared = $this->checkAutoClearance($book->user_id);

        $msg = $autocleared
            ? 'Book returned and student auto-cleared!'
            : 'Book marked as returned.';

        return redirect()->route('staff.student', $book->user_id)
            ->with('success', $msg);
    }

    public function issueControlNumber(Request $request, $penaltyId)
    {
        $request->validate([
            'control_number' => 'required|string|unique:control_numbers,control_number',
            'expires_at'     => 'nullable|date',
        ]);

        $penalty = Penalty::findOrFail($penaltyId);

        ControlNumber::updateOrCreate(
            ['penalty_id' => $penaltyId],
            [
                'user_id'        => $penalty->user_id,
                'control_number' => $request->control_number,
                'status'         => 'issued',
                'expires_at'     => $request->expires_at,
            ]
        );

        $this->notify(
            $penalty->user_id,
            '💳 Control Number Issued',
            'A control number has been issued for "' . $penalty->name . '": ' . $request->control_number . '. Please use this to make your payment before the expiry date.',
            'info'
        );

        return back()->with('success', 'Control number issued and student notified!');
    }

    public function allBooks()
    {
        BorrowedBook::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

        $books = BorrowedBook::with('user')
                    ->orderByRaw("CASE status WHEN 'overdue' THEN 0 WHEN 'borrowed' THEN 1 ELSE 2 END")
                    ->latest()
                    ->get();

        return view('staff.librarian.books', compact('books'));
    }
}