<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\Customer;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test customer (mk@gmail.com)
        $testCustomer = Customer::where('email', 'mk@gmail.com')->first();

        if ($testCustomer) {
            // Create sample pets for test user
            Pet::firstOrCreate(
                ['name' => 'Max', 'customer_id' => $testCustomer->id],
                [
                    'species' => 'dog',
                    'breed' => 'Golden Retriever',
                    'age' => 3.5,
                    'weight' => 28.5,
                    'gender' => 'male',
                    'color' => 'Golden',
                ]
            );

            Pet::firstOrCreate(
                ['name' => 'Luna', 'customer_id' => $testCustomer->id],
                [
                    'species' => 'cat',
                    'breed' => 'Persian',
                    'age' => 2.0,
                    'weight' => 4.2,
                    'gender' => 'female',
                    'color' => 'White',
                ]
            );

            Pet::firstOrCreate(
                ['name' => 'Buddy', 'customer_id' => $testCustomer->id],
                [
                    'species' => 'dog',
                    'breed' => 'Labrador',
                    'age' => 1.5,
                    'weight' => 22.0,
                    'gender' => 'male',
                    'color' => 'Black',
                ]
            );

            Pet::firstOrCreate(
                ['name' => 'Bella', 'customer_id' => $testCustomer->id],
                [
                    'species' => 'rabbit',
                    'breed' => 'Holland Lop',
                    'age' => 0.8,
                    'weight' => 1.5,
                    'gender' => 'female',
                    'color' => 'Brown',
                ]
            );
        }

        // Also create some pets for other customers for variety
        $customer1 = Customer::where('email', 'owner@vetcare.com')->first();
        if ($customer1) {
            Pet::firstOrCreate(
                ['name' => 'Rocky', 'customer_id' => $customer1->id],
                [
                    'species' => 'dog',
                    'breed' => 'German Shepherd',
                    'age' => 4.0,
                    'weight' => 32.0,
                    'gender' => 'male',
                    'color' => 'Brown/Black',
                ]
            );

            Pet::firstOrCreate(
                ['name' => 'Molly', 'customer_id' => $customer1->id],
                [
                    'species' => 'cat',
                    'breed' => 'Maine Coon',
                    'age' => 3.2,
                    'weight' => 6.8,
                    'gender' => 'female',
                    'color' => 'Tabby',
                ]
            );
        }

        $customer2 = Customer::where('email', 'owner2@vetcare.com')->first();
        if ($customer2) {
            Pet::firstOrCreate(
                ['name' => 'Charlie', 'customer_id' => $customer2->id],
                [
                    'species' => 'dog',
                    'breed' => 'Beagle',
                    'age' => 2.5,
                    'weight' => 12.0,
                    'gender' => 'male',
                    'color' => 'Tri-color',
                ]
            );
        }

        $customer3 = Customer::where('email', 'owner3@vetcare.com')->first();
        if ($customer3) {
            Pet::firstOrCreate(
                ['name' => 'Daisy', 'customer_id' => $customer3->id],
                [
                    'species' => 'cat',
                    'breed' => 'British Shorthair',
                    'age' => 1.8,
                    'weight' => 5.5,
                    'gender' => 'female',
                    'color' => 'Gray',
                ]
            );
        }
    }
}