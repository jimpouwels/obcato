<?php

namespace Obcato\Core\admin\core\form;

use Exception;

class FormException extends Exception {

    public function __construct(string $errorMessage = '') {
        parent::__construct($errorMessage);
    }
}