<?php
    // No direct access
    defined('_ACCESS') or die;

    require_once "pre_handlers/pre_handler.php";
    require_once "database/dao/element_dao.php";
    require_once "elements/text_element/text_element_form.php";

    class TextElementPreHandler extends PreHandler {

        private $_text_element;
        private $_element_dao;
        private $_text_element_form;

        public function __construct($text_element) {
            $this->_text_element = $text_element;
            $this->_element_dao = ElementDao::getInstance();
            $this->_text_element_form = new TextElementForm($this->_text_element);
        }

        public function handle()
        {
            $this->_text_element_form->loadFields();
            $this->_element_dao->updateElement($this->_text_element);
        }
    }
?>