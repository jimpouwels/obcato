<?php

namespace Obcato\Core\admin\core\form;

use Obcato\Core\admin\core\model\Link;

class LinkForm extends Form {

    private Link $link;

    public function __construct(Link $link) {
        $this->link = $link;
    }

    public function loadFields(): void {
        $this->link->setTitle($this->getFieldValue('link_' . $this->link->getId() . '_title'));
        $this->link->setTargetAddress($this->getFieldValue('link_' . $this->link->getId() . '_url'));
        $this->link->setCode($this->getMandatoryNumber('link_' . $this->link->getId() . '_code'));
        $this->link->setTarget($this->getFieldValue('link_' . $this->link->getId() . '_target'));
        $this->link->setTargetElementHolderId($this->getNumber('link_element_holder_ref_' . $this->link->getId()));
        if ($this->getFieldValue('delete_link_target') == $this->link->getId()) {
            $this->link->setTargetElementHolderId(null);
        }
    }

    public function isSelectedForDeletion(): bool {
        return $this->getFieldValue('link_' . $this->link->getId() . '_delete') != null;
    }
}