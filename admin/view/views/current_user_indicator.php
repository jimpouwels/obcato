<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/authenticator.php";
    require_once CMS_ROOT . "view/views/visual.php";
    
    class CurrentUserIndicator extends Visual {
    
        private static $TEMPLATE = "system/current_user_indicator.tpl";
        private $_template_engine;

        public function __construct() {
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render(): string {
            $this->_template_engine->assign('username', Authenticator::getCurrentUser()->getFullName());
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    }