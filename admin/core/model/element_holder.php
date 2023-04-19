<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/presentable.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "database/dao/link_dao.php";
    require_once CMS_ROOT . "database/dao/authorization_dao.php";

    class ElementHolder extends Presentable {
        
        private ElementHolderDao $_element_holder_dao;
        private string $_title;
        private bool $_published;
        private bool $_include_in_search_engine;
        private string $_created_at;
        private int $_created_by_id;
        private string $_type;
        
        public function __construct() {
            $this->_element_holder_dao = ElementHolderDao::getInstance();
        }
        
        public function isPublished(): bool {
            return $this->_published;
        }
        
        public function getTitle(): string {
            return $this->_title;
        }
        
        public function setTitle(string $title): void {
            $this->_title = $title;
        }
        
        public function setPublished(bool $published): void {
            $this->_published = $published;
        }
        
        public function getIncludeInSearchEngine(): bool {
            return $this->_include_in_search_engine;
        }
        
        public function setIncludeInSearchEngine(bool $include_in_search_engine): void {
            $this->_include_in_search_engine = $include_in_search_engine;
        }
        
        public function getElements(): array {
            $dao = ElementDao::getInstance();
            return $dao->getElements($this);
        }
        
        public function getCreatedAt(): string {
            return $this->_created_at;
        }
        
        public function setCreatedAt(string $created_at): void {
            $this->_created_at = $created_at;
        }
        
        public function getCreatedBy(): User {
            $authorization_dao = AuthorizationDao::getInstance();
            return $authorization_dao->getUserById($this->_created_by_id);
        }
        
        public function setCreatedById(int $created_by_id): void {
            $this->_created_by_id = $created_by_id;
        }
        
        public function getType(): string {
            return $this->_type;
        }
        
        public function setType(string $type): void {
            $this->_type = $type;
        }
        
        public function getLinks(): array {
            $link_dao = LinkDao::getInstance();
            return $link_dao->getLinksForElementHolder($this->getId());
        }
        
        public function getElementStatics(): array {
            $element_statics = array();
            foreach ($this->getElements() as $element) {
                $key = $element->getType()->getIdentifier();
                if (!array_key_exists($key, $element_statics)) {
                    $statics = $element->getStatics();
                    if (!is_null($statics)) {
                        $element_statics[$key] = $element->getStatics();
                    }
                }
            }
            return $element_statics;
        }
        
        public function update(): void {
            $this->_element_holder_dao->update($this);
        }
        
        public function delete(): void {
            $this->_element_holder_dao->delete($this);
        }
        
        public static function constructFromRecord(array $record): ElementHolder {
            $element_holder = new ElementHolder();
            $element_holder->setId($record['id']);
            $element_holder->setPublished($record['published'] == 1 ? true : false);
            $element_holder->setTitle($record['title']);
            $element_holder->setTemplateId($record['template_id']);
            $element_holder->setScopeId($record['scope_id']);
            $element_holder->setCreatedAt($record['created_at']);
            $element_holder->setCreatedById($record['created_by']);
            $element_holder->setType($record['type']);
            
            return $element_holder;
        }
    
    }
    
?>