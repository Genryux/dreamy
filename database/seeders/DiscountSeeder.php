<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discounts = [
            // Academic Excellence Discount (Percentage)
            [
                'name' => 'Academic Excellence Scholarship',
                'description' => 'Awarded to students with outstanding academic performance (GPA 95% and above)',
                'discount_type' => 'percentage',
                'discount_value' => 25.00,
                'is_active' => true,
            ],
            
            // Early Enrollment Discount (Percentage)
            [
                'name' => 'Early Enrollment Discount',
                'description' => 'Special discount for students who enroll before the regular enrollment period',
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'is_active' => true,
            ],
            
            // Sibling Discount (Percentage)
            [
                'name' => 'Sibling Discount',
                'description' => 'Family discount for families with multiple children enrolled in the school',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'is_active' => true,
            ],
            
            // Financial Aid (Fixed Amount)
            [
                'name' => 'Financial Aid Grant',
                'description' => 'Fixed amount financial assistance for students from low-income families',
                'discount_type' => 'fixed',
                'discount_value' => 5000.00,
                'is_active' => true,
            ],
            
            // Alumni Discount (Percentage)
            [
                'name' => 'Alumni Family Discount',
                'description' => 'Special discount for families where parents are alumni of the school',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'is_active' => true,
            ],
            
            // STEM Program Incentive (Percentage)
            [
                'name' => 'STEM Program Incentive',
                'description' => 'Encouragement discount for students enrolling in STEM programs',
                'discount_type' => 'percentage',
                'discount_value' => 12.00,
                'is_active' => true,
            ],
            
            // Payment Plan Discount (Fixed Amount)
            [
                'name' => 'Payment Plan Incentive',
                'description' => 'Small discount for students who choose to pay in installments',
                'discount_type' => 'fixed',
                'discount_value' => 1000.00,
                'is_active' => true,
            ],
            
            // Inactive Discount (Example of inactive discount)
            [
                'name' => 'Summer Program Discount',
                'description' => 'Special discount for summer programs (currently inactive)',
                'discount_type' => 'percentage',
                'discount_value' => 30.00,
                'is_active' => false,
            ],
        ];

        foreach ($discounts as $discountData) {
            $discount = Discount::create($discountData);
            
            $status = $discount->is_active ? 'Active' : 'Inactive';
            $value = $discount->getFormattedValue();
            
            $this->command->info("Created discount: {$discount->name} ({$value}) - {$status}");
        }

        $this->command->info('Discount seeder completed successfully!');
        $this->command->info('Created 8 discounts: 6 active, 2 inactive');
        $this->command->info('Discount types: 6 percentage-based, 2 fixed-amount');
    }
}
