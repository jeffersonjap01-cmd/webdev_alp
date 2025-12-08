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
        // Get test user owner
        $testUser = User::where('email', 'mk@gmail.com')->first();

        if ($testUser) {
            Customer::firstOrCreate(
                ['user_id' => $testUser->id],
                [
                    'name' => 'Test User',
                    'phone' => '081234567895',
                    'email' => 'mk@gmail.com',
                    'address' => 'Jl. Test No. 999, Jakarta',
                ]
            );
        }

        // Get owner users
        $owner1 = User::where('email', 'owner@vetcare.com')->first();
        if ($owner1) {
            Customer::firstOrCreate(
                ['user_id' => $owner1->id],
                [
                    'name' => 'Budi Santoso',
                    'phone' => '081234567892',
                    'email' => 'owner@vetcare.com',
                    'address' => 'Jl. Sudirman No. 123, Jakarta',
                ]
            );
        }

        $owner2 = User::where('email', 'owner2@vetcare.com')->first();
        if ($owner2) {
            Customer::firstOrCreate(
                ['user_id' => $owner2->id],
                [
                    'name' => 'Siti Sarah',
                    'phone' => '081234567893',
                    'email' => 'owner2@vetcare.com',
                    'address' => 'Jl. Thamrin No. 456, Jakarta',
                ]
            );
        }

        $owner3 = User::where('email', 'owner3@vetcare.com')->first();
        if ($owner3) {
            Customer::firstOrCreate(
                ['user_id' => $owner3->id],
                [
                    'name' => 'Ahmad Wijaya',
                    'phone' => '081234567894',
                    'email' => 'owner3@vetcare.com',
                    'address' => 'Jl. Gatot Subroto No. 789, Jakarta',
                ]
            );
        }
    }
}