<?php

namespace Obcato\Core\modules\articles\visuals\metadata;

use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class MetadataList extends Panel {

    private ArticleService $articleService;

    public function __construct() {
        parent::__construct("Artikel Metadata velden");
        $this->articleService = ArticleInteractor::getInstance();
    }

    function getPanelContentTemplate(): string {
        return "articles/templates/metadata/list.tpl";
    }

    function loadPanelContent(TemplateData $data): void {
        $data->assign("metadata_fields", $this->getMetadataFields());
    }

    private function getMetadataFields(): array {
        $metadataFields = array();
        foreach ($this->articleService->getMetadataFields() as $metadataField) {
            $metadataFields[] = $this->toArray($metadataField);
        }
        return $metadataFields;
    }

    private function toArray($metadataField): array {
        $metadataFieldValue = array();
        $metadataFieldValue["id"] = $metadataField->getId();
        $metadataFieldValue["name"] = $metadataField->getName();
        $metadataFieldValue["default_value"] = $metadataField->getDefaultValue();
        return $metadataFieldValue;
    }
}