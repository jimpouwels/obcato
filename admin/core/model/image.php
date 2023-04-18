<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";
    require_once CMS_ROOT . "database/dao/authorization_dao.php";

    class Image extends Entity {
    
        private string $_title;
        private ?string $_file_name;
        private ?string $_thumbnail_file_name;
        private bool $_published;
        private string $_created_at;
        private int $_created_by_id;
        
        public function setTitle(string $title): void {
            $this->_title = $title;
        }
        
        public function getTitle(): string {
            return $this->_title;
        }
        
        public function setFileName(?string $filename): void {
            $this->_file_name = $filename;
        }
        
        public function getFileName(): ?string {
            return $this->_file_name;
        }
        
        public function setThumbFileName(?string $thumb_filename): void {
            $this->_thumbnail_file_name = $thumb_filename;
        }
        
        public function getThumbUrl(): string {
            $id = $this->getId();
            return "/admin/upload.php?image=$id&amp;thumb=true";
        }
        
        public function getUrl(): string {
            $id = $this->getId();
            return "/admin/upload.php?image=$id";
        }
        
        public function getThumbFileName(): ?string {
            return $this->_thumbnail_file_name;
        }
        
        public function isPublished(): bool {
            return $this->_published;
        }
        
        public function setPublished(bool $published): void {
            $this->_published = $published;
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

        public function getExtension(): string {
            $parts = explode(".", $this->getFileName());
            return $parts[count($parts) - 1];
        }
        
        public static function constructFromRecord(array $record): Image {
            $image = new Image();
            $image->setId($record['id']);
            $image->setTitle($record['title']);
            $image->setPublished($record['published']) == 1 ? true : false;
            $image->setCreatedAt($record['created_at']);
            $image->setCreatedById($record['created_by']);
            $image->setFileName($record['file_name']);
            $image->setThumbFileName($record['thumb_file_name']);
            
            return $image;
        }
    
    }
    
?>