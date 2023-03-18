<?php

namespace Arc;

class ArcSystem
{
    private static $version = '1.0.0.0';
    private static $controllerPart = '';
    private static $actionPart = '';

    // Get current path.
    static function getPath()
    {
        return ltrim($_SERVER['REQUEST_URI'], '/');
    }

    // Get Host.
    static function getHost()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
    }

    // Get document root.
    static function getDocRoot()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/';
    }

    // Get request method
    static function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    // Get path as array.
    static function getPathArray()
    {
        return explode('/', self::getPath());
    }

    // Get route.
    static function route() {
        $pathParts = self::getPathArray();

        if ($pathParts[0] == '') {
            self::$controllerPart = 'index';
            self::$actionPart = 'version';
        }
        else if ($pathParts[0] != '') {
            self::$controllerPart = $pathParts[0];
            if (count($pathParts) > 1) {
                self::$actionPart = $pathParts[1];
            }
        }
        

        // see if the controller file exists and load it.
        $controller = self::getDocRoot() . 'controller/' . self::$controllerPart . 'Controller.php';
        if (file_exists($controller)) {
            require_once $controller;
            $controllerClass = 'Arc\\Controller\\' . self::$controllerPart . 'Controller';
            if (method_exists($controllerClass, self::$actionPart)) {
                // we have the action method also, so execute.
                $method = self::$actionPart;
                $controllerClass::$method();
                return;
            }
        }

        self::ReturnUnprocessable(['error' => 'Method not supported']);            
    }

    // Get version.
    static function getVersion() {
        if (EXPOSE_VERSION) {
            self::ReturnOK(['arcrest_version' => self::$version]);
        } else {
            self::ReturnOK(['arcrest_version' => 'DISABLED']);
        }
    }

    // Return 200 OK Responce
    static function returnOK($array, $headers = []) {
        $headers[] = 'HTTP/1.1 200 OK';
        self::returnJSON($array, $headers);
    }

    // Return 422 Unprocessable Entity Responce
    static function returnUnprocessable($array, $headers = []) {
        $headers[] = 'HTTP/1.1 422 Unprocessable Entity';
        self::returnJSON($array, $headers);
    }

    // Return custom Responce
    static function returnCustom($array, $headers = []) {
        self::returnJSON($array, $headers);
    }

    // Set header and return JSON.
    private static function returnJSON($array, $headers = []) {
        header_remove('Set-Cookie');
        header("Content-Type: application/json", false);

        // add headers
        if (is_array($headers) && count($headers)) {
            foreach ($headers as $headers) {
                header($headers, false);
            }
        }

        // add debug if enabled
        if (DEBUG == true) {
            $array['debug_enabled'] = DEBUG; 
            $array['arcrest_version'] = self::$version; 
            $array['controller'] = self::$controllerPart;
            $array['action'] = self::$actionPart;
            $array['request_type'] = self::getMethod();
            $array['http_headers'] = $headers;
        }

        echo json_encode($array);
    }
}