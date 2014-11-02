<?php
	
	defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
	
	class StaticsRequestHandler extends HttpRequestHandler {

		private $_settings;
		
		public function __construct() {
		}

        public function handleGet() {
			$relative_path = $this->getRelativePathFromGetRequest();
			if (!empty($relative_path)) {
				$absolute_path = $this->getAbsolutePathFor($relative_path);
				$this->setResponseContentType($absolute_path);
				readfile($absolute_path);
			}
		}

        public function handlePost() {
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
			return STATIC_DIR . $relative_path;
		}
		
		private function getRelativePathFromGetRequest() {
			if (isset($_GET["file"]) && $_GET["file"] != "")
				return $_GET["file"];
		}
	}
?>