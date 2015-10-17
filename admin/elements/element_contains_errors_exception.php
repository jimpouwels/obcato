<?php
    defined('_ACCESS') or die;

    class ElementContainsErrorsException extends Exception {

        public function __construct($message = '') {
            parent::__construct($message);
        }
    }

