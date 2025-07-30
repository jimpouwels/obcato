<?php

namespace Obcato\Core\modules\articles\model;

use Obcato\Core\core\model\Entity;

class ArticleMetadataField extends Entity {

    private string $name;
    private ?string $defaultValue;

    public function setName($name): void {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setDefaultValue(?string $defaultValue): void {
        $this->defaultValue = $defaultValue;
    }

    public function getDefaultValue(): ?string {
        return $this->defaultValue;
    }

    public static function constructFromRecord($row): ArticleMetadataField {
        $field = new ArticleMetadataField();
        $field->initFromDb($row);
        return $field;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setDefaultValue($row['default_value']);
        parent::initFromDb($row);
    }
}