<?php

namespace Obcato\Core\request_handlers;

use Obcato\Core\core\form\ElementHolderForm;
use Obcato\Core\core\form\FormException;
use Obcato\Core\core\form\LinkForm;
use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\core\model\ElementType;
use Obcato\Core\core\model\Link;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\database\dao\LinkDao;
use Obcato\Core\database\dao\LinkDaoMysql;
use Obcato\Core\elements\ElementContainsErrorsException;
use Obcato\Core\request_handlers\exceptions\ElementHolderContainsErrorsException;
use const Obcato\core\ADD_ELEMENT_FORM_ID;
use const Obcato\core\DELETE_ELEMENT_FORM_ID;

abstract class ElementHolderRequestHandler extends HttpRequestHandler {

    private ElementDao $elementDao;
    private LinkDao $linkDao;

    public function __construct() {
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->linkDao = LinkDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        if (!$this->getElementHolderFromPostRequest()) {
            return;
        }
        if ($this->isAddElementAction()) {
            $this->addElement($this->getElementHolderFromPostRequest());
            $this->updateElementHolder($this->getElementHolderFromPostRequest());
        } else if ($this->isDeleteElementAction()) {
            $this->deleteElementFrom($this->getElementHolderFromPostRequest());
        } else if ($this->isAddLinkAction()) {
            $this->addLink($this->getElementHolderFromPostRequest());
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
        $this->updateLinks($elementHolder);
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
        $createdElement = $this->elementDao->createElement($elementType, $elementHolder->getId());
        $createdElement->setElementHolderId($elementHolder->getId());
        $elementHolder->addElement($createdElement);
    }

    private function deleteElementFrom(ElementHolder $elementHolder): void {
        $elementToDelete = $this->elementDao->getElement($_POST[DELETE_ELEMENT_FORM_ID]);
        if ($elementToDelete) {
            $this->elementDao->deleteElement($elementToDelete);
            $elementHolder->deleteElement($elementToDelete);
        }
    }

    private function getElementTypeToAdd(): ElementType {
        $element_type_to_add = $_POST[ADD_ELEMENT_FORM_ID];
        return $this->elementDao->getElementType($element_type_to_add);
    }

    private function addLink(ElementHolder $elementHolder): void {
        $link = $this->linkDao->createLink($elementHolder->getId(), $this->getTextResource('link_editor_new_link_title'));
        $elementHolder->addLink($link);
    }

    private function updateLinks(ElementHolder $elementHolder): void {
        $links = $elementHolder->getLinks();
        foreach ($links as $link) {
            $linkForm = new LinkForm($link);
            if ($linkForm->isSelectedForDeletion()) {
                $this->linkDao->deleteLink($link);
                $elementHolder->deleteLink($link);
            } else {
                $this->updateLink($link, $linkForm);
            }
        }
    }

    private function updateLink(Link $link, LinkForm $link_form): void {
        $link_form->loadFields();
        $this->linkDao->updateLink($link);
    }

    private function isAddElementAction(): bool {
        return isset($_POST[ADD_ELEMENT_FORM_ID]) && $_POST[ADD_ELEMENT_FORM_ID] != "";
    }

    private function isDeleteElementAction(): bool {
        return isset($_POST[DELETE_ELEMENT_FORM_ID]) && $_POST[DELETE_ELEMENT_FORM_ID] != "";
    }

    private function isAddLinkAction(): bool {
        return isset($_POST['action']) && $_POST['action'] == 'add_link';
    }
}
