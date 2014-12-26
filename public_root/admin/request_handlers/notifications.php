<?php
    defined('_ACCESS') or die;
    
    class Notifications {
        
        /*
            Private constructor.
        */
        private function __construct() {
        }
        
        /*
            Sets a success message to the session.
            
            @param $message The message to set
        */
        public static function setSuccessMessage($message) {
            if (!self::getMessage()) {
                $_SESSION['success'] = true;
                self::setMessage($message);
            }
        }
        
        /*
            Sets a failed message to the session.
            
            @param $message The message to set
        */
        public static function setFailedMessage($message) {
            if (!self::getMessage()) {
                $_SESSION['success'] = false;
                self::setMessage($message);
            }
        }
        
        /*
            Returns the message in the session.
        */
        public static function getMessage() {
            if (isset($_SESSION['cms_notification']) && !is_null($_SESSION['cms_notification'])) {
                return $_SESSION['cms_notification'];
            }
        }
        
        /*
            Returns the success value from the session.
        */
        public static function getSuccess() {
            $success = NULL;
            if (isset($_SESSION['success']) && !is_null($_SESSION['success'])) {
                $success = $_SESSION['success'];
            }
            return $success;
        }
        
        /*
            Clears the notification.
        */
        public static function clearMessage() {
            if (isset($_SESSION['cms_notification']) && !is_null($_SESSION['cms_notification'])) {
                unset($_SESSION['cms_notification']);
            }
            if (isset($_SESSION['success']) && !is_null($_SESSION['success'])) {
                unset($_SESSION['success']);
            }
        }

        private static function setMessage($message) {
            $_SESSION['cms_notification'] = $message;
        }
    
    }
?>