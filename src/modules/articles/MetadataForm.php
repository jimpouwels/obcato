<?php

namespace Pageflow\Core\modules\articles;

use Pageflow\Core\core\form\Form;
use Pageflow\Core\modules\articles\model\ArticleMetadataField;
use Pageflow\Core\modules\articles\service\ArticleInteractor;
use Pageflow\Core\modules\articles\service\ArticleService;

class MetadataForm extends Form {

    private ArticleService $articleService;
    private ?ArticleMetadataField $currentMetadataField;

    public function __construct(?ArticleMetadataField $metadataField = null) {
        $this->articleService = ArticleInteractor::getInstance();
        $this->currentMetadataField = $metadataField;
    }

    public function loadFields(): void {
        $this->currentMetadataField->setName($this->getMandatoryFieldValue("name"));
        $this->currentMetadataField->setDefaultValue($this->getFieldValue("default_value"));
    }

    public function getMetadataFieldsToDelete(): array {
        $metadataFieldsToDelete = array();
        $metadataFields = $this->articleService->getMetadataFields();
        foreach ($metadataFields as $metadataField) {
            $fieldToCheck = "metadata_field_" . $metadataField->getId() . "_delete";
            if (isset($_POST[$fieldToCheck]) && $_POST[$fieldToCheck] != "") {
                $metadataFieldsToDelete[] = $metadataField;
            }
        }
        return $metadataFieldsToDelete;
    }

}
    