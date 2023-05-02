<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/panel.php";

    class WebFormEditor extends Panel {

        public function __construct(?WebForm $current_webform) {
            parent::__construct("webforms_editor_panel_title");
        }

        public function getPanelContentTemplate(): string {
            return "modules/webforms/webforms/editor.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {

        }

    }
?>