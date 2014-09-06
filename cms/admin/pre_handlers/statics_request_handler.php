<?php
	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "/pre_handlers/pre_handler.php";
	require_once CMS_ROOT . "/database/dao/settings_dao.php";
	
	class StaticsRequestHandler extends PreHandler {
		
		private static $FILE_QUERYSTRING_KEY;
		private $_settings;
		
		public function __construct() {
			$this->_settings = SettingsDao::getInstance()->getSettings();
		}
		
		public function handle() {
			$relative_path = $this->getRelativePathFromGetRequest();
			if (!empty($relative_path)) {
				$absolute_path = $this->getAbsolutePathFor($relative_path);
				$this->setResponseContentType($absolute_path);
				readfile($absolute_path);
			}
		}
		
		private function setResponseContentType($absolute_path) {
			$path_parts = explode(".", $absolute_path);
			$extension = $path_parts[count($path_parts) - 1];
			if ($extension == "jpg") {
				header("Content-Type: image/jpeg");
			} else if ($extension == "gif") {
				header("Content-Type: image/gif");
			} else if ($extension == "png") {
				header("Content-Type: img/png");
			} else if ($extension == "css") {
				header("Content-Type: text/css");
			} else if ($extension == "js") {
				header("Content-Type: text/javascript");
			} else if ($extension == "ttf") {
				header("Content-Type: application/x-font-ttf");
			}
		}
		
		private function getAbsolutePathFor($relative_path) {
			return $this->_settings->getStaticDir() . $relative_path;
		}
		
		private function getRelativePathFromGetRequest() {
			if (isset($_GET["file"]) && $_GET["file"] != "") {
				return $_GET["file"];
			}
		}
	}
?>