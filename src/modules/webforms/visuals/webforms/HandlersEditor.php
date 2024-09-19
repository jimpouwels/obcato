<?php

namespace Obcato\Core\modules\webforms\visuals\webforms;

use Obcato\Core\database\dao\WebformDao;
use Obcato\Core\database\dao\WebformDaoMysql;
use Obcato\Core\modules\webforms\handlers\FormHandler;
use Obcato\Core\modules\webforms\model\Webform;
use Obcato\Core\modules\webforms\model\WebformHandlerInstance;
use Obcato\Core\modules\webforms\model\WebformHandlerProperty;
use Obcato\Core\modules\webforms\WebformHandlerManager;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Button;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\TextArea;
use Obcato\Core\view\views\TextField;

class HandlersEditor extends Panel {

    private WebformHandlerManager $webformHandlerManager;
    private WebForm $webform;
    private WebformDao $webformDao;

    public function __construct(WebForm $webform) {
        parent::__construct('webforms_handlers_editor_title');
        $this->webform = $webform;
        $this->webformHandlerManager = WebformHandlerManager::getInstance();
        $this->webformDao = WebformDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return 'modules/webforms/webforms/handlers_editor.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('handlers_add_buttons', $this->renderHandlerButtons());
        $data->assign('selected_handlers', $this->renderSelectedHandlers());
    }

    private function renderHandlerButtons(): array {
        $handlerButtons = array();
        foreach ($this->webformHandlerManager->getAllHandlers() as $handler) {
            $handlerButton = new Button("webforms_add_{$handler->getType()}_button", "{$handler->getNameResourceIdentifier()}_add_button", "addFormHandler('{$handler->getType()}');");
            $handlerButtons[] = $handlerButton->render();
        }
        return $handlerButtons;
    }

    private function renderSelectedHandlers(): array {
        $handlers = array();
        $foundHandlerInstances = $this->webformDao->getWebFormHandlersFor($this->webform);
        foreach ($foundHandlerInstances as $foundHandlerInstance) {
            $handlerData = array();
            $handler = $this->webformHandlerManager->getHandler($foundHandlerInstance->getType());
            $handlerData['id'] = $foundHandlerInstance->getId();
            $handlerData['type'] = $handler->getType();
            $handlerData['name_resource_identifier'] = $handler->getNameResourceIdentifier();
            $handlerData['properties'] = $this->renderHandlerProperties($handler, $foundHandlerInstance);
            $handlers[] = $handlerData;
        }
        return $handlers;
    }

    private function renderHandlerProperties(FormHandler $form_handler, WebFormHandlerInstance $webformHandlerInstance): array {
        $handlerProperties = array();

        foreach ($form_handler->getRequiredProperties() as $property) {
            $handlerProperty = array();
            $existingProperty = $webformHandlerInstance->getProperty($property->getName());
            if (!$existingProperty) {
                $existingProperty = new WebformHandlerProperty();
                $existingProperty->setName($property->getName());
                $existingProperty->setType($property->getType());
                $this->webformDao->storeProperty($webformHandlerInstance->getId(), $existingProperty);
            }
            $propertyField = null;
            if ($property->getEditor()) {
                $propertyField = $property->getEditor();
                $propertyField->setCurrentValue($existingProperty);
            } else {
                if ($property->getType() == 'textfield') {
                    $propertyField = new TextField("handler_property_{$existingProperty->getId()}_field", $existingProperty->getName(), $existingProperty->getValue(), true, false, null);
                } else {
                    $propertyField = new TextArea("handler_property_{$existingProperty->getId()}_field", $existingProperty->getName(), $existingProperty->getValue(), true, false, null);
                }
            }
            $handlerProperty['id'] = $existingProperty->getId();
            $handlerProperty['field'] = $propertyField->render();
            $handlerProperties[] = $handlerProperty;
        }

        return $handlerProperties;
    }
}