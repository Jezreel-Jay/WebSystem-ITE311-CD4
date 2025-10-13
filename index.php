<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)

// Block direct browser access to index.php (show custom 404)
if (isset($_SERVER['REQUEST_URI']) && preg_match('/index\.php/i', $_SERVER['REQUEST_URI'])) {
    http_response_code(404);
    include __DIR__ . '/public/error_404.html';
    exit;
}
if (isset($_SERVER['REQUEST_URI']) && preg_match('/(index\.php|\/public\/?)/i', $_SERVER['REQUEST_URI'])) {
    http_response_code(404);
    include __DIR__ . '/public/error_404.html';
    exit;
}

/*
 *---------------------------------------------------------------
 * CUSTOM: Handle Unauthorized / Out-of-Scope Access
 *---------------------------------------------------------------
 * Only allow routes related to Auth controller
 * (like /login, /register, /dashboard, etc.)
 * If not matching -> show 404 error page
 */
//$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = $_SERVER['REQUEST_URI'];

// Normalize: remove leading/trailing slashes
$requestUri = trim($requestUri, '/');

// Reject URLs with multiple consecutive slashes (like ////login)
// if (preg_match('#/{2,}#', $_SERVER['REQUEST_URI'])) {
//     http_response_code(404);
//     include __DIR__ . '/public/error_404.html';
//     exit;
// }
// $requestUri = strtolower($requestUri);
// $allowedRoutes = [
//     '', '/', 
//     'home', 'about', 'contact',
//     'login', 'logout', 'dashboard',
//     'register',
//     'auth/addUserByAdmin',
//     'auth/updateUserRole',
//     'auth/deleteUser',
//     // Include post routes that share names with get routes
//     'login/attempt', 'register/store', 'auth/addRole'
// ];
// if ($requestUri === 'home' || $requestUri === 'home/index') {
//     header('Location: ' . base_url('/'));
//     exit;
// }
// $allowed = false;
// foreach ($allowedRoutes as $route) {
//     if (preg_match("#/{$route}(/|$)#i", $requestUri)) {
//         $allowed = true;
//         break;
//     }
// }

// if (!$allowed) {
//     // http_response_code(404);
//     // include __DIR__ . '/public/error_404.html';
//     // exit;
// }






define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
require FCPATH . '/app/Config/Paths.php';
// ^^^ Change this line if you move your application folder

$paths = new Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
