<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";
    require_once CMS_ROOT . "database/dao/scope_dao.php";
    require_once CMS_ROOT . "database/dao/template_dao.php";

    class Template extends Entity {
    
        private ?string $_file_name = null;
        private string $_scope;
        private string $_name;
        private int $_scope_id;
        private array $_template_vars = array();
        
        public function setName(string $name): void {
            $this->_name = $name;
        }
        
        public function getName(): string {
            return $this->_name;
        }
        
        public function setFileName(?string $file_name): void {
            $this->_file_name = $file_name;
        }
        
        public function getFileName(): ?string {
            return $this->_file_name;
        }
        
        public function getScope(): Scope {
            $dao = ScopeDao::getInstance();
            return $dao->getScope($this->_scope_id);
        }
        
        public function getScopeId(): int {
            return $this->_scope_id;
        }
        
        public function setScopeId(int $scope_id): void {
            $this->_scope_id = $scope_id;
        }
        
        public function exists(): bool {
            $template_dir = Settings::find()->getFrontendTemplateDir();
            return file_exists($template_dir . "/" . $this->getFileName()) && $this->getFileName() != "";
        }

        public function getTemplateVars(): array {
            return $this->_template_vars;
        }

        public function setTemplateVars(array $template_vars): void {
            $this->_template_vars = $template_vars;
        }
        
        public function getCode(): string {
            $code = "";
            $file_path = FRONTEND_TEMPLATE_DIR . '/' . $this->getFilename();
            if (is_file($file_path) && file_exists($file_path)) {
                $code = file_get_contents($file_path);
            }
            return $code;
        }
        
        public static function constructFromRecord(array $row): Template {
            $template = new Template();
            $template->initFromDb($row);
            return $template;
        }

        protected function initFromDb(array $row): void {
            $this->setFileName($row['filename']);
            $this->setName($row['name']);
            $this->setScopeId($row['scope_id']);
            parent::initFromDb($row);
            $this->setTemplateVars(TemplateDao::getInstance()->getTemplateVars($this));
        }
    
    }
    
?>