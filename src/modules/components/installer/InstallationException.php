<?php

namespace Pageflow\Core\modules\components\installer;

use Exception;

class InstallationException extends Exception {

    public function __construct($message = '') {
        parent::__construct($message);
    }
}