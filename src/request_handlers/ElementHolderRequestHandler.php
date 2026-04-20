<?php

namespace Obcato\Core\request_handlers;

use Obcato\Core\core\form\ElementHolderForm;
use Obcato\Core\core\form\FormException;
use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\core\model\ElementType;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\elements\ElementContainsErrorsException;
use Obcato\Core\service\ElementHolderService;
use Obcato\Core\service\ElementHolderInteractor;
use Obcato\Core\request_handlers\exceptions\ElementHolderContainsErrorsException;
use Obcato\Core\request_handlers\exceptions\VersionConflictException;
use const Obcato\core\ADD_ELEMENT_FORM_ID;
use const Obcato\core\DELETE_ELEMENT_FORM_ID;

abstract class ElementHolderRequestHandler extends HttpRequestHandler {

    private ElementDao $elementDao;
    private ElementHolderService $elementHolderService; 

    public function __construct() {
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->elementHolderService = new ElementHolderInteractor();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        if (!$this->getElementHolderFromPostRequest()) {
            return;
        }
        $holder = $this->getElementHolderFromPostRequest();
        $submittedVersion = isset($_POST['element_holder_version']) ? (int)$_POST['element_holder_version'] : null;
        if ($submittedVersion !== null && $submittedVersion !== $holder->getVersion()) {
            throw new VersionConflictException();
        }
        if ($this->isAddElementAction()) {
            $this->addElement($this->getElementHolderFromPostRequest());
            $this->updateElementHolder($this->getElementHolderFromPostRequest());
        } else if ($this->isDeleteElementAction()) {
            $this->deleteElementFrom($this->getElementHolderFromPostRequest());
            $this->updateElementHolder($this->getElementHolderFromPostRequest());
        } else {
            $this->updateElementHolder($this->getElementHolderFromPostRequest());
        }
    }

    protected abstract function getElementHolderFromPostRequest(): ?ElementHolder;

    protected function updateElementHolder(ElementHolder $elementHolder): void {
        $errorThrown = false;
        $form = new ElementHolderForm($elementHolder);
        $form->loadFields();
        foreach ($elementHolder->getElements() as $element) {
            try {
                $element->getRequestHandler()->handle();
            } catch (ElementContainsErrorsException|FormException $e) {
                $errorThrown = true;
            }
        }
        if ($errorThrown) {
            throw new ElementHolderContainsErrorsException();
        }
    }

    private function addElement(ElementHolder $elementHolder): void {
        $elementType = $this->getElementTypeToAdd();
        $createdElement = $this->elementHolderService->addElementToElementHolder($elementType, $elementHolder);
        
        // Handle insert position if specified
        if (isset($_POST['element_insert_position'])) {
            $insertPosition = intval($_POST['element_insert_position']);
            $this->reorderElementsAfterInsert($elementHolder, $createdElement->getId(), $insertPosition);
        }
    }

    // TODO: This function can probably be simplified
    private function reorderElementsAfterInsert(ElementHolder $elementHolder, int $newElementId, int $insertPosition): void {
        $elements = $elementHolder->getElements();
        
        // Check if draggable_order exists (user dragged elements before inserting)
        $baseOrder = array();
        if (isset($_POST['draggable_order']) && !empty($_POST['draggable_order'])) {
            // Use the client's dragged order as the base
            $draggedIds = explode(',', $_POST['draggable_order']);
            foreach ($draggedIds as $id) {
                $id = intval(trim($id));
                if ($id > 0) {
                    $baseOrder[] = $id;
                }
            }
        } else {
            // Use the saved order from database
            foreach ($elements as $element) {
                $baseOrder[] = $element->getId();
            }
        }
        
        // Build new order: insert new element at specified position
        $orderArray = array();
        $insertedNew = false;
        
        foreach ($baseOrder as $elementId) {
            if (count($orderArray) == $insertPosition && !$insertedNew) {
                // Insert new element at this position
                $orderArray[] = $newElementId;
                $insertedNew = true;
            }
            
            // Add existing element (skip the newly created one if it's somehow in baseOrder)
            if ($elementId != $newElementId) {
                $orderArray[] = $elementId;
            }
        }
        
        // If not yet inserted (position at end or after all elements)
        if (!$insertedNew) {
            $orderArray[] = $newElementId;
        }
        
        // Set order numbers based on position in array
        foreach ($elements as $element) {
            $position = array_search($element->getId(), $orderArray);
            if ($position !== false) {
                $element->setOrderNr($position);
            }
        }
    }

    private function deleteElementFrom(ElementHolder $elementHolder): void {
        $elementToDelete = $this->elementDao->getElement($_POST[DELETE_ELEMENT_FORM_ID]);
        if ($elementToDelete) {
            $this->elementDao->deleteElement($elementToDelete);
            $elementHolder->deleteElement($elementToDelete);
        }
    }

    private function getElementTypeToAdd(): ElementType {
        $elementTypeToAdd = $_POST[ADD_ELEMENT_FORM_ID];
        return $this->elementDao->getElementType($elementTypeToAdd);
    }

    private function isAddElementAction(): bool {
        return isset($_POST[ADD_ELEMENT_FORM_ID]) && $_POST[ADD_ELEMENT_FORM_ID] != "";
    }

    private function isDeleteElementAction(): bool {
        return isset($_POST[DELETE_ELEMENT_FORM_ID]) && $_POST[DELETE_ELEMENT_FORM_ID] != "";
    }
}
