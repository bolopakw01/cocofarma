<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$controller = new App\Http\Controllers\AdminController();
$request = new Illuminate\Http\Request(['range' => 'weekly']);
echo json_encode($controller->chartData($request)->getData());
