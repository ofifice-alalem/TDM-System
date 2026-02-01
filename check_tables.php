<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$tables = DB::select('SHOW TABLES');
$dbName = 'Tables_in_' . env('DB_DATABASE');

echo "الجداول الموجودة في قاعدة البيانات:\n";
foreach ($tables as $table) {
    echo "- " . $table->$dbName . "\n";
}
