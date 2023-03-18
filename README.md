# Arc Rest API
Arc REST API is a PHP API framework for rapid development.
Based on the Representational state transfer architectural style.

The framework will always return JSON and uses an MVC style system that allow for easy creation of new methods.

# Config
File: Config/config.php

This file contains the database config used by Medoo and a flag for enabling debug. If debug is enabled all JSON outputted will include the debug information such as Controller, Action, Version, Request type, HTTP Headers along side any other returns.
The settings also allow the framework version to be hidden when requests to index are made.

```php
<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'database');

define('DEBUG', false);
define('EXPOSE_VERSION', false);
```

# Basic Usage
Example file: Controller/exampleController.php

This example show a simple random name generator that will select a name from an array and return it when the request method type is GET.
In this example if the request method is not GET, an error is return instead.

```php
<?php

namespace Arc\Controller;

class ExampleController {

    // This example method will only return a random name on a GET request.
    // All other types are returned as an error.
    static function getName() {
        
        switch (\Arc\ArcSystem::getMethod())
        {
            case "GET":
                $names = ['Dave', 'Bob', 'Sue', 'Alice', 'Mike', 'Sam', 'Lizz', 'Tony'];
                \Arc\ArcSystem::returnOK(['name' => $names[array_rand($names, 1)]]);
                break;
            default:
                \Arc\ArcSystem::returnUnprocessable(['error' => 'Request type not supported.']);
                break;
        }

    }
}
```
