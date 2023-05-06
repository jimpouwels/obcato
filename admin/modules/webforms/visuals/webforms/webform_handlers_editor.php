<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'core/model/webform.php';
    require_once CMS_ROOT . 'view/views/panel.php';
    require_once CMS_ROOT . 'view/views/button.php';
    require_once CMS_ROOT . 'modules/webforms/handlers/email_form_handler.php';
    require_once CMS_ROOT . 'modules/webforms/webform_handler_manager.php';
    require_once CMS_ROOT . 'database/dao/webform_dao.php';

    class HandlersEditor extends Panel {

        private WebFormHandlerManager $_webform_handler_manager;
        private WebForm $_webform;
        private WebFormDao $_webform_dao;

        public function __construct(WebForm $webform) {
            parent::__construct('webforms_handlers_editor_title');
            $this->_webform = $webform;
            $this->_webform_handler_manager = WebFormHandlerManager::getInstance();
            $this->_webform_dao = WebFormDao::getInstance();
        }

        public function getPanelContentTemplate(): string {
            return 'modules/webforms/webforms/handlers_editor.tpl';
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign('handlers_add_buttons', $this->renderHandlerButtons());
            $data->assign('selected_handlers', $this->renderSelectedHandlers());
        }

        private function renderHandlerButtons(): array {
            $handler_buttons = array();
            foreach ($this->_webform_handler_manager->getAllHandlers() as $handler) {
                $handler_button = new Button("webforms_add_{$handler->getType()}_button", "{$handler->getNameResourceIdentifier()}_add_button", "addFormHandler('{$handler->getType()}');");
                $handler_buttons[] = $handler_button->render();
            }
            return $handler_buttons;
        }

        private function renderSelectedHandlers(): array {
            $handlers = array();
            $found_handlers = $this->_webform_dao->getHandlersFor($this->_webform);
            foreach ($found_handlers as $found_handler) {
                $handler_data = array();
                $handler = $this->_webform_handler_manager->getHandler($found_handler['type']);
                $handler_data['id'] = $found_handler['id'];
                $handler_data['type'] = $handler->getType();
                $handler_data['name_resource_identifier'] = $handler->getNameResourceIdentifier();
                $handlers[] = $handler_data;
            }
            return $handlers;
        }
    }

?>