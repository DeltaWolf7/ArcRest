<?php

/**
 * @author Craig Longford <deltawolf7@gmail.com>
 * @package ArcREST
 * @license https://opensource.org/licenses/MIT
 * @copyright Copyright (c) 2023 Craig Longford
 * @link https://github.com/DeltaWolf7/ArcRest
 * @version 1.0.0.0
 */

namespace Arc\Controller;

class IndexController {

    // This method return the version regardless of request type.
    static function version() {
        \Arc\ArcSystem::getVersion();
    }

}