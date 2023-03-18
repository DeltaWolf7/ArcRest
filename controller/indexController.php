<?php

namespace Arc\Controller;

class IndexController {

    // This method return the version regardless of request type.
    static function version() {
        \Arc\ArcSystem::getVersion();
    }

}