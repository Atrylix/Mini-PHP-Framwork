<?php

/*
* MIT License
*
* Copyright (c) 2024 Atrylix
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

namespace App\Core;

class Router
{
    // Static instance for Singleton pattern
    private static $instance;
    
    // Array to store registered routes
    private $routes = [];

    // Initializes the Router instance if not already created
    public static function init()
    {
        if (self::$instance == null) {
            self::$instance = new self();
            return self::$instance;
        } else {
            return self::$instance;
        }
    }

    // Prevent cloning to enforce Singleton pattern
    private function __clone() {}

    // Registers a route for GET requests
    public function get($route, $callback)
    {
        $this->routes[$route] = ['method' => 'GET', 'callback' => $callback];
    }

    // Registers a route for POST requests
    public function post($route, $callback)
    {
        $this->routes[$route] = ['method' => 'POST', 'callback' => $callback];
    }

    // Matches the current request with registered routes and runs the callback
    public function run()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptDir = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        $uri = str_replace($scriptDir, '', $uri);

        // Get the request method (e.g., GET or POST)
        $method = $_SERVER['REQUEST_METHOD'];

        // Loop through registered routes to find a match
        foreach ($this->routes as $route => $config) {
            if ($uri === $route && $method === $config['method']) {
                $callback = $config['callback'];
                $callback(); // Execute the callback function if route matches
                return;
            }
        }

        // Return 404 response if no route is matched
        http_response_code(404);
        echo 'Not Found';
    }

    /*public function run()
    {
        // Get the request path and dynamically handle the project directory
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove the project path dynamically
        $scriptDir = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        $uri = str_replace($scriptDir, '', $uri);

        // Get the request method (e.g., GET or POST)
        $method = $_SERVER['REQUEST_METHOD'];

        // Loop through registered routes to find a match
        foreach ($this->routes as $route => $config) {
            if ($uri === $route && $method === $config['method']) {
                $callback = $config['callback'];
                $callback(); // Execute the callback function if route matches
                return;
            }
        }

        // Return 404 response if no route is matched
        http_response_code(404);
        echo 'Not Found';
    }*/
}
