<?php

namespace Obcato\Core\modules\webforms;

use Obcato\Core\modules\webforms\handlers\ArticleCommentFormHandler;
use Obcato\Core\modules\webforms\handlers\EmailFormHandler;
use Obcato\Core\modules\webforms\handlers\FormHandler;
use Obcato\Core\modules\webforms\handlers\RedirectFormHandler;

class WebformHandlerManager {

    private array $_all_handlers = array();
    private static ?WebformHandlerManager $_instance = null;

    private function __construct() {
        $this->_all_handlers[] = new EmailFormHandler();
        $this->_all_handlers[] = new RedirectFormHandler();
        $this->_all_handlers[] = new ArticleCommentFormHandler();
    }

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new WebformHandlerManager();
        }
        return self::$_instance;
    }

    public function getHandler(string $type): FormHandler {
        $found_handler = null;
        foreach ($this->_all_handlers as $handler) {
            if ($handler->getType() == $type) {
                $found_handler = $handler;
            }
        }
        return $found_handler;
    }

    public function getAllHandlers(): array {
        return $this->_all_handlers;
    }
}

?>