<?php

namespace Obcato\Core\admin\modules\images;

use Obcato\Core\admin\core\form\Form;
use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\database\dao\ImageDao;
use Obcato\Core\admin\database\dao\ImageDaoMysql;
use Obcato\Core\admin\modules\images\model\ImageLabel;

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
