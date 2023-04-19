<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "core/model/entity.php";
    require_once CMS_ROOT . "database/dao/element_holder_dao.php";

    class Link extends Entity {
        
        const INTERNAL = "INTERNAL";
        const EXTERNAL = "EXTERNAL";
    
        private string $_title;
        private ?string $_url = null;
        private string $_type;
        private string $_code;
        private ?int $_targetElementHolderId;
        private int $_parentElementHolderId;
        private string $_target;
        private ElementHolderDao $_element_holder_dao;

        public function __construct() {
            $this->_element_holder_dao = ElementHolderDao::getInstance();
        }

        public function getTitle(): string {
            return $this->_title;
        }
        
        public function setTitle(string $title): void {
            $this->_title = $title;
        }
        
        public function getTargetAddress(): ?string {
            return $this->_url;
        }
        
        public function setTargetAddress(?string $url): void {
            $this->_url = $url;
        }
        
        public function getType(): string {
            return $this->_type;
        }
        
        public function setType(string $type): void {
            $this->_type = $type;
        }
        
        public function getTargetElementHolder(): ?ElementHolder {
            $element_holder = null;
            if (!is_null($this->_targetElementHolderId) && $this->_targetElementHolderId != '') {
                $element_holder = $this->getElementHolder($this->_targetElementHolderId);
            }
            return $element_holder;
        }
        
        public function getParentElementHolder(): ElementHolder {
            $element_holder = NULL;
            if (!is_null($this->_parentElementHolderId) && $this->_parentElementHolderId != '') {
                $element_holder = $this->getElementHolder($this->_parentElementHolderId);
            }
            return $element_holder;
        }
        
        public function getTargetElementHolderId(): ?int {
            return $this->_targetElementHolderId;
        }
        
        public function setTargetElementHolderId(?int $target_element_holder_id): void {
            $this->_targetElementHolderId = $target_element_holder_id;
        }
        
        public function getParentElementHolderId(): int {
            return $this->_parentElementHolderId;
        }
        
        public function setParentElementHolderId(int $parent_element_holder_id): void {
            $this->_parentElementHolderId = $parent_element_holder_id;
        }
        
        public function getCode(): string {
            return $this->_code;
        }
        
        public function setCode(string $code): void {
            $this->_code = $code;
        }

        public function getTarget(): string {
            return $this->_target;
        }

        public function setTarget(string $target): void {
            $this->_target = $target;
        }
        
        private function getElementHolder(int $element_holder_id): ElementHolder {
            return $this->_element_holder_dao->getElementHolder($element_holder_id);
        }
        
        public static function constructFromRecord(array $record): Link {
            $link = new Link();
            $link->setId($record['id']);
            $link->setTitle($record['title']);
            $link->setTargetAddress($record['target_address']);
            $link->setType($record['type']);
            $link->setCode($record['code']);
            $link->setTarget($record['target']);
            $link->setParentElementHolderId($record['parent_element_holder']);
            $link->setTargetElementHolderId($record['target_element_holder']);
            
            return $link;
        }
    }
    
?>