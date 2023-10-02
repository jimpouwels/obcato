<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/core/model/Link.php";

class LinkForm extends Form {

    private Link $link;

    public function __construct(Link $link) {
        $this->link = $link;
    }

    public function loadFields(): void {
        $this->link->setTitle($this->getFieldValue('link_' . $this->link->getId() . '_title'));
        $this->link->setTargetAddress($this->getFieldValue('link_' . $this->link->getId() . '_url'));
        $this->link->setCode($this->getMandatoryNumber('link_' . $this->link->getId() . '_code',
            $this->getTextResource('link_code_field_missing'),
            $this->getTextResource('link_code_field_invalid')));
        $this->link->setTarget($this->getFieldValue('link_' . $this->link->getId() . '_target'));
        $this->link->setTargetElementHolderId($this->getNumber('link_element_holder_ref_' . $this->link->getId(), "Dit is geen getal"));
        if ($this->getFieldValue('delete_link_target') == $this->link->getId()) {
            $this->link->setTargetElementHolderId(null);
        }
        if ($this->hasErrors()) {
            throw new FormException('Link form contains errors');
        }
    }

    public function isSelectedForDeletion(): bool {
        return $this->getFieldValue('link_' . $this->link->getId() . '_delete') != null;
    }
}