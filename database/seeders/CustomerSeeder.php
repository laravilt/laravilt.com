<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('No user found. Please create a user first.');
            return;
        }

        $faker = Faker::create();
        $count = 500; // Create 500 customers

        $this->command->info("Creating {$count} customers...");

        $statuses = ['active', 'inactive', 'blocked'];
        $genders = ['male', 'female', 'other'];
        $countries = ['US', 'CA', 'GB', 'AU', 'DE', 'FR', 'ES', 'IT', 'JP', 'BR', 'MX', 'EG', 'SA', 'AE', 'IN'];

        $customers = [];
        $now = now();

        for ($i = 0; $i < $count; $i++) {
            $customers[] = [
                'user_id' => $user->id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'date_of_birth' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'gender' => $faker->randomElement($genders),
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'state' => $faker->state,
                'postal_code' => $faker->postcode,
                'country' => $faker->randomElement($countries),
                'status' => $faker->randomElement($statuses),
                'total_spent' => $faker->randomFloat(2, 0, 10000),
                'orders_count' => $faker->numberBetween(0, 50),
                'last_order_at' => $faker->optional(0.7)->dateTimeBetween('-1 year', 'now'),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Insert in batches of 100
            if (count($customers) >= 100) {
                Customer::insert($customers);
                $customers = [];
                $this->command->info("Created " . ($i + 1) . " customers...");
            }
        }

        // Insert remaining customers
        if (!empty($customers)) {
            Customer::insert($customers);
        }

        $this->command->info("{$count} customers seeded successfully!");
    }
}
