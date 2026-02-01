<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select('SHOW TABLES');
    $dbName = env('DB_DATABASE');
    $key = "Tables_in_{$dbName}";
    
    echo "📊 عدد الجداول في قاعدة البيانات: " . count($tables) . "\n\n";
    echo "📋 قائمة الجداول:\n";
    echo str_repeat("=", 50) . "\n";
    
    foreach ($tables as $index => $table) {
        echo ($index + 1) . ". " . $table->$key . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}
