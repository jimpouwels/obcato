<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/core/model/Link.php";

class LinkForm extends Form {

    private Link $_link;

    public function __construct(Link $link) {
        $this->_link = $link;
    }

    public function loadFields(): void {
        $this->_link->setTitle($this->getFieldValue('link_' . $this->_link->getId() . '_title'));
        $this->_link->setTargetAddress($this->getFieldValue('link_' . $this->_link->getId() . '_url'));
        $this->_link->setCode($this->getMandatoryNumber('link_' . $this->_link->getId() . '_code',
            $this->getTextResource('link_code_field_missing'),
            $this->getTextResource('link_code_field_invalid')));
        $this->_link->setTarget($this->getFieldValue('link_' . $this->_link->getId() . '_target'));
        $this->_link->setTargetElementHolderId($this->getNumber('link_element_holder_ref_' . $this->_link->getId(), "Dit is geen getal"));
        if ($this->getFieldValue('delete_link_target') == $this->_link->getId()) {
            $this->_link->setTargetElementHolderId(null);
        }
        if ($this->hasErrors()) {
            throw new FormException('Link form contains errors');
        }
    }

    public function isSelectedForDeletion(): bool {
        return $this->getFieldValue('link_' . $this->_link->getId() . '_delete') != null;
    }
}