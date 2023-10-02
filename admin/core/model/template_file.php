<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "core/model/entity.php";

class TemplateFIle extends Entity {

    private ?string $_file_name = null;
    private string $_name;
    private array $_template_var_defs = array();

    public static function constructFromRecord(array $row): TemplateFile {
        $template = new TemplateFile();
        $template->initFromDb($row);
        return $template;
    }

    protected function initFromDb(array $row): void {
        $this->setFileName($row['filename']);
        $this->setName($row['name']);
        parent::initFromDb($row);
        $this->setTemplateVarDefs(TemplateDao::getInstance()->getTemplateVarDefs($this));
    }

    public function getTemplateVarDefs(): array {
        return $this->_template_var_defs;
    }

    public function setTemplateVarDefs(array $template_var_defs): void {
        $this->_template_var_defs = $template_var_defs;
    }

    public function getCode(): string {
        $code = "";
        $file_path = FRONTEND_TEMPLATE_DIR . '/' . $this->getFilename();
        if (is_file($file_path) && file_exists($file_path)) {
            $code = file_get_contents($file_path);
        }
        return $code;
    }

    public function getFileName(): ?string {
        return $this->_file_name;
    }

    public function setFileName(?string $file_name): void {
        $this->_file_name = $file_name;
    }

    public function getTemplateVarDef(string $var_name): TemplateVarDef {
        return Arrays::firstMatch($this->_template_var_defs, function ($template_var_def) use ($var_name) {
            return $var_name == $template_var_def->getName();
        });
    }

    public function getName(): string {
        return $this->_name;
    }

    public function setName(string $name): void {
        $this->_name = $name;
    }

    public function addTemplateVarDef(TemplateVarDef $template_var_def): void {
        $this->_template_var_defs[] = $template_var_def;
    }

    public function deleteTemplateVarDef(TemplateVarDef $template_var_def_to_delete): void {
        $this->_template_var_defs = array_filter($this->_template_var_defs, function ($template_var_def) use ($template_var_def_to_delete) {
            return $template_var_def->getId() !== $template_var_def_to_delete->getId();
        });
    }

}