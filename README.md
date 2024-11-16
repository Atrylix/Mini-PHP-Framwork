# Mini PHP Framework

A lightweight PHP framework in development, designed for simplicity and ease of use. Currently, it includes a basic router with GET and POST methods and a simple template engine. The framework aims to provide an easy setup for small projects with minimal features.

## Features

- **Router**: Supports custom syntax for GET and POST methods.
- **Template Engine**: Allows rendering of dynamic content through templates.

## Installation

### Requirements:
- PHP 7.4 or higher
- Apache with mod_rewrite enabled
- Composer (for dependency management)
- Python (for the installation script)

### License Files
After running the installation script, you'll need to manually copy the license files into your project directory:
- **LICENSE**: Located in the **root** of the repository.

### Installation Steps:

1. Clone the repository to your local machine:
   ```bash
   git clone https://github.com/yourusername/mini-php-framework.git
   ```

2. Navigate to the project directory:
    ```bash
    cd mini-php-framework
    ```

3. Run the installation script (Linux only for now):
    ```bash
    python3 install.py
    ```
The script will install the necessary project files and set up the framework. If you’re using a different operating system, you’ll need to set up the project manually (detailed steps later).

4. Make sure Apache is installed and mod_rewrite is enabled. You can enable mod_rewrite by running:
    ```bash
    sudo a2enmod rewrite
    ```

5. Set up your Apache virtual host to point to the project directory. Example configuration for Apache:
    ```apache
    <VirtualHost *:80>
        DocumentRoot /path/to/mini-php-framework
        <Directory /path/to/mini-php-framework>
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>
    ```

6. Restart Apache:
    ```bash
    sudo systemctl restart apache2 # Or systemctl restart httpd
    ```

7. After installation, navigate to http://localhost in your browser. The router should route to the root of the project, and the template engine will render the index page.

## Usage Example

Once installed, you can start using the router and template engine. Here's a simple example:

### index.php
```php
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
```
## Template Engine Syntax

In the HTML templates, you can use the following syntax for variables and includes:
    Variable rendering:
    ```html
    <<var variable_name>>
    ```
    Example:
    ```html
    <h1>Welcome to <<var Site_Name>>!</h1>```
    Including other templates:
    ```html
    <<include include_name.html>>
    ```
    Example:
    ```html
    <<include header.html>>
    ```

## Passing Data to Templates

When rendering a template, you can pass data to it in the form of an associative array:
```php
$data = [
    "Site_Name" => "Hello World"
];
TemplateEngine::init()->render('index.html', $data);
```

*Note: You can also pass individual variables, but using an array is recommended to keep things organized.*

### Folder Structure for Templates and Includes
To ensure everything works, place all your templates and includes in the appropriate folders:
- **Templates**: Place all your template files in the `src/App/views` directory.
- **Includes**: Place all your include files (e.g., header, footer) in the `src/App/views/includes` directory.

### Manual Setup for Other Operating Systems

If you're using macOS or Windows, you can still set up the project manually. Follow these steps:

1. **Install Dependencies**:
   - Make sure PHP 7.4 or higher is installed. Or use XAMPP.
   - Install Composer by following the official [Composer installation guide](https://getcomposer.org/download/).
   - Ensure Apache is installed, and mod_rewrite is enabled.
   
2. **Clone the Repository**:
   Clone the repository as explained in the installation steps. Copy all files from the framework folder to your project location.

3. **Install Project Dependencies**:
   In the project directory, run the following command to install dependencies via Composer:
   ```bash
   composer install
   ```

4. **Setup apache config file**:
    Add this to the end of your apache conf file:
    ```apache
    LoadModule php_module modules/libphp.so
    AddHandler php-script .php
    Include conf/extra/php_module.conf

    Listen 8080

    ServerName localhost

    <VirtualHost *:8080>
        DocumentRoot "/path/to/your/project/root"
        ServerName localhost
        ErrorLog "/path/to/your/project/root/logs/error_log.log"

        <Directory "/path/to/your/project/root">
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>
   ```

5. **Start Apache**:
    Run the appropriate command to start Apache on your system.

6. **Navigate to Your Localhost**:
    After starting Apache, navigate to http://localhost to see your project in action.

### Contributing
I welcome contributions! If you'd like to help improve this framework, feel free to open an issue or submit a pull request. Here’s how you can contribute:

    1. Fork the repository.
    2. Clone your fork to your local machine.
    3. Make your changes.
    4. Push your changes back to your fork.
    5. Submit a pull request with a description of what you’ve changed.

We encourage all contributors to follow the standard coding conventions and to write clear commit messages.

### License
This project is licensed under the MIT License. See the LICENSE file for more information.
