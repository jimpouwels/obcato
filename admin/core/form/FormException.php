<?php
defined('_ACCESS') or die;

class FormException extends Exception {

    public function __construct(string $error_message = '') {
        parent::__construct($error_message);
    }
}