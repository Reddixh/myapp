<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // ── Create student ──────────────────────────────────
        $studentId = DB::table('users')->insertGetId([
            'name'          => 'Brown Albetho',
            'reg_number'    => 'T22-03-08160',
            'email'         => 'brownreddishie26@gmail.com',
            'password'      => Hash::make('password123'),
            'programme'     => 'Bachelor of Science in Software Engineering',
            'year'          => '3',
            'college'       => 'Department of Computer Science',
            'role'          => 'student',
            'department_id' => null,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // ── Create librarian ────────────────────────────────
        DB::table('users')->insert([
            'name'          => 'Mary Kileo',
            'reg_number'    => 'STAFF-LIB-001',
            'email'         => 'm.kileo@brown.ac.tz',
            'password'      => Hash::make('library123'),
            'programme'     => null,
            'year'          => null,
            'college'       => null,
            'role'          => 'librarian',
            'department_id' => 1,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // ── Add a borrowed book (unreturned) ────────────────
        DB::table('borrowed_books')->insert([
            'user_id'     => $studentId,
            'book_title'  => 'Introduction to Software Engineering',
            'author'      => 'Ian Sommerville',
            'isbn'        => '978-0-13-394303-0',
            'borrow_date' => now()->subDays(20),
            'due_date'    => now()->subDays(6),
            'status'      => 'overdue',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // ── Add a library penalty ───────────────────────────
        DB::table('penalties')->insert([
            'user_id'       => $studentId,
            'department_id' => 1,
            'name'          => 'Overdue Book Fine',
            'type'          => 'penalty',
            'amount'        => 15000,
            'status'        => 'unpaid',
            'due_date'      => now()->addDays(7),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}