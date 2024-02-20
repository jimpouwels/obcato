<?php

namespace Obcato\Core\admin\modules\downloads\model;

use Obcato\Core\admin\core\form\Form;
use Obcato\Core\admin\core\form\FormException;

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
    