<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/notifications.php";
    
    class NotificationBar extends Visual {
    
        private static $TEMPLATE = "system/notification_bar.tpl";
        
        public function __construct() {
            parent::__construct();
        }
        
        public function render(): string {
            $this->getTemplateEngine()->assign("message", Notifications::getMessage());
            $this->getTemplateEngine()->assign("success", Notifications::getSuccess());
            Notifications::clearMessage();
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
        
    }