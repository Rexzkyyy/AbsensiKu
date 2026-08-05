<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = app(\App\Http\Controllers\Admin\PrestasiController::class);
$request = \Illuminate\Http\Request::create('/admin/prestasi/ajax', 'POST', [
    'kegiatan' => 'all',
    'start_date' => null,
    'end_date' => null
]);
try {
    $response = $controller->ajaxFilter($request);
    echo "SUCCESS: \n" . $response->getContent();
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
