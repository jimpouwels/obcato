<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "elements/table_of_contents_element/table_of_contents_element_form.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "elements/element_contains_errors_exception.php";

    class TableOfContentsElementRequestHandler extends HttpRequestHandler {

        private $_table_of_contents_element;
        private $_element_dao;
        private $_table_of_contents_element_form;

        public function __construct($table_of_contents_element) {
            $this->_table_of_contents_element = $table_of_contents_element;
            $this->_element_dao = ElementDao::getInstance();
            $this->_table_of_contents_element_form = new TableOfContentsElementForm($this->_table_of_contents_element);
        }

        public function handleGet() {
        }

        public function handlePost() {
            try {
                $this->_table_of_contents_element_form->loadFields();
                $this->_element_dao->updateElement($this->_table_of_contents_element);
            } catch (FormException $e) {
                throw new ElementContainsErrorsException("Table of contents element contains errors");
            }
        }
    }
?>