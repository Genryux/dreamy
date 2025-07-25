<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicationForm>
 */
class ApplicationFormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Enrollment Info
            'preferred_sched' => fake()->randomElement(['AM', 'PM']),
            'is_returning' => fake()->boolean,
            'lrn' => fake()->numerify('###########'),
            'grade_level' => fake()->randomElement(['Grade 7', 'Grade 8', 'Grade 9']),
            'primary_track' => fake()->randomElement(['Academic', 'Technical-Vocational']),
            'secondary_track' => fake()->randomElement(['STEM', 'TVL', 'ABM']),

            // Personal Info
            'last_name' => fake()->lastName,
            'first_name' => fake()->firstName,
            'middle_name' => fake()->lastName,
            'extension_name' => fake()->randomElement([null, 'Jr.', 'Sr.', 'III']),
            'birthdate' => fake()->date(),
            'age' => fake()->numberBetween(12, 20),
            'place_of_birth' => fake()->city,
            'mother_tongue' => fake()->languageCode,
            'belongs_to_ip' => fake()->boolean,
            'is_4ps_beneficiary' => fake()->boolean,

            // Current Address
            'cur_house_no' => fake()->buildingNumber,
            'cur_street' => fake()->streetName,
            'cur_barangay' => fake()->word,
            'cur_city' => fake()->city,
            'cur_province' => fake()->state,
            'cur_country' => fake()->country,
            'cur_zip_code' => fake()->postcode,

            // Permanent Address
            'perm_house_no' => fake()->buildingNumber,
            'perm_street' => fake()->streetName,
            'perm_barangay' => fake()->word,
            'perm_city' => fake()->city,
            'perm_province' => fake()->state,
            'perm_country' => fake()->country,
            'perm_zip_code' => fake()->postcode,

            // Parents
            'father_last_name' => fake()->lastName,
            'father_first_name' => fake()->firstNameMale,
            'father_middle_name' => fake()->lastName,
            'father_contact_number' => fake()->phoneNumber,
            'mother_last_name' => fake()->lastName,
            'mother_first_name' => fake()->firstNameFemale,
            'mother_middle_name' => fake()->lastName,
            'mother_contact_number' => fake()->phoneNumber,
            'guardian_last_name' => fake()->lastName,
            'guardian_first_name' => fake()->firstName,
            'guardian_middle_name' => fake()->lastName,
            'guardian_contact_number' => fake()->phoneNumber,

            // Special Needs
            'has_special_needs' => fake()->boolean,
            'special_needs' => fake()->randomElements(
                ['Visual', 'Hearing', 'Speech', 'Mobility'],
                rand(0, 2)
            ),

            // Previous School
            'last_grade_level_completed' => fake()->randomElement(['Grade 6', 'Grade 7', 'Grade 8']),
            'last_school_attended' => fake()->company,
            'last_school_year_completed' => fake()->year . '-' . (fake()->year + 1),
            'school_id' => fake()->numerify('########'),
        ];
    }
}
