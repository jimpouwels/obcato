<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";
    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class Template extends Entity {
    
        private ?string $_file_name = null;
        private string $_scope;
        private string $_name;
        private int $_scope_id;
        
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
        
        public static function constructFromRecord(array $record): Template {
            $template = new Template();
            $template->setId($record['id']);
            $template->setFileName($record['filename']);
            $template->setName($record['name']);
            $template->setScopeId($record['scope_id']);
            
            return $template;
        }
    
    }
    
?>