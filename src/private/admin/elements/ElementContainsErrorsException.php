<?php

namespace Obcato\Core;

use Exception;

class ElementContainsErrorsException extends Exception {

    public function __construct($message = '') {
        parent::__construct($message);
    }
}

