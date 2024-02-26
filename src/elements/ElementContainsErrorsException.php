<?php

namespace Obcato\Core\elements;

use Exception;

class ElementContainsErrorsException extends Exception {

    public function __construct($message = '') {
        parent::__construct($message);
    }
}
