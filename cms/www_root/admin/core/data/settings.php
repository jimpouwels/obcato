<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "core/data/entity.php";
	require_once FRONTEND_REQUEST . "database/mysql_connector.php";
	require_once FRONTEND_REQUEST . "database/dao/page_dao.php";

	class Settings extends Entity {
		
		private $_website_title;
		private $_frontend_hostname;
		private $_backend_hostname;
		private $_email_address;
		private $_smtp_host;
		private $_root_dir;
		private $_frontend_template_dir;
		private $_static_dir;
		private $_config_dir;
		private $_upload_dir;
		private $_component_dir;
		private $_backend_template_dir;
		private $_database_version;
		
		public function setWebsiteTitle($website_title) {
			$this->_website_title = $website_title;
		}
		
		public function getWebsiteTitle() {
			return $this->_website_title;
		}
		
		public function setFrontEndHostname($frontend_hostname) {
			$this->_frontend_hostname = $frontend_hostname;
		}
		
		public function getFrontEndHostname() {
			return $this->_frontend_hostname;
		}
		
		public function setBackEndHostname($backend_hostname) {
			$this->_backend_hostname = $backend_hostname;
		}
		
		public function getBackEndHostname() {
			return $this->_backend_hostname;
		}
				
		public function setEmailAddress($email_address) {
			$this->_email_address = $email_address;
		}
		
		public function getEmailAddress() {
			return $this->_email_address;
		}
				
		public function setSmtpHost($smtp_host) {
			$this->_smtp_host = $smtp_host;
		}
		
		public function getSmtpHost() {
			return $this->_smtp_host;
		}
				
		public function setRootDir($root_dir) {
			$this->_root_dir = $root_dir;
		}
		
		public function getRootDir() {
			return $this->_root_dir;
		}
				
		public function setFrontendTemplateDir($frontend_template_dir) {
			$this->_frontend_template_dir = $frontend_template_dir;
		}
		
		public function getFrontendTemplateDir() {
			return $this->_frontend_template_dir;
		}
				
		public function setStaticDir($static_dir) {
			$this->_static_dir = $static_dir;
		}
		
		public function getStaticDir() {
			return $this->_static_dir;
		}
		
		public function setConfigDir($config_dir) {
			$this->_config_dir = $config_dir;
		}
		
		public function getConfigDir() {
			return $this->_config_dir;
		}
		
		public function setUploadDir($upload_dir) {
			$this->_upload_dir = $upload_dir;
		}	
		
		public function getUploadDir() {
			return $this->_upload_dir;
		}
		
		public function setComponentDir($component_dir) {
			$this->_component_dir = $component_dir;
		}	
		
		public function getComponentDir() {
			return $this->_component_dir;
		}
		
		public function setDatabaseVersion($database_version) {
			$this->_database_version = $database_version;
		}
		
		public function getDatabaseVersion() {
			return $this->_database_version;
		}
		
		public function setBackendTemplateDir($backend_template_dir) {
			$this->_backend_template_dir = $backend_template_dir;
		}
		
		public function getBackendTemplateDir() {
			return $this->_backend_template_dir;
		}
		
		public function getHomepage() {
			$mysql_database = MysqlConnector::getInstance();
			$query = "SELECT element_holder_id FROM pages WHERE is_homepage = 1";
			$result = $mysql_database->executeSelectQuery($query);
			
			$homepage = NULL;
			$homepage_id = NULL;
			while ($row = mysql_fetch_assoc($result)) {
				$homepage_id = $row['element_holder_id'];
			}
		
			if (!is_null($homepage_id)) {
				$homepage = PageDao::getInstance()->getPage($homepage_id);
			}
			
			return $homepage;
		}
		
		public function persist() {
		}
		
		public function update() {
			$mysql_database = MysqlConnector::getInstance();
			$query = "UPDATE settings SET website_title = '" . $this->getWebsiteTitle() . "', backend_hostname = '" . 
					 $this->getBackEndHostname() . "', frontend_hostname = '" . $this->getFrontEndHostname() . "',
					 smtp_host = '" . $this->getSmtpHost() . "', email_address = '" . $this->getEmailAddress() . "',
					 frontend_template_dir = '" . $this->getFrontendTemplateDir() . "', root_dir = '" . $this->getRootDir() . "',
					 static_files_dir = '" . $this->getStaticDir() . "', config_dir = '" . $this->getConfigDir() . "',
					 upload_dir = '" . $this->getUploadDir() . "', database_version = '" . $this->getDatabaseVersion() . "',
					 component_dir = '" . $this->getComponentDir() . "', backend_template_dir = '" . $this->getBackendTemplateDir() . "'";
			$mysql_database->executeQuery($query);
		}
		
		public static function find() {
			$mysql_database = MysqlConnector::getInstance(); 
			$query = "SELECT * FROM settings";
			$result = $mysql_database->executeSelectQuery($query);
			$settings = null;
			while ($row = mysql_fetch_assoc($result)) {
				$settings = self::constructFromRecord($row);
			}
			
			return $settings;
		}
		
		public static function constructFromRecord($record) {
			$settings = new Settings();
			$settings->setWebsiteTitle($record['website_title']);
			$settings->setFrontEndHostname($record['frontend_hostname']);
			$settings->setBackEndHostname($record['backend_hostname']);
			$settings->setEmailAddress($record['email_address']);
			$settings->setSmtpHost($record['smtp_host']);
			$settings->setRootDir($record['root_dir']);
			$settings->setFrontendTemplateDir($record['frontend_template_dir']);
			$settings->setStaticDir($record['static_files_dir']);
			$settings->setConfigDir($record['config_dir']);
			$settings->setUploadDir($record['upload_dir']);
			$settings->setComponentDir($record['component_dir']);
			$settings->setDatabaseVersion($record['database_version']);
			$settings->setBackendTemplateDir($record['backend_template_dir']);
			return $settings;
		}
	}
	
?>