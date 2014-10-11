<?php

	
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "core/data/entity.php";
	include_once CMS_ROOT . "database/dao/authorization_dao.php";

	class Image extends Entity {
	
		private $_title;
		private $_file_name;
		private $_thumbnail_file_name;
		private $_published;
		private $_created_at;
		private $_created_by_id;
		
		public function setTitle($title) {
			$this->_title = $title;
		}
		
		public function getTitle() {
			return $this->_title;
		}
		
		public function setFileName($filename) {
			$this->_file_name = $filename;
		}
		
		public function getFileName() {
			return $this->_file_name;
		}
		
		public function setThumbFileName($thumb_filename) {
			$this->_thumbnail_file_name = $thumb_filename;
		}
		
		public function getThumbUrl() {
			$id = $this->getId();
			return "/admin/upload.php?image=$id&amp;thumb=true";
		}
		
		public function getUrl() {
			$id = $this->getId();
			return "/admin/upload.php?image=$id";
		}
		
		public function getThumbFileName() {
			return $this->_thumbnail_file_name;
		}
		
		public function isPublished() {
			return $this->_published;
		}
		
		public function setPublished($published) {
			$this->_published = $published;
		}
		
		public function getCreatedAt() {
			return $this->_created_at;
		}
		
		public function setCreatedAt($created_at) {
			$this->_created_at = $created_at;
		}
		
		public function getCreatedBy() {
			$authorization_dao = AuthorizationDao::getInstance();
			return $authorization_dao->getUserById($this->_created_by_id);
		}
		
		public function setCreatedById($created_by_id) {
			$this->_created_by_id = $created_by_id;
		}

        public function getExtension() {
            $parts = explode(".", $this->getFileName());
            return $parts[count($parts) - 1];
        }
		
		public static function constructFromRecord($record) {
			$image = new Image();
			$image->setId($record['id']);
			$image->setTitle($record['title']);
			$image->setPublished($record['published']);
			$image->setCreatedAt($record['created_at']);
			$image->setCreatedById($record['created_by']);
			$image->setFileName($record['file_name']);
			$image->setThumbFileName($record['thumb_file_name']);
			
			return $image;
		}
	
	}
	
?>