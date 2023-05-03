<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/panel.php";

    class WebFormEditor extends Panel {

        private WebForm $_current_webform;

        public function __construct(?WebForm $current_webform) {
            parent::__construct("webforms_webform_editor_panel_title");
            $this->_current_webform = $current_webform;
        }

        public function getPanelContentTemplate(): string {
            return "modules/webforms/webforms/webform_editor.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
        }

    }
?>