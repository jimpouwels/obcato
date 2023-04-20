<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "elements/list_element/list_element_form.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";

    class ListElementRequestHandler extends HttpRequestHandler {

        private ListElement $_list_element;
        private ListElementForm $_list_element_form;
        private ElementDao $_element_dao;

        public function __construct($list_element) {
            $this->_list_element = $list_element;
            $this->_list_element_form = new ListElementForm($this->_list_element);
            $this->_element_dao = ElementDao::getInstance();
        }

        public function handleGet(): void {
        }

        public function handlePost(): void {
            $this->_list_element_form->loadFields();
            foreach ($this->_list_element_form->getListItemsToDelete() as $list_item_to_delete) {
                $this->_list_element->deleteListItem($list_item_to_delete);
            }
            if ($this->isAddListItemAction()) {
                $this->_list_element->addListItem();
            }
            $this->_element_dao->updateElement($this->_list_element);
        }

        private function isAddListItemAction(): bool
        {
            return !is_null($this->_list_element) && isset($_POST['element' . $this->_list_element->getId() . '_add_item']) &&
                $_POST['element' . $this->_list_element->getId() . '_add_item'] != '';
        }
    }
?>