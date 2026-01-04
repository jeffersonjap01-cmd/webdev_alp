<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$req = Illuminate\Http\Request::create('/prescriptions/create', 'GET');
try {
    $route = app('router')->getRoutes()->match($req);
    $action = $route->getAction();
    echo "Matched route:\n";
    print_r($action);
} catch (Exception $e) {
    echo "Exception: " . get_class($e) . " - " . $e->getMessage() . "\n";
}
