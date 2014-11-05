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
            $_SESSION['success'] = true;
            $_SESSION['cms_notification'] = $message;
        }
        
        /*
            Sets a failed message to the session.
            
            @param $message The message to set
        */
        public static function setFailedMessage($message) {
            $_SESSION['success'] = false;
            $_SESSION['cms_notification'] = $message;
        }
        
        /*
            Returns the message in the session.
        */
        public static function getMessage() {
            $message = NULL;
            if (isset($_SESSION['cms_notification']) && !is_null($_SESSION['cms_notification'])) {
                $message = $_SESSION['cms_notification'];
            }
            return $message;
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
    
    }
?>