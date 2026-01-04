<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get test user customer
        $testUser = User::where('email', 'mk@gmail.com')->first();

        if ($testUser) {
            Customer::firstOrCreate(
                ['user_id' => $testUser->id],
                [
                    'name' => 'Test User',
                    'phone' => '085174262645',
                    'email' => 'mk@gmail.com',
                    'address' => 'Jl. Test No. 999, Jakarta',
                ]
            );
        }

        // Get customer users
        $customer1 = User::where('email', 'customer@vetcare.com')->first();
        if ($customer1) {
            Customer::firstOrCreate(
                ['user_id' => $customer1->id],
                [
                    'name' => 'Budi Santoso',
                    'phone' => '085174262645',
                    'email' => 'customer@vetcare.com',
                    'address' => 'Jl. Sudirman No. 123, Jakarta',
                ]
            );
        }

        $customer2 = User::where('email', 'customer2@vetcare.com')->first();
        if ($customer2) {
            Customer::firstOrCreate(
                ['user_id' => $customer2->id],
                [
                    'name' => 'Siti Sarah',
                    'phone' => '085174262645',
                    'email' => 'customer2@vetcare.com',
                    'address' => 'Jl. Thamrin No. 456, Jakarta',
                ]
            );
        }

        $customer3 = User::where('email', 'customer3@vetcare.com')->first();
        if ($customer3) {
            Customer::firstOrCreate(
                ['user_id' => $customer3->id],
                [
                    'name' => '`Ahmad `Wijaya',
                    'phone' => '085174262645',
                    'email' => 'customer3@vetcare.com',
                    'address' => 'Jl. Gatot Subroto No. 789, Jakarta',
                ]
            );
        }
    }
}