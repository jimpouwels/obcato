<?php

namespace Obcato\Core;

class TemplateVar extends Entity {

    private string $name;
    private ?string $value = null;
    private int $templateId;

    public static function constructFromRecord(array $row): TemplateVar {
        $templateVar = new TemplateVar();
        $templateVar->initFromDb($row);
        return $templateVar;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setValue($row['value']);
        $this->setTemplateId($row['template_id']);
        parent::initFromDb($row);
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getValue(): ?string {
        return $this->value;
    }

    public function setValue(?string $value): void {
        $this->value = $value;
    }

    public function getTemplateId(): int {
        return $this->templateId;
    }

    public function setTemplateId(int $templateId): void {
        $this->templateId = $templateId;
    }
}