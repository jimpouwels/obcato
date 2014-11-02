<?php
	defined('_ACCESS') or die;

    require_once CMS_ROOT . 'request_handlers/notifications.php';
	
	abstract class HttpRequestHandler {
	
		public function handle() {
			if ($_SERVER["REQUEST_METHOD"] === "POST") {
				$this->handlePost();
			} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
				$this->handleGet();
			}
		}
	
		abstract function handleGet();
		
		abstract function handlePost();

        protected function sendSuccessMessage($message) {
            Notifications::setSuccessMessage($message);
        }

        protected static function sendErrorMessage($message) {
            Notifications::setFailedMessage($message);
        }

	}