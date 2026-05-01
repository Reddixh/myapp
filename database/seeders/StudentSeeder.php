<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Create student
        $userId = DB::table('users')->insertGetId([
            'name'        => 'John Mwamba',
            'reg_number'  => '2021-04-01234',
            'email'       => 'j.mwamba@udom.ac.tz',
            'password'    => Hash::make('password123'),
            'programme'   => 'BSc Computer Science',
            'year'        => '4',
            'college'     => 'College of Informatics & Virtual Education',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Dean clearance - cleared
        DB::table('clearances')->insert([
            'user_id'       => $userId,
            'department_id' => 1,
            'status'        => 'cleared',
            'requested_at'  => now()->subDays(5),
            'cleared_at'    => now()->subDays(2),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Library clearance - pending
        DB::table('clearances')->insert([
            'user_id'       => $userId,
            'department_id' => 2,
            'status'        => 'pending',
            'requested_at'  => now()->subDay(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Penalties
        DB::table('penalties')->insert([
            'user_id'       => $userId,
            'department_id' => 2,
            'name'          => 'Library Fine',
            'type'          => 'penalty',
            'amount'        => 15000,
            'status'        => 'unpaid',
            'due_date'      => now()->addDays(10),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        DB::table('penalties')->insert([
            'user_id'       => $userId,
            'department_id' => 1,
            'name'          => 'Hostel Damage Fee',
            'type'          => 'outstanding_bill',
            'amount'        => 50000,
            'status'        => 'unpaid',
            'due_date'      => now()->addDays(12),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Control number for library fine
        DB::table('control_numbers')->insert([
            'user_id'        => $userId,
            'penalty_id'     => 1,
            'control_number' => '981234567890',
            'status'         => 'issued',
            'expires_at'     => now()->addDays(10),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}