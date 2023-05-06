<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/panel.php";

    class HandlersEditor extends Panel {

        public function __construct() {
            parent::__construct('webforms_handlers_editor_title');
        }

        public function getPanelContentTemplate(): string {
            return 'modules/webforms/webforms/handlers_editor.tpl';
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {

        }
    }

?>