<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
\Illuminate\Support\Facades\Auth::login(App\Models\User::first());
$request = Illuminate\Http\Request::create('/backoffice/master-user', 'GET');
$response = $kernel->handle($request);
file_put_contents('error_page.html', $response->getContent());
$kernel->terminate($request, $response);
echo "HTML written to error_page.html";
