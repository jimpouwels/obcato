<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'core/model/webform.php';
    require_once CMS_ROOT . 'view/views/panel.php';
    require_once CMS_ROOT . 'view/views/button.php';
    require_once CMS_ROOT . 'view/views/form_textfield.php';
    require_once CMS_ROOT . 'view/views/form_textarea.php';
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
                $found_handler_id = $found_handler['id'];
                $handler_data['id'] = $found_handler_id;
                $handler_data['type'] = $handler->getType();
                $handler_data['name_resource_identifier'] = $handler->getNameResourceIdentifier();
                $handler_data['properties'] = $this->renderHandlerProperties($found_handler_id, $handler);
                $handlers[] = $handler_data;
            }
            return $handlers;
        }

        private function renderHandlerProperties(int $found_handler_id, FormHandler $form_handler): array {
            $handler_properties = array();

            $existing_properties = $this->_webform_dao->getPropertiesFor($found_handler_id);
            foreach ($form_handler->getRequiredProperties() as $property) {
                $handler_property = array();
                $existing_property = $this->findPropertyIn($existing_properties, $property);
                if (!$existing_property) {
                    $existing_property = $this->_webform_dao->storeProperty($found_handler_id, $property);
                }
                $property_field = null;
                if ($property['type'] == 'textfield') {
                    $property_field = new TextField('property_' . $existing_property['id'] . '_field', $existing_property['name'], $existing_property['value'], true, false, null);
                } else {
                    $property_field = new TextArea('property_' . $existing_property['id'] . '_field', $existing_property['name'], $existing_property['value'], true, false, null);
                }
                $handler_property['id'] = $existing_property['id'];
                $handler_property['field'] = $property_field->render();
                $handler_properties[] = $handler_property;
            }

            return $handler_properties;
        }

        private function findPropertyIn(array $properties, array $property_to_find): ?array {
            foreach ($properties as $property) {
                if ($property['name'] == $property_to_find['name']) {
                    return $property;
                }
            }
            return null;
        }
    }

?>