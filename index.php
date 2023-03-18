<?php

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
