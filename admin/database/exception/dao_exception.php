<?php

defined('_ACCESS') or die;

class DaoException extends Exception {

    public function __construct($message) {
        parent::__construct($message);
    }
}

?>