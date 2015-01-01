<?php

    
    defined('_ACCESS') or die;

    include_once CMS_ROOT . "core/model/entity.php";

    class ListItem extends Entity {
            
        private $_text;
        private $_indent;
        private $_elementId;
        
        public function setText($text) {
            $this->_text = $text;
        }
        
        public function getText() {
            return $this->_text;
        }
        
        public function setIndent($indent) {
            $this->_indent = $indent;
        }
        
        public function getIndent() {
            return $this->_indent;
        }
        
        public function getElementId() {
            return $this->_elementId;
        }
        
        public function setElementId($element_id) {
            $this->_elementId = $element_id;
        }
        
        public function getElement() {
            include_once CMS_ROOT . "dao/element_dao.php";
            $element_dao = ElementDao::getInstance();
            return $element_dao->getElement($this->_elementId);
        }
        
    }
?>