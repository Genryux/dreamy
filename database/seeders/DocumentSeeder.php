<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        \App\Models\Documents::factory()->count(10)->create([
            'type' => 'Passport',
            'description' => 'A valid passport is required for international applicants.',
            'file_type_restriction' => 'pdf',
            'max_file_size' => 2000, // in KB
        ]);

    }
}
