<?php

namespace Obcato\Core\modules\templates\model;

use Obcato\Core\core\model\Entity;

class TemplateVarDef extends Entity {

    private string $name;
    private ?string $defaultValue = null;

    public static function constructFromRecord(array $row): TemplateVarDef {
        $templateVarDef = new TemplateVarDef();
        $templateVarDef->initFromDb($row);
        return $templateVarDef;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setDefaultValue($row['default_value']);
        parent::initFromDb($row);
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getDefaultValue(): ?string {
        return $this->defaultValue;
    }

    public function setDefaultValue(?string $defaultValue): void {
        $this->defaultValue = $defaultValue;
    }
}