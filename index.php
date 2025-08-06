<?php
require_once __DIR__ . '/vendor/autoload.php';

// Optional: load environment variables if using .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Get requested URL path
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Define allowed base directories
$allowedDirs = ['models', 'routes', 'api'];

// Check if the request matches any allowed folder and file exists
foreach ($allowedDirs as $dir) {
    if (strpos($requestUri, $dir . '/') === 0) {
        $filePath = __DIR__ . '/' . $requestUri . '.php';
        if (file_exists($filePath)) {
            require $filePath;
            exit;
        }
    }
}

// If no match → return 404 JSON
http_response_code(404);
echo json_encode([
    "error" => "Endpoint not found",
    "path" => $requestUri
]);
