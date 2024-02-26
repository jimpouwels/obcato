<?php

namespace Obcato\Core\modules\downloads\model;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;

class DownloadForm extends Form {

    private Download $download;

    public function __construct(Download $download) {
        $this->download = $download;
    }

    public function loadFields(): void {
        $this->download->setTitle($this->getMandatoryFieldValue("download_title"));
        $this->download->setPublished($this->getCheckboxValue("download_published"));
        if ($this->hasErrors())
            throw new FormException();
    }

    public function getUploadPath(): string {
        return $this->getUploadFilePath('download_file');
    }

    public function getUploadFileName(): string {
        return $this->getUploadedFileName('download_file');
    }

}
    