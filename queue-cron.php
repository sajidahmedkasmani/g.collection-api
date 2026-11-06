<?php

use Illuminate\Contracts\Console\Kernel;

// Laravel ko boot karein
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);

// Queue worker chalayein (jab queue khali ho jaye to khud ruk jayega)
$status = $kernel->call('queue:work', [
    '--stop-when-empty' => true,
    '--tries' => 3,
]);

// Output dikhayein (taake Hostinger ke "View Output" mein error nazar aaye agar koi ho)
echo $kernel->output();