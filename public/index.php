<?php
/**
 * Front Controller
 * Entry point for the application
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define application directory
define('APP_DIR', __DIR__ . '/../app');

// Autoload classes
require_once APP_DIR . '/Router.php';
require_once APP_DIR . '/BaseController.php';
require_once APP_DIR . '/controllers/GuessController.php';

// Create router
$router = new Router();

// Define routes
$router->addRoute('GET', 'index', 'GuessController', 'index');
$router->addRoute('GET', '', 'GuessController', 'index');
$router->addRoute('POST', 'guess', 'GuessController', 'guess');

// Dispatch request
try {
    $router->dispatch();
} catch (Exception $e) {
    // Handle exceptions
    header("HTTP/1.1 500 Internal Server Error");
    echo "Error: " . $e->getMessage();
}