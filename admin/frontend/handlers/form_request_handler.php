<?php
    defined("_ACCESS") or die;

    require_once CMS_ROOT . 'core/model/page.php';
    require_once CMS_ROOT . 'core/model/article.php';
    require_once CMS_ROOT . 'database/dao/webform_dao.php';
    require_once CMS_ROOT . 'modules/webforms/webform_handler_manager.php';

    class FormRequestHandler {

        private static ?FormRequestHandler $_instance = null;
        private WebFormHandlerManager $_webform_handler_manager;
        private WebFormDao $_webform_dao;

        public function __construct() {
            $this->_webform_dao = WebFormDao::getInstance();
            $this->_webform_handler_manager = WebFormHandlerManager::getInstance();
        }
        
        public static function getInstance(): FormRequestHandler {
            if (!self::$_instance) {
                self::$_instance = new FormRequestHandler();
            }
            return self::$_instance;
        }

        public function handlePost(Page $page, ?Article $article): void {
            if (isset($_POST['webform_id'])) {
                $webform = $this->_webform_dao->getWebForm($_POST['webform_id']);
                $webform_handlers = $this->_webform_dao->getHandlersFor($webform);
                foreach ($webform_handlers as $webform_handler) {
                    $properties = $this->_webform_dao->getPropertiesFor($webform_handler['id']);
                    $fields = $this->getFields($webform);
                    $handler_instance = $this->_webform_handler_manager->getHandler($webform_handler['type']);
                    $handler_instance->handlePost($properties, $fields);
                }
            }
        }

        private function getFields(WebForm $webform): array {
            $form_fields = $this->_webform_dao->getFormFieldsByWebForm($webform->getId());
            $filled_in_fields = array();
            foreach ($form_fields as $form_field) {
                if (!$form_field instanceof WebFormField) {
                    continue;
                }
                $filled_in_field = array();
                $filled_in_field['name'] = $form_field->getName();
                $filled_in_field['value'] = $_POST[$form_field->getName()];
                $filled_in_fields[] = $filled_in_field;
            }
            return $filled_in_fields;
        }
    }
?>