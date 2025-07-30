<?php

namespace Obcato\Core\modules\articles\model;

use Obcato\Core\core\model\Entity;

class ArticleMetadataFieldValue extends Entity {

    private ?string $value;
    private int $metadataFieldId;
    private int $articleId;

    public function setValue(?string $value): void {
        $this->value = $value;
    }

    public function getValue(): ?string {
        return $this->value;
    }

    public function setMetadataFieldId(int $metadataFieldId): void {
        $this->metadataFieldId = $metadataFieldId;
    }

    public function getMetadataFieldId(): int {
        return $this->metadataFieldId;
    }

    public function setArticleId(int $articleId): void {
        $this->articleId = $articleId;
    }

    public function getArticleId(): int {
        return $this->articleId;
    }

    public static function constructFromRecord($row): ArticleMetadataFieldValue {
        $fieldValue = new ArticleMetadataFieldValue();
        $fieldValue->initFromDb($row);
        return $fieldValue;
    }

    protected function initFromDb(array $row): void {
        $this->setValue($row['value']);
        $this->setMetadataFieldId($row['metadata_field_id']);
        $this->setArticleId($row['article_id']);
        parent::initFromDb($row);
    }
}