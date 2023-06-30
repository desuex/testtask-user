<?php

use App\Kernel;

require_once __DIR__ . '/../src/App/Kernel.php';

// Bootstrap the application
$application = Kernel::bootstrap();

// Handle the request
try {
    $application->handle();
} catch (\App\Exceptions\HttpException $e) {
    printf("%d: %s",$e->getHttpCode(), $e->getMessage());
    die();
} catch (Exception $e) {
    printf("%d: %s",500, "Unknown Exception");
    die();
}