<?php

class ElementHolderContainsErrorsException extends Exception {
    public function __construct($message = '') {
        parent::__construct($message);
    }
}