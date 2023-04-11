<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/form/form.php";
    
    class LinkForm extends Form {

        private $_link;

        public function __construct($link) {
            $this->_link = $link;
        }

        public function loadFields(): void {
            $this->_link->setTitle($this->getFieldValue('link_' . $this->_link->getId() . '_title'));
            $this->_link->setTargetAddress($this->getFieldValue('link_' . $this->_link->getId() . '_url'));
            $this->_link->setCode($this->getMandatoryNumber('link_' . $this->_link->getId() . '_code',
                                                            $this->getTextResource('link_code_field_missing'),
                                                            $this->getTextResource('link_code_field_invalid')));
            $this->_link->setTarget($this->getFieldValue('link_' . $this->_link->getId() . '_target'));
            $this->_link->setTargetElementHolderId($this->getFieldValue('link_element_holder_ref_' . $this->_link->getId()));
            if ($this->getFieldValue('delete_link_target') == $this->_link->getId())
                $this->_link->setTargetElementHolderId(null);
            if ($this->hasErrors()) {
                throw new FormException('Link form contains errors');
            }
        }

        public function isSelectedForDeletion() {
            return $this->getFieldValue('link_' . $this->_link->getId() . '_delete');
        }
    }