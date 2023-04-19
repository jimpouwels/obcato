<?php
    defined('_ACCESS') or die;

    class ActionButton extends Visual {

        private static string $TEMPLATE = "system/actions_menu_button.tpl";
        private string $_label;
        private string $_action_id;
        private string $_icon_class;

        public function __construct(string $label, string $action_id, string $icon_class) {
            parent::__construct();
            $this->_label = $label;
            $this->_action_id = $action_id;
            $this->_icon_class = $icon_class;
        }

        public function render(): string {
            $this->getTemplateEngine()->assign("action_id", $this->_action_id);
            $this->getTemplateEngine()->assign("icon_class", $this->_icon_class);
            $this->getTemplateEngine()->assign("label", $this->_label);
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }

    }

?>
