<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/authorization_dao.php";

    class Download extends Entity {

        private $_authorization_dao;
        private $_title;
        private $_file_name;
        private $_published;
        private $_created_at;
        private $_created_by_id;

        public function __construct() {
            $this->_authorization_dao = AuthorizationDao::getInstance();
        }

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
            return $this->_authorization_dao->getUserById($this->_created_by_id);
        }

        public function setCreatedById($created_by_id) {
            $this->_created_by_id = $created_by_id;
        }

        public function getExtension() {
            $parts = explode(".", $this->getFileName());
            return $parts[count($parts) - 1];
        }

        public static function constructFromRecord($record) {
            $download = new Download();
            $download->setId($record['id']);
            $download->setTitle($record['title']);
            $download->setPublished($record['published']);
            $download->setCreatedAt($record['created_at']);
            $download->setCreatedById($record['created_by']);
            $download->setFileName($record['file_name']);
            return $download;
        }
    }