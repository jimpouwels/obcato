<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";
    
    class ActionsMenu extends Visual {
    
        private array $_action_buttons;
    
        public function __construct(array $action_buttons) {
            parent::__construct();
            $this->_action_buttons = $action_buttons;
        }
    
        public function getTemplateFilename(): string {
            return "system/actions_menu.tpl";
        }

        public function load(): void {
            $this->assign("buttons", $this->getActionButtonsHtml());
        }
        
        private function getActionButtonsHtml(): array {
            $buttons_html = array();
            foreach ($this->_action_buttons as $action_button) {
                if (!is_null($action_button)) {
                    $buttons_html[] = $action_button->render();
                }
            }
            return $buttons_html;
        }
        
    }

?>