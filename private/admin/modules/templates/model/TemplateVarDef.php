<?php
require_once CMS_ROOT . "/core/model/Entity.php";

class TemplateVarDef extends Entity {

    private string $_name;
    private ?string $_default_value = null;

    public static function constructFromRecord(array $row): TemplateVarDef {
        $template_var_def = new TemplateVarDef();
        $template_var_def->initFromDb($row);
        return $template_var_def;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setDefaultValue($row['default_value']);
        parent::initFromDb($row);
    }

    public function getName(): string {
        return $this->_name;
    }

    public function setName(string $name): void {
        $this->_name = $name;
    }

    public function getDefaultValue(): ?string {
        return $this->_default_value;
    }

    public function setDefaultValue(?string $default_value): void {
        $this->_default_value = $default_value;
    }
}