<?php
	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "/database/dao/element_holder_dao.php";
    require_once CMS_ROOT . "/view/request_handlers/module_request_handler.php";
	
	abstract class ElementHolderRequestHandler extends ModuleRequestHandler {

		private $_element_dao;
		private $_element_holder_dao;
		
		public function __construct() {
			$this->_element_dao = ElementDao::getInstance();
			$this->_element_holder_dao = ElementHolderDao::getInstance();
		}

        function handleGet() {
        }

        function handlePost() {
			if ($this->isAddElementAction())
                $this->addElement();
			if ($this->isDeleteElementAction())
                $this->deleteElement();
			if ($this->isUpdateElementAction())
                $this->updateElements();
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
            if ($element_type->getIdentifier() == "text_element" || $element_type->getIdentifier() == "list_element")
                $element->getRequestHandler()->handle();
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

        private function isAddElementAction()
        {
            return $_POST[ADD_ELEMENT_FORM_ID] != "";
        }

        private function isUpdateElementAction()
        {
            return $_POST[ACTION_FORM_ID] == "update_element_holder";
        }

        private function isDeleteElementAction()
        {
            return $_POST[DELETE_ELEMENT_FORM_ID] != "";
        }
    }

?>