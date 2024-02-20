<?php

namespace Obcato\Core\admin\modules\components\installer;

use Exception;

class InstallationException extends Exception {

    public function __construct($message = '') {
        parent::__construct($message);
    }
}