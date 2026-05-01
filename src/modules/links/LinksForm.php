<?php

namespace Pageflow\Core\modules\links;

use Pageflow\Core\core\form\Form;
use Pageflow\Core\core\form\FormException;
use Pageflow\Core\modules\links\model\ReusableLink;

class LinksForm extends Form {

    private ReusableLink $link;

    public function __construct(ReusableLink $link) {
        $this->link = $link;
    }

    public function loadFields(): void {
        $name = trim($this->getMandatoryFieldValue("name"));
        $title = trim($this->getFieldValue("title"));
        $url = trim($this->getFieldValue("url"));

        if ($name === '') {
            throw new FormException($this->getTextResource('links_editor_name_required_error'));
        }

        $this->link->setName($name);
        $this->link->setTitle($title);
        $this->link->setUrl($url);
        $this->link->setFolderId($this->getFolderId() ?? $this->link->getFolderId());
    }

    private function getFolderId(): ?int {
        $folderIdRaw = $this->getNumber("folder_id");
        if ($folderIdRaw === '') {
            return null;
        }
        return $folderIdRaw;
    }

}