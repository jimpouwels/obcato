<?php

namespace Obcato\Core\modules\articles\visuals\metadata;

use Obcato\Core\modules\articles\model\ArticleMetadataField;
use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class MetadataList extends Panel {

    private ArticleService $articleService;
    private ?ArticleMetadataField $currentMetadataField;

    public function __construct(?ArticleMetadataField $currentMetadataField = null) {
        parent::__construct("Artikel Metadata velden", 'metadata_list');
        $this->articleService = ArticleInteractor::getInstance();
        $this->currentMetadataField = $currentMetadataField;
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
        $metadataFieldValue["is_active"] = $this->currentMetadataField && $this->currentMetadataField->getId() === $metadataField->getId();
        return $metadataFieldValue;
    }
}