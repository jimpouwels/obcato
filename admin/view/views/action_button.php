<?php
    defined('_ACCESS') or die;

    class ActionButton extends Visual {

        private $TEMPLATE = "system/actions_menu_button.tpl";
        private $_label;
        private $_action_id;
        private $_icon_class;

        public function __construct($label, $action_id, $icon_class) {
            parent::__construct();
            $this->_label = $label;
            $this->_action_id = $action_id;
            $this->_icon_class = $icon_class;
        }

        public function renderVisual(): string {
            $this->getTemplateEngine()->assign("action_id", $this->_action_id);
            $this->getTemplateEngine()->assign("icon_class", $this->_icon_class);
            $this->getTemplateEngine()->assign("label", $this->_label);
            return $this->getTemplateEngine()->fetch($this->TEMPLATE);
        }

    }

?>
