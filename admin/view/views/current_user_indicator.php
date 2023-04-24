<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/authenticator.php";
    require_once CMS_ROOT . "view/views/visual.php";
    
    class CurrentUserIndicator extends Visual {
    

        public function __construct() {
            parent::__construct();
        }

        public function getTemplateFilename(): string {
            return "system/current_user_indicator.tpl";
        }

        public function load(): void {
            $this->assign('username', Authenticator::getCurrentUser()->getFullName());
        }
    }