<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class TemplateVar extends Entity {
    
        private string $_name;
        private ?string $_value = null;

        public function getName(): string {
            return $this->_name;
        }

        public function setName(string $name): void {
            $this->_name = $name;
        }

        public function getValue(): ?string {
            return $this->_value;
        }

        public function setValue(?string $value): void {
            $this->_value = $value;
        }

        public static function constructFromRecord(array $row): TemplateVar {
            $template_var = new TemplateVar();
            $template_var->initFromDb($row);
            return $template_var;
        }

        protected function initFromDb(array $row): void {
            $this->setName($row['name']);
            $this->setValue($row['value']);
            parent::initFromDb($row);
        }
    }