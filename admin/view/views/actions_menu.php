<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";
    
    class ActionsMenu extends Visual {
    
        private static $TEMPLATE = "system/actions_menu.tpl";
        private $_action_buttons;
    
        public function __construct($action_buttons) {
            $this->_action_buttons = $action_buttons;
        }
    
        public function render() {
            $template_engine = TemplateEngine::getInstance();
            $template_engine->assign("buttons", $this->getActionButtonsHtml());
            return $template_engine->fetch(self::$TEMPLATE);
        }
        
        private function getActionButtonsHtml() {
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