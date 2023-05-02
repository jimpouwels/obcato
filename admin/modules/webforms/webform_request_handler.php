<?php    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "database/dao/webform_dao.php";
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "modules/forms/form_form.php";
    
    class WebFormRequestHandler extends HttpRequestHandler {

        private static string $FORM_QUERYSTRING_KEY = "webform";

        private static string $FORM_ID_POST_KEY = "webform_id";
        private WebFormDao $_webform_dao;
        private ?WebForm $_current_webform = null;
    
        public function __construct() {
            $this->_webform_dao = WebFormDao::getInstance();
        }
    
        public function handleGet(): void {
            $this->_current_webform = $this->getFormFromGetRequest();
        }
        
        public function handlePost(): void {
            $this->_current_webform = $this->getFormFromPostRequest();
            // if ($this->isUpdateImageAction()) {
            //     $this->updateImage();
            // } else if ($this->isDeleteImageAction()) {
            //     $this->deleteImage();
            // } else if ($this->isAddImageAction()) {
            //     $this->addImage();
            // } else if ($this->isToggleImagePublishedAction()) {
            //     $this->toggleImagePublished();
            // }
        }
        
        public function getCurrentWebForm(): ?Form {
            return $this->_current_webform;
        }
        
        private function getFormFromPostRequest(): ?WebForm {
            $webform = null;
            $webform_id = $this->getFormIdFromPostRequest();
            if (!is_null($webform_id)) {
                $webform = $this->_webform_dao->getWebForm($webform_id);
            }
            return $webform;
        }
        
        private function getFormFromGetRequest(): ?Form {
            $current_form = null;
            if (isset($_GET[self::$FORM_QUERYSTRING_KEY]) && $_GET[self::$FORM_QUERYSTRING_KEY] != "") {
                $current_form = $this->_webform_dao->getWebForm($_GET[self::$FORM_QUERYSTRING_KEY]);
            }
            return $current_form;
        }
        
        private function getFormIdFromPostRequest(): ?int {
            $form_id = null;
            if (isset($_POST[self::$FORM_ID_POST_KEY]) && $_POST[self::$FORM_ID_POST_KEY]) {
                $form_id = $_POST[self::$FORM_ID_POST_KEY];
            }
            return $form_id;
        }
        
    }
    
?>