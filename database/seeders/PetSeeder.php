<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\Owner;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test owner (mk@gmail.com)
        $testOwner = Owner::where('email', 'mk@gmail.com')->first();

        if ($testOwner) {
            // Create sample pets for test user
            Pet::create([
                'customer_id' => $testOwner->id,
                'name' => 'Max',
                'species' => 'dog',
                'breed' => 'Golden Retriever',
                'age' => 3.5,
                'weight' => 28.5,
                'gender' => 'male',
                'color' => 'Golden',
            ]);

            Pet::create([
                'customer_id' => $testOwner->id,
                'name' => 'Luna',
                'species' => 'cat',
                'breed' => 'Persian',
                'age' => 2.0,
                'weight' => 4.2,
                'gender' => 'female',
                'color' => 'White',
            ]);

            Pet::create([
                'customer_id' => $testOwner->id,
                'name' => 'Buddy',
                'species' => 'dog',
                'breed' => 'Labrador',
                'age' => 1.5,
                'weight' => 22.0,
                'gender' => 'male',
                'color' => 'Black',
            ]);

            Pet::create([
                'customer_id' => $testOwner->id,
                'name' => 'Bella',
                'species' => 'rabbit',
                'breed' => 'Holland Lop',
                'age' => 0.8,
                'weight' => 1.5,
                'gender' => 'female',
                'color' => 'Brown',
            ]);
        }

        // Also create some pets for other owners for variety
        $owner1 = Owner::where('email', 'owner@vetcare.com')->first();
        if ($owner1) {
            Pet::create([
                'customer_id' => $owner1->id,
                'name' => 'Rocky',
                'species' => 'dog',
                'breed' => 'German Shepherd',
                'age' => 4.0,
                'weight' => 32.0,
                'gender' => 'male',
                'color' => 'Brown/Black',
            ]);

            Pet::create([
                'customer_id' => $owner1->id,
                'name' => 'Molly',
                'species' => 'cat',
                'breed' => 'Maine Coon',
                'age' => 3.2,
                'weight' => 6.8,
                'gender' => 'female',
                'color' => 'Tabby',
            ]);
        }

        $owner2 = Owner::where('email', 'owner2@vetcare.com')->first();
        if ($owner2) {
            Pet::create([
                'customer_id' => $owner2->id,
                'name' => 'Charlie',
                'species' => 'dog',
                'breed' => 'Beagle',
                'age' => 2.5,
                'weight' => 12.0,
                'gender' => 'male',
                'color' => 'Tri-color',
            ]);
        }

        $owner3 = Owner::where('email', 'owner3@vetcare.com')->first();
        if ($owner3) {
            Pet::create([
                'customer_id' => $owner3->id,
                'name' => 'Daisy',
                'species' => 'cat',
                'breed' => 'British Shorthair',
                'age' => 1.8,
                'weight' => 5.5,
                'gender' => 'female',
                'color' => 'Gray',
            ]);
        }
    }
}