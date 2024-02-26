<?php

namespace Obcato\Core\modules\images;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\modules\images\model\ImageLabel;

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
