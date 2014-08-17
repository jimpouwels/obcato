<?php
	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "database/dao/element_holder_dao.php";
	require_once FRONTEND_REQUEST . "pre_handlers/pre_handler.php";
	
	class ElementPreHandler extends PreHandler {
	
		private $_element_dao;
		private $_element_holder_dao;
		
		public function __construct() {
			$this->_element_dao = ElementDao::getInstance();
			$this->_element_holder_dao = ElementHolderDao::getInstance();
		}
	
		public function handle() {
			// Adds an element to the current element holder
			if (isset($_POST[ADD_ELEMENT_FORM_ID]) && $_POST[ADD_ELEMENT_FORM_ID] != '' && isset($_POST[EDIT_ELEMENT_HOLDER_ID])) {
				// first obtain the element holder
				$element_holder_id = $_POST[EDIT_ELEMENT_HOLDER_ID];
				$element_type_to_add = $_POST[ADD_ELEMENT_FORM_ID];
				
				$element_type = $this->_element_dao->getElementType($element_type_to_add);
				if (!is_null($element_type)) {
					$element = $this->_element_dao->createElement($element_type, $element_holder_id);
				}
			}
			
			// Check if we need to delete an element
			if (isset($_POST[DELETE_ELEMENT_FORM_ID]) && $_POST[DELETE_ELEMENT_FORM_ID] != "") {
				$element_to_delete = $this->_element_dao->getElement($_POST[DELETE_ELEMENT_FORM_ID]);
				if (!is_null($element_to_delete)) {
					$element_to_delete->delete();
				}
			}
			
			// Updates all elements in the element holder
			if (isset($_POST[ACTION_FORM_ID]) && $_POST[ACTION_FORM_ID] == 'update_element_holder' && isset($_POST[EDIT_ELEMENT_HOLDER_ID])) {
				// first obtain the element holder
				$element_holder_id = $_POST[EDIT_ELEMENT_HOLDER_ID];
				$element_holder = $this->_element_holder_dao->getElementHolder($element_holder_id);
				if (!is_null($element_holder)) {
					foreach ($element_holder->getElements() as $element) {
						$element_type = $element->getType();
                        if ($element_type->getIdentifier() == 'text_element') {
                            include $element_type->getRootDirectory() . "/" . $element_type->getIdentifier() . "_pre_handler.php";
                            $pre_handler_class_name = $element_type->getClassName() . "PreHandler";
                            $element_pre_handler = new $pre_handler_class_name($element);
                            $element_pre_handler->handle();
                        } else {
						    include $element_type->getRootDirectory() . "/handler/update_element.php";
                        }
					}
				}
			}
		}
		
	}

?>