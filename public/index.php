<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Sembunyikan PHP "Deprecated" notice (mis. konstanta PDO::MYSQL_ATTR_* yang
// di-deprecate sejak PHP 8.5) agar tidak tercetak di atas halaman saat config
// dimuat. Aman & portable: tidak berpengaruh di PHP 8.2–8.4, dan di production
// tetap tertutup karena display_errors dimatikan.
error_reporting(error_reporting() & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
