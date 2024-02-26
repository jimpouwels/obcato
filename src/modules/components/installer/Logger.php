<?php

namespace Obcato\Core\modules\components\installer;

class Logger {

    private array $messages = array();

    public function log($message): void {
        $this->messages[] = date('H:m:s') . ': ' . $message;
    }

    public function getMessages(): array {
        return $this->messages;
    }
}