<?php
// Usage: php scripts/check_record.php <id>
$id = $argv[1] ?? null;
if (! $id) {
    echo "Usage: php scripts/check_record.php <id>\n";
    exit(1);
}
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$record = \App\Models\MedicalRecord::find($id);
if ($record) {
    echo "FOUND\n";
    echo json_encode([
        'id' => $record->id,
        'appointment_id' => $record->appointment_id,
        'pet_id' => $record->pet_id,
        'doctor_id' => $record->doctor_id,
        'created_at' => (string) $record->created_at,
    ], JSON_PRETTY_PRINT);
    echo "\n";
} else {
    echo "NULL\n";
}
