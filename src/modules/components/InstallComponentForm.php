<?php

namespace Pageflow\Core\modules\components;

use Pageflow\Core\core\form\Form;
use Pageflow\Core\core\form\FormException;

class InstallComponentForm extends Form {

    private $filePath;
    private $fileName;

    public function loadFields(): void {
        $this->filePath = $this->getMandatoryUploadFilePath('upload_field');
        $this->fileName = $this->getUploadedFileName('upload_field');
        if ($this->hasErrors())
            throw new FormException($this->getError('upload_field'));
    }

    public function getFilePath() {
        return $this->filePath;
    }

    public function getFileName() {
        return $this->fileName;
    }
}