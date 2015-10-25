<?php
    defined('_ACCESS') or die;

    class ActionButton extends Visual {

        private $TEMPLATE = "system/actions_menu_button.tpl";
        private $_label;
        private $_action_id;
        private $_icon_class;

        public function __construct($label, $action_id, $icon_class) {
            $this->_label = $label;
            $this->_action_id = $action_id;
            $this->_icon_class = $icon_class;
        }

        public function render() {
            $template_engine = TemplateEngine::getInstance();

            $template_engine->assign("action_id", $this->_action_id);
            $template_engine->assign("icon_class", $this->_icon_class);
            $template_engine->assign("label", $this->_label);
            return $template_engine->fetch($this->TEMPLATE);
        }

    }

?>
