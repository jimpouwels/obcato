<?php

namespace Obcato\Core;

class LabelForm extends Form {

    private ImageLabel $label;
    private ImageDao $imageDao;

    public function __construct(ImageLabel $label) {
        $this->label = $label;
        $this->imageDao = ImageDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $this->label->setName($this->getMandatoryFieldValue("name"));
        if ($this->hasErrors() || $this->labelExists())
            throw new FormException();
    }

    private function labelExists(): bool {
        $existingLabel = $this->imageDao->getLabelByName($this->label->getName());
        if ($existingLabel && $existingLabel->getId() != $this->label->getId()) {
            $this->raiseError("name", "Er bestaat al een label met deze naam");
            return true;
        }
        return false;
    }

}
    