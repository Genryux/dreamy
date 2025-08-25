<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            ['code' => 'HUMSS', 'name' => 'Humanities And Social Sciences'],
            ['code' => 'ABM', 'name' => 'Accountancy and Business Management'],
            ['code' => 'STEM', 'name' => 'Science, Technology, Engineering, and Mathematics'],
        ];

        foreach ($programs as $program) {
            Program::create([
                'code' => $program['code'],
                'name' => $program['name'],
            ]);
        }
    }
}
