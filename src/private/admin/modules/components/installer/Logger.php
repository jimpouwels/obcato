<?php

class Logger {

    private $messages = array();

    public function log($message) {
        $this->messages[] = date('H:m:s') . ': ' . $message;
    }

    public function getMessages() {
        return $this->messages;
    }
}