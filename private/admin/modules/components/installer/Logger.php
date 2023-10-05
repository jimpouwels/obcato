<?php

class Logger {

    private $_log_messages = array();

    public function log($message) {
        $this->_log_messages[] = date('H:m:s') . ': ' . $message;
    }

    public function getLogMessages() {
        return $this->_log_messages;
    }
}