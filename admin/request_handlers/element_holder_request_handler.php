<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/form/element_holder_form.php";
    require_once CMS_ROOT . "database/dao/element_holder_dao.php";
    require_once CMS_ROOT . "database/dao/link_dao.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "core/form/link_form.php";
    require_once CMS_ROOT . "request_handlers/exceptions/element_holder_contains_errors_exception.php";
    require_once CMS_ROOT . "elements/element_contains_errors_exception.php";

    abstract class ElementHolderRequestHandler extends HttpRequestHandler {

        private ElementDao $_element_dao;
        private LinkDao $_link_dao;
        private ElementHolderDao $_element_holder_dao;

        public function __construct() {
            $this->_element_dao = ElementDao::getInstance();
            $this->_link_dao = LinkDao::getInstance();
            $this->_element_holder_dao = ElementHolderDao::getInstance();
        }

        public function handleGet(): void {
        }

        public function handlePost(): void {
            $element_holder = $this->getElementHolderFromPostRequest();
            if ($this->isAddElementAction()) {
                $this->addElement($element_holder);
            } else if ($this->isDeleteElementAction()) {
                $this->deleteElementFrom($element_holder);
            } else if ($this->isAddLinkAction()) {
                $this->addLink($element_holder);
            }
            $this->updateElementHolder($element_holder);
        }

        protected function updateElementHolder(ElementHolder $element_holder): void {
            $form = new ElementHolderForm($element_holder);
            $form->loadFields();
            $this->updateLinks($element_holder);
            foreach ($element_holder->getElements() as $element) {
                try {
                    $element->getRequestHandler()->handle();
                } catch (ElementContainsErrorsException $e) {
                    throw new ElementHolderContainsErrorsException($e->getMessage());
                }
            }
        }

        private function addElement(ElementHolder $element_holder): void {
            $element_type = $this->getElementTypeToAdd();
            if (!is_null($element_type)) {
                $created_element = $this->_element_dao->createElement($element_type, $_POST[EDIT_ELEMENT_HOLDER_ID]);
                $element_holder->addElement($created_element);
            }
        }

        private function deleteElementFrom(ElementHolder $element_holder): void {
            $element_to_delete = $this->_element_dao->getElement($_POST[DELETE_ELEMENT_FORM_ID]);
            if (!is_null($element_to_delete)) {
                $element_to_delete->delete();
                $element_holder->deleteElement($element_to_delete);
            }
        }

        private function getElementTypeToAdd(): ElementType {
            $element_type_to_add = $_POST[ADD_ELEMENT_FORM_ID];
            $element_type = $this->_element_dao->getElementType($element_type_to_add);
            return $element_type;
        }

        private function addLink(ElementHolder $element_holder): void {
            $this->_link_dao->createLink($element_holder->getId(), $this->getTextResource('link_editor_new_link_title'));
        }

        private function updateLinks(ElementHolder $element_holder): void {
            $links = $element_holder->getLinks();
            foreach ($links as $link) {
                $link_form = new LinkForm($link);
                if ($link_form->isSelectedForDeletion()) {
                    $this->_link_dao->deleteLink($link);
                } else {
                    $this->updateLink($link, $link_form);
                }
            }
        }

        private function updateLink(Link $link, LinkForm $link_form): void {
            try {
                $link_form->loadFields();
                $this->_link_dao->updateLink($link);
            } catch (FormException $e) {
                $this->sendErrorMessage($this->getTextResource('link_not_saved_error'));
            }
        }

        private function getElementHolderFromPostRequest(): ?ElementHolder {
            if (!isset($_POST[EDIT_ELEMENT_HOLDER_ID])) return null;
            return $this->_element_holder_dao->getElementHolder($_POST[EDIT_ELEMENT_HOLDER_ID]);
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

?>
