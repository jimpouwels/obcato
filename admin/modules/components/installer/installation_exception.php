<?php
defined('_ACCESS') or die;

class InstallationException extends Exception {

    public function __construct($message = '') {
        parent::__construct($message);
    }
}