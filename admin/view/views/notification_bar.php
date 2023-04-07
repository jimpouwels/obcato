<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/notifications.php";
    
    class NotificationBar extends Visual {
    
        private static $TEMPLATE = "system/notification_bar.tpl";
        private $_template_engine;
        
        public function __construct() {
            parent::__construct();
            $this->_template_engine = TemplateEngine::getInstance();
        }
        
        public function renderVisual(): string {
            $this->_template_engine->assign("message", Notifications::getMessage());
            $this->_template_engine->assign("success", Notifications::getSuccess());
            Notifications::clearMessage();
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
        
    }