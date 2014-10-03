<?php
	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "/database/dao/element_holder_dao.php";
    require_once CMS_ROOT . "/database/dao/link_dao.php";
    require_once CMS_ROOT . "/view/request_handlers/module_request_handler.php";
	
	abstract class ElementHolderRequestHandler extends ModuleRequestHandler {

		private $_element_dao;
        private $_link_dao;
		private $_element_holder_dao;
		
		public function __construct() {
			$this->_element_dao = ElementDao::getInstance();
            $this->_link_dao = LinkDao::getInstance();
			$this->_element_holder_dao = ElementHolderDao::getInstance();
		}

        function handleGet() {
        }

        function handlePost() {
            $this->updateLinks();
			if ($this->isAddElementAction())
                $this->addElement();
			else if ($this->isDeleteElementAction())
                $this->deleteElement();
            else if ($this->isUpdateElementAction())
                $this->updateElements();
            else if ($this->isAddLinkAction())
                $this->addLink();
		}

        private function updateElements()
        {
            $element_holder = $this->_element_holder_dao->getElementHolder($_POST[EDIT_ELEMENT_HOLDER_ID]);
            foreach ($element_holder->getElements() as $element) {
                $this->updateElement($element);
            }
        }

        private function updateElement($element)
        {
            $element_type = $element->getType();
            // new way of calling request handler for an element
            if ($element_type->getIdentifier() == "text_element" ||
                $element_type->getIdentifier() == "list_element" ||
                $element_type->getIdentifier() == "image_element")
                $element->getRequestHandler()->handle();
            // old way (TODO: Refactor of calling elements request handler)
            else
                include $element_type->getRootDirectory() . "/handler/update_element.php";
        }

        private function addElement()
        {
            $element_type = $this->GetElementTypeToAdd();
            if (!is_null($element_type))
                $this->_element_dao->createElement($element_type, $_POST[EDIT_ELEMENT_HOLDER_ID]);
        }

        private function deleteElement()
        {
            $element_to_delete = $this->_element_dao->getElement($_POST[DELETE_ELEMENT_FORM_ID]);
            if (!is_null($element_to_delete))
                $element_to_delete->delete();
        }

        private function GetElementTypeToAdd() {
            $element_type_to_add = $_POST[ADD_ELEMENT_FORM_ID];
            $element_type = $this->_element_dao->getElementType($element_type_to_add);
            return $element_type;
        }

        private function addLink() {
            $this->_link_dao->createLink($_POST[EDIT_ELEMENT_HOLDER_ID]);
        }

        private function updateLinks() {
            // Updates all links in the element holder
            if (isset($_POST[ACTION_FORM_ID]) && $_POST[ACTION_FORM_ID] == 'update_element_holder' && isset($_POST[EDIT_ELEMENT_HOLDER_ID])) {
                include_once CMS_ROOT . "/database/dao/link_dao.php";
                include_once CMS_ROOT . "/libraries/handlers/form_handler.php";

                $link_dao = LinkDao::getInstance();
                $links = $link_dao->getLinksForElementHolder($_POST[EDIT_ELEMENT_HOLDER_ID]);

                foreach ($links as $link) {
                    if (isset($_POST['link_' . $link->getId() . '_delete'])) {
                        $link_dao->deleteLink($link);
                    } else {
                        if (isset($_POST['link_' . $link->getId() . '_title'])) {
                            $link->setTitle(FormHandler::getFieldValue('link_' . $link->getId() . '_title'));
                        }
                        if (isset($_POST['link_' . $link->getId() . '_url'])) {
                            $link->setTargetAddress(FormHandler::getFieldValue('link_' . $link->getId() . '_url'));
                        }
                        if (isset($_POST['link_' . $link->getId() . '_code'])) {
                            $link->setCode(FormHandler::getFieldValue('link_' . $link->getId() . '_code'));
                        }
                        if (isset($_POST['link_element_holder_ref_' . $link->getId()])) {
                            $link->setTargetElementHolderId(FormHandler::getFieldValue('link_element_holder_ref_' . $link->getId()));
                        }
                        if (isset($_POST['delete_link_target']) && ($_POST['delete_link_target'] == $link->getId())) {
                            $link->setTargetElementHolderId(NULL);
                        }
                    }
                    $link_dao->updateLink($link);
                }
            }
        }

        private function isAddElementAction()
        {
            return isset($_POST[ADD_ELEMENT_FORM_ID]) && $_POST[ADD_ELEMENT_FORM_ID] != "";
        }

        private function isUpdateElementAction()
        {
            return isset($_POST[ACTION_FORM_ID]) && $_POST[ACTION_FORM_ID] == "update_element_holder";
        }

        private function isDeleteElementAction()
        {
            return isset($_POST[DELETE_ELEMENT_FORM_ID]) && $_POST[DELETE_ELEMENT_FORM_ID] != "";
        }

        private function isAddLinkAction() {
            return isset($_POST['action']) && $_POST['action'] == 'add_link';
        }
    }

?>