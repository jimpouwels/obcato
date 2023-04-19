<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/authenticator.php";
    require_once CMS_ROOT . "view/views/visual.php";
    
    class CurrentUserIndicator extends Visual {
    
        private static string $TEMPLATE = "system/current_user_indicator.tpl";

        public function __construct() {
            parent::__construct();
        }

        public function render(): string {
            $this->getTemplateEngine()->assign('username', Authenticator::getCurrentUser()->getFullName());
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    }