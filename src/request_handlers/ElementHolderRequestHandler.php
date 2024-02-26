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
use const Obcato\core\EDIT_ELEMENT_HOLDER_ID;

abstract class ElementHolderRequestHandler extends HttpRequestHandler {

    private ElementDao $_element_dao;
    private LinkDao $linkDao;

    public function __construct() {
        $this->_element_dao = ElementDaoMysql::getInstance();
        $this->linkDao = LinkDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        $elementHolder = $this->loadElementHolderFromPostRequest();
        if (!$elementHolder) {
            return;
        }
        if ($this->isAddElementAction()) {
            $this->addElement($elementHolder);
        } else if ($this->isDeleteElementAction()) {
            $this->deleteElementFrom($elementHolder);
        } else if ($this->isAddLinkAction()) {
            $this->addLink($elementHolder);
        }
        $this->updateElementHolder($elementHolder);
    }

    protected abstract function loadElementHolderFromPostRequest(): ?ElementHolder;

    protected function updateElementHolder(ElementHolder $element_holder): void {
        $form = new ElementHolderForm($element_holder);
        try {
            $form->loadFields();
            $this->updateLinks($element_holder);
            foreach ($element_holder->getElements() as $element) {
                $element->getRequestHandler()->handle();
            }
        } catch (ElementContainsErrorsException|FormException $e) {
            throw new ElementHolderContainsErrorsException($e->getMessage());
        }
    }

    private function addElement(ElementHolder $element_holder): void {
        $element_type = $this->getElementTypeToAdd();
        $createdElement = $this->_element_dao->createElement($element_type, $_POST[EDIT_ELEMENT_HOLDER_ID]);
        $element_holder->addElement($createdElement);
    }

    private function deleteElementFrom(ElementHolder $element_holder): void {
        $elementToDelete = $this->_element_dao->getElement($_POST[DELETE_ELEMENT_FORM_ID]);
        if ($elementToDelete) {
            $this->_element_dao->deleteElement($elementToDelete);
            $element_holder->deleteElement($elementToDelete);
        }
    }

    private function getElementTypeToAdd(): ElementType {
        $element_type_to_add = $_POST[ADD_ELEMENT_FORM_ID];
        return $this->_element_dao->getElementType($element_type_to_add);
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
