<?php

/**
 * @author Craig Longford <deltawolf7@gmail.com>
 * @package ArcREST
 * @license https://opensource.org/licenses/MIT
 * @copyright Copyright (c) 2023 Craig Longford
 * @link https://github.com/DeltaWolf7/ArcRest
 * @version 1.0.0.0
 */

namespace Arc;

/**
 * ArcREST System class.
 */
class ArcSystem
{
    /**
     * ArcREST Version.
     * 
     * @var string
     */
    private static $version = '1.0.0.0';

    /**
     * Controller name.
     * 
     * @var string
     */
    private static $controllerPart = '';

    /**
     * Action name.
     * 
     * @var string
     */
    private static $actionPart = '';

    /**
     * Returns the path.
     * 
     * @return string
     */
    static function getPath()
    {
        return ltrim($_SERVER['REQUEST_URI'], '/');
    }

    /**
     * Returns the host.
     * 
     * @return string
     */
    static function getHost()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
    }

    /**
     * Returns the document root.
     * 
     * @return string
     */
    static function getDocRoot()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/';
    }

    /**
     * Returns the request method.
     * 
     * @return string
     */
    static function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * Returns the path or the uri as an array.
     * 
     * @return array
     */
    static function getPathArray()
    {
        return explode('/', self::getPath());
    }

    /**
     * Locates the controller and action of a request and then calls it.
     * 
     */
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

    /**
     * Outputs the system version as JSON.
     * If EXPOSE_VERSION is set to false in config, the version is replaced with DISABLED.
     * 
     */
    static function getVersion() {
        if (EXPOSE_VERSION) {
            self::ReturnOK(['arcrest_version' => self::$version]);
        } else {
            self::ReturnOK(['arcrest_version' => 'DISABLED']);
        }
    }

    /**
     * Outputs a 200 OK header and JSON array.
     * @param array $array Array of data to be output as JSON.
     * @param array $headers Array of headers to include to the browser on output.
     * 
     */
    static function returnOK($array, $headers = []) {
        $headers[] = 'HTTP/1.1 200 OK';
        self::returnJSON($array, $headers);
    }

    /**
     * Outputs a 422 Unprocessable Entity responce.
     * @param array $array Array of data to be output as JSON.
     * @param array $headers Array of headers to include to the browser on output.
     * 
     */
    static function returnUnprocessable($array, $headers = []) {
        $headers[] = 'HTTP/1.1 422 Unprocessable Entity';
        self::returnJSON($array, $headers);
    }

    /**
     * Outputs a custom JSON responce.
     * @param array $array Array of data to be output as JSON.
     * @param array $headers Array of headers to include to the browser on output.
     * 
     */
    static function returnCustom($array, $headers = []) {
        self::returnJSON($array, $headers);
    }

    /**
     * Outputs a responce.
     * Removed the Cookie header and sets the JSON type for the browser.
     * @param array $array Array of data to be output as JSON.
     * @param array $headers Array of headers to include to the browser on output.
     * 
     */
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