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