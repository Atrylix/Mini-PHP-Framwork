<?php
const PROJECT_ROOT_PATH = __DIR__ . '/..';

// Require scripts
require PROJECT_ROOT_PATH . '/vendor/autoload.php';

// Include namespaces
use App\Config\Config;
use App\Core\Router;
use App\Core\TemplateEngine;

// Initialize instances
Router::init();
TemplateEngine::init();

// Define routes
Router::init()->get('/', function() {
    $data = [
        "Site_Name" => "Hello World"
    ];
    TemplateEngine::init()->render('index.html', $data /* You can leave this blank if your page doesn't require it */);
});

// Run the site
Router::init()->run();