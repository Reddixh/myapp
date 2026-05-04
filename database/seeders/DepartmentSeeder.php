<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Library',            'slug' => 'library', 'description' => 'Return all borrowed books and clear library fines',     'order' => 1],
            ['name' => 'Dean of Students',   'slug' => 'dean',    'description' => 'General student conduct and academic standing',          'order' => 2],
            ['name' => 'IT Department',      'slug' => 'it',      'description' => 'Return all IT equipment and clear IT accounts',          'order' => 3],
            ['name' => 'Bursary',            'slug' => 'bursary', 'description' => 'Clear all outstanding tuition fees and penalties',       'order' => 4],
            ['name' => 'Head of Department', 'slug' => 'hod',     'description' => 'Final academic clearance from your Head of Department',  'order' => 5],
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->insert([
                'name'        => $dept['name'],
                'slug'        => $dept['slug'],
                'description' => $dept['description'],
                'order'       => $dept['order'],
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}