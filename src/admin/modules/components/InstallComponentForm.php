<?php

namespace Obcato\Core\admin\modules\components;

use Obcato\Core\admin\core\form\Form;
use Obcato\Core\admin\core\form\FormException;

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