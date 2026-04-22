<?php

namespace Pageflow\Core\request_handlers\exceptions;

use Exception;

class ElementHolderContainsErrorsException extends Exception {
    public function __construct($message = '') {
        parent::__construct($message);
    }
}