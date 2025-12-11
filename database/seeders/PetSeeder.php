<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\User;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users by email
        $testUser = User::where('email', 'mk@gmail.com')->first();
        $owner1 = User::where('email', 'owner@vetcare.com')->first();
        $owner2 = User::where('email', 'owner2@vetcare.com')->first();
        $owner3 = User::where('email', 'owner3@vetcare.com')->first();

        if ($testUser) {
            Pet::firstOrCreate(
                ['name' => 'Max', 'user_id' => $testUser->id],
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
                ['name' => 'Luna', 'user_id' => $testUser->id],
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
                ['name' => 'Buddy', 'user_id' => $testUser->id],
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
                ['name' => 'Bella', 'user_id' => $testUser->id],
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

        if ($owner1) {
            Pet::firstOrCreate(
                ['name' => 'Rocky', 'user_id' => $owner1->id],
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
                ['name' => 'Molly', 'user_id' => $owner1->id],
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

        if ($owner2) {
            Pet::firstOrCreate(
                ['name' => 'Charlie', 'user_id' => $owner2->id],
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

        if ($owner3) {
            Pet::firstOrCreate(
                ['name' => 'Daisy', 'user_id' => $owner3->id],
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