<?php

namespace Database\Seeders;

use App\Models\CampusTourSection;
use App\Models\CampusTourItem;
use Illuminate\Database\Seeder;

class CampusTourSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the main section
        $section = CampusTourSection::create([
            'heading' => 'Virtual Campus Tour',
            'description' => 'Explore our modern campus and facilities from the comfort of your home.',
            'is_active' => true,
            'order' => 1,
        ]);

        // Create the tour items
        $items = [
            [
                'title' => 'Main Building',
                'description' => 'Our state-of-the-art main building houses modern classrooms, administrative offices, and student services. Equipped with the latest technology to support innovative learning.',
                'image' => null,
                'icon' => 'fi-rr-marker',
                'highlight' => 'Located at the heart of campus',
                'order' => 1,
            ],
            [
                'title' => 'Learning Resource Center',
                'description' => 'A comprehensive library with over 50,000 books, digital resources, and quiet study areas. Your gateway to knowledge and research excellence.',
                'image' => null,
                'icon' => 'fi-rr-book',
                'highlight' => 'Open 7 days a week',
                'order' => 2,
            ],
            [
                'title' => 'Science Laboratories',
                'description' => 'Fully equipped chemistry, physics, and biology labs for hands-on experiments. Foster scientific inquiry and innovation with cutting-edge equipment.',
                'image' => null,
                'icon' => 'fi-rr-flask',
                'highlight' => '3 specialized labs available',
                'order' => 3,
            ],
            [
                'title' => 'Computer Laboratory',
                'description' => 'High-performance computers with the latest software for programming, design, and digital learning. Fast internet connectivity ensures seamless online resources.',
                'image' => null,
                'icon' => 'fi-rr-computer',
                'highlight' => '100+ workstations',
                'order' => 4,
            ],
            [
                'title' => 'Sports Complex',
                'description' => 'Multi-purpose sports facilities including basketball courts, volleyball courts, and a track and field area. Promoting physical fitness and team spirit.',
                'image' => null,
                'icon' => 'fi-rr-basketball',
                'highlight' => 'Indoor & outdoor facilities',
                'order' => 5,
            ],
            [
                'title' => 'Student Cafeteria',
                'description' => 'Spacious dining area serving nutritious meals and snacks. A vibrant social hub where students gather, share ideas, and build friendships.',
                'image' => null,
                'icon' => 'fi-rr-restaurant',
                'highlight' => 'Healthy meal options daily',
                'order' => 6,
            ],
            [
                'title' => 'Auditorium & Events Hall',
                'description' => 'A modern 500-seat auditorium for assemblies, performances, and special events. Equipped with professional sound and lighting systems.',
                'image' => null,
                'icon' => 'fi-rr-users',
                'highlight' => 'Capacity: 500 students',
                'order' => 7,
            ],
            [
                'title' => 'Art & Music Studio',
                'description' => 'Creative spaces for artistic expression. Students explore painting, sculpture, music, and performing arts under expert guidance.',
                'image' => null,
                'icon' => 'fi-rr-palette',
                'highlight' => 'Nurturing creative talents',
                'order' => 8,
            ],
            [
                'title' => 'School Chapel',
                'description' => 'A peaceful sanctuary for reflection and worship. The chapel hosts regular services, spiritual guidance, and moments of quiet contemplation.',
                'image' => null,
                'icon' => 'fi-rr-cross',
                'highlight' => 'Open for prayer daily',
                'order' => 9,
            ],
            [
                'title' => 'Campus Gardens',
                'description' => 'Beautifully landscaped gardens and green spaces throughout campus. Perfect spots for outdoor classes, study sessions, or simply enjoying nature.',
                'image' => null,
                'icon' => 'fi-rr-leaf',
                'highlight' => 'Eco-friendly campus',
                'order' => 10,
            ],
        ];

        foreach ($items as $item) {
            $section->items()->create($item);
        }
    }
}
