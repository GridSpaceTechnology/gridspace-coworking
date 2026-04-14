<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Categories in database:\n";
App\Models\Category::orderBy('name')->get()->each(function($c) {
    echo $c->id . ': ' . $c->name . ' (' . $c->slug . ')' . PHP_EOL;
});
