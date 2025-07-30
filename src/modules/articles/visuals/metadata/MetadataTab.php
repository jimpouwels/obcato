<?php
namespace Obcato\Core\modules\articles\visuals\metadata;

use Obcato\Core\modules\articles\model\ArticleMetadataField;
use Obcato\Core\view\views\Visual;

class MetadataTab extends Visual {

    private ?ArticleMetadataField $currentMetadataField;

    public function __construct(?ArticleMetadataField $currentMetadataField) {
        parent::__construct();
        $this->currentMetadataField = $currentMetadataField;
    }

    public function getTemplateFilename(): string {
        return "modules/articles/metadata/root.tpl";
    }

    public function load(): void {
        if ($this->currentMetadataField) {
            $this->assign("metadata_field_editor", $this->renderMetadataFieldEditor());
        }
        $this->assign("metadata_field_list", $this->renderMetadataFieldList());
    }

    private function renderMetadataFieldEditor(): string {
        return (new MetadataFieldEditor($this->currentMetadataField))->render();
    }

    private function renderMetadataFieldList(): string {
        return (new MetadataList())->render();
    }
}