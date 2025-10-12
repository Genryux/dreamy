<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\Track;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $tracks = [
            ['name' => 'Academic Track', 'code' => null, 'description' => null, 'status' => 'active'],
            ['name' => 'Tech-Voc Track', 'code' => null, 'description' => null, 'status' => 'inactive'],
            ['name' => 'Sports Track', 'code' => null, 'description' => null, 'status' => 'inactive'],
        ];

        foreach ($tracks as $track) {
            Track::create(
                [
                    'name' => $track['name'],
                    'code' => $track['code'],
                    'description' => $track['description'],
                    'status' => $track['status']
                ]
                );
        }

        $programs = [
            ['track_id' => 1,'code' => 'HUMSS', 'name' => 'Humanities And Social Sciences'],
            ['track_id' => 1,'code' => 'ABM', 'name' => 'Accountancy and Business Management'],
            ['track_id' => 1,'code' => 'STEM', 'name' => 'Science, Technology, Engineering, and Mathematics'],
        ];

        foreach ($programs as $program) {
            Program::create([
                'track_id' => $program['track_id'],
                'code' => $program['code'],
                'name' => $program['name'],
            ]);
        }
    }
}
