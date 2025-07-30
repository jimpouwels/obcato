<?php

namespace Obcato\Core\modules\articles;

use Obcato\Core\core\form\FormException;
use Obcato\Core\modules\articles\model\ArticleMetadataField;
use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\request_handlers\HttpRequestHandler;

class MetadataRequestHandler extends HttpRequestHandler {

    private ArticleService $articleService;
    private ?ArticleMetadataField $currentMetadataField = null;

    public function __construct() {
        $this->articleService = ArticleInteractor::getInstance();
    }

    public function handleGet(): void {
        $this->currentMetadataField = $this->getMetadataFieldFromGetRequest();
    }

    public function handlePost(): void {
        $this->currentMetadataField = $this->getMetadataFieldFromPostRequest();
        if ($this->isAddAction()) {
            $newField = $this->articleService->createNewArticleMetadataField("new_field");
            $this->redirectTo($this->getBackendBaseUrl() . '&metadata_field=' . $newField->getId());
        } else if ($this->isUpdateAction()) {
            $this->updateMetadataField();
        } else if ($this->isDeleteAction()) {
            $this->deleteMetadataFields();
        }
    }

    public function getCurrentMetadataField(): ?ArticleMetadataField {
        return $this->currentMetadataField;
    }

    private function getMetadataFieldFromGetRequest(): ?ArticleMetadataField {
        if (isset($_GET["metadata_field"])) {
            return $this->articleService->getMetadataField($_GET["metadata_field"]);
        }
        return null;
    }

    private function getMetadataFieldFromPostRequest(): ?ArticleMetadataField {
        if (isset($_POST["metadata_field"]) && $_POST["metadata_field"] != "") {
            return $this->articleService->getMetadataField($_POST["metadata_field"]);
        }
        return null;
    }

    private function deleteMetadataFields(): void {
        $metadataForm = new MetadataForm();
        foreach ($metadataForm->getMetadataFieldsToDelete() as $metadataFieldToDelete) {
            $this->articleService->deleteMetadataField($metadataFieldToDelete);
        }
    }

    private function updateMetadataField(): void {
        try {
            $fieldForm = new MetadataForm($this->currentMetadataField);
            $fieldForm->loadFields();
            $this->articleService->updateMetadataField($this->currentMetadataField);
            $this->sendSuccessMessage($this->getTextResource('article_metadata_fields_saved'));
        } catch (FormException $e) {
            $this->sendErrorMessage($this->getTextResource('article_metadata_fields_not_all_fields_saved'));
        }
    }

    private function isAddAction(): bool {
        return isset($_POST["add_metadata_field_action"]) && $_POST["add_metadata_field_action"] === "add_metadata_field";
    }

    private function isUpdateAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "update_metadata_field";
    }

    private function isDeleteAction(): bool {
        return isset($_POST["metadata_field_delete_action"]) && $_POST["metadata_field_delete_action"] == "delete_metadata_fields";
    }
}