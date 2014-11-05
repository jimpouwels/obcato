<?php

    
    defined('_ACCESS') or die;
    
    class ActionButton extends Visual {
    
        private $TEMPLATE = "system/actions_menu_button.tpl";
        private $myLabel;
        private $myActionId;
        private $myIconClass;
    
        public function __construct($label, $action_id, $icon_class) {
            $this->myLabel = $label;
            $this->myActionId = $action_id;
            $this->myIconClass = $icon_class;
        }
    
        public function render() {
            $template_engine = TemplateEngine::getInstance();
            
            $template_engine->assign("action_id", $this->myActionId);
            $template_engine->assign("icon_class", $this->myIconClass);
            $template_engine->assign("label", $this->myLabel);
            return $template_engine->fetch($this->TEMPLATE);
        }
    
    }

?>