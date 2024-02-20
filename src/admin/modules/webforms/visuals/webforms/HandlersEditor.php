<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class HandlersEditor extends Panel {

    private WebformHandlerManager $_webform_handler_manager;
    private WebForm $_webform;
    private WebformDao $_webform_dao;

    public function __construct(TemplateEngine $templateEngine, WebForm $webform) {
        parent::__construct($templateEngine, 'webforms_handlers_editor_title');
        $this->_webform = $webform;
        $this->_webform_handler_manager = WebformHandlerManager::getInstance();
        $this->_webform_dao = WebformDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return 'modules/webforms/webforms/handlers_editor.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('handlers_add_buttons', $this->renderHandlerButtons());
        $data->assign('selected_handlers', $this->renderSelectedHandlers());
    }

    private function renderHandlerButtons(): array {
        $handler_buttons = array();
        foreach ($this->_webform_handler_manager->getAllHandlers() as $handler) {
            $handler_button = new Button($this->getTemplateEngine(), "webforms_add_{$handler->getType()}_button", "{$handler->getNameResourceIdentifier()}_add_button", "addFormHandler('{$handler->getType()}');");
            $handler_buttons[] = $handler_button->render();
        }
        return $handler_buttons;
    }

    private function renderSelectedHandlers(): array {
        $handlers = array();
        $found_handler_instances = $this->_webform_dao->getWebFormHandlersFor($this->_webform);
        foreach ($found_handler_instances as $found_handler_instance) {
            $handler_data = array();
            $handler = $this->_webform_handler_manager->getHandler($found_handler_instance->getType());
            $handler_data['id'] = $found_handler_instance->getId();
            $handler_data['type'] = $handler->getType();
            $handler_data['name_resource_identifier'] = $handler->getNameResourceIdentifier();
            $handler_data['properties'] = $this->renderHandlerProperties($handler, $found_handler_instance);
            $handlers[] = $handler_data;
        }
        return $handlers;
    }

    private function renderHandlerProperties(FormHandler $form_handler, WebFormHandlerInstance $webform_handler_instance): array {
        $handler_properties = array();

        foreach ($form_handler->getRequiredProperties() as $property) {
            $handler_property = array();
            $existing_property = $webform_handler_instance->getProperty($property->getName());
            if (!$existing_property) {
                $existing_property = new WebFormHandlerProperty();
                $existing_property->setName($property->getName());
                $existing_property->setType($property->getType());
                $this->_webform_dao->storeProperty($webform_handler_instance->getId(), $existing_property);
            }
            $property_field = null;
            if ($property->getEditor()) {
                $property_field = $property->getEditor();
                $property_field->setCurrentValue($existing_property);
            } else {
                if ($property->getType() == 'textfield') {
                    $property_field = new TextField($this->getTemplateEngine(), "handler_property_{$existing_property->getId()}_field", $existing_property->getName(), $existing_property->getValue(), true, false, null);
                } else {
                    $property_field = new TextArea($this->getTemplateEngine(), "handler_property_{$existing_property->getId()}_field", $existing_property->getName(), $existing_property->getValue(), true, false, null);
                }
            }
            $handler_property['id'] = $existing_property->getId();
            $handler_property['field'] = $property_field->render();
            $handler_properties[] = $handler_property;
        }

        return $handler_properties;
    }
}