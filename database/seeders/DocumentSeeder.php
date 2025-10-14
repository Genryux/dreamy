<?php

namespace Database\Seeders;

use App\Models\Documents;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $documents = [
            ['type' => 'Good Moral', 'description' => null, 'file_type_restriction' => ['jpg', 'png', 'pdf'], 'max_file_size' => 10000],
            ['type' => 'PSA/Birth Certificate', 'description' => null, 'file_type_restriction' => ['jpg', 'png', 'pdf'], 'max_file_size' => 10000 ],
            ['type' => '2x2 Picture', 'description' => null, 'file_type_restriction' => ['jpg', 'png', 'pdf'], 'max_file_size' => 10000],
            ['type' => 'Form 138/Report Card', 'description' => null, 'file_type_restriction' => ['jpg', 'png', 'pdf'], 'max_file_size' => 10000],
        ];

        foreach ($documents as $doc) {
            Documents::create([
                'type' => $doc['type'],
                'description' => $doc['description'],
                'file_type_restriction' => $doc['file_type_restriction'],
                'max_file_size' => $doc['max_file_size']
            ]);
        }

    }
}
