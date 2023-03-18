<?php

/**
 * @author Craig Longford <deltawolf7@gmail.com>
 * @package ArcREST
 * @license https://opensource.org/licenses/MIT
 * @copyright Copyright (c) 2023 Craig Longford
 * @link https://github.com/DeltaWolf7/ArcRest
 * @version 1.0.0.0
 */

// Check PHP version
if (version_compare(phpversion(), '8.1.0', '<') == true) {
    die('PHP 8.1.0 or newer required');
}

// Required config file.
require_once __DIR__ . '\\config\\config.php';
require_once __DIR__ . '\\vendor\\Arc\\ArcSystem.php';
require_once __DIR__ . '\\vendor\\Medoo\\Medoo.php';

// Initilise Arc System.
Arc\ArcSystem::route();
