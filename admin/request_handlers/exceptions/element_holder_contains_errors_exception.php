<?php
    defined('_ACCESS') or die;

    class ElementHolderContainsErrorsException extends Exception {
        public function __construct($message = '') {
            parent::__construct($message);
        }
    }