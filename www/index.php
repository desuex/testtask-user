<?php

use App\Kernel;

require_once __DIR__ . '/../src/App/Kernel.php';

// Bootstrap the application
$application = Kernel::bootstrap();

// Handle the request
$application->handle();