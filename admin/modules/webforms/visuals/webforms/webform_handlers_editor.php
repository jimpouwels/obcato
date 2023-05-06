<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'core/model/webform.php';
    require_once CMS_ROOT . 'view/views/panel.php';
    require_once CMS_ROOT . 'view/views/button.php';
    require_once CMS_ROOT . 'modules/webforms/handlers/email_form_handler.php';

    class HandlersEditor extends Panel {

        private array $_all_handlers = array();
        private WebForm $_webform;

        public function __construct(WebForm $webform) {
            parent::__construct('webforms_handlers_editor_title');
            $this->_webform = $webform;
            $this->_all_handlers[] = new EmailFormHandler($webform);
        }

        public function getPanelContentTemplate(): string {
            return 'modules/webforms/webforms/handlers_editor.tpl';
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign('handlers_add_buttons', $this->renderHandlerButtons());
        }

        private function renderHandlerButtons(): array {
            $handler_buttons = array();
            foreach ($this->_all_handlers as $handler) {
                $handler_button = new Button("webforms_add_{$handler->getType()}_button", "{$handler->getNameResourceIdentifier()}_add_button", "addFormHandler('{$handler->getType()}');");
                $handler_buttons[] = $handler_button->render();
            }
            return $handler_buttons;
        }
    }

?>