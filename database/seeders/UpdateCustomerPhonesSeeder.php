<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateCustomerPhonesSeeder extends Seeder
{
    /**
     * Run the database updates to replace legacy phone numbers with the user's phone.
     */
    public function run(): void
    {
        $target = '085101908219';

        $patterns = [
            '085174262645%',
            '08123456789%',
            '8123456789%',
            '628123456789%',
            '+628123456789%'
        ];

        $total = 0;

        foreach ($patterns as $pat) {
            $updated = DB::table('customers')->where('phone', 'like', $pat)->update(['phone' => $target]);
            if ($updated) {
                $total += $updated;
                Log::info("Updated {$updated} customer(s) matching {$pat} to {$target}");
            }
        }

        $exacts = [
            '081234567894', '081234567895', '081234567890', '081234567891', '081234567892', '081234567893'
        ];

        foreach ($exacts as $old) {
            $updated = DB::table('customers')->where('phone', $old)->update(['phone' => $target]);
            if ($updated) {
                $total += $updated;
                Log::info("Updated {$updated} customer(s) from {$old} to {$target}");
            }
        }

        echo "Updated {$total} customer(s) to {$target}\n";
    }
}
