<?php
    defined('_ACCESS') or die;
  
    require_once CMS_ROOT . 'modules/webforms/handlers/form_handler.php';
    require_once CMS_ROOT . 'core/model/webform.php';
    require_once CMS_ROOT . 'database/dao/settings_dao.php';
    require_once CMS_ROOT . 'database/dao/page_dao.php';

    class RedirectFormHandler extends Formhandler {

        public static string $TYPE = 'redirect_form_handler';
        private SettingsDao $_settings_dao;
        private PageDao $_page_dao;

        public function __construct() {
            parent::__construct();
            $this->_settings_dao = SettingsDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
        }

        public function getRequiredProperties(): array {
            require_once CMS_ROOT . 'modules/webforms/visuals/redirect_form_handler_editor.php';
            return array(
                array('name' => 'page_id', 'type' => 'textfield', 'editor' => new RedirectFormHandlerEditor()),
            );
        }

        public function getNameResourceIdentifier(): string {
            return 'webforms_redirect_form_handler_name';
        }

        public function getType(): string {
            return self::$TYPE;
        }

        public function handlePost(array $properties, array $fields): void {
            $page_id = $this->findPropertyIn($properties, 'page_id')['value'];
            if ($page_id) {
                $page = $this->_page_dao->getPage($page_id);
                if ($page) {
                    $this->redirectTo($this->getPageUrl($page));
                }
            }
        }

        private function findPropertyIn(array $properties, string $property_to_find): ?array {
            foreach ($properties as $property) {
                if ($property['name'] == $property_to_find) {
                    return $property;
                }
            }
            return null;
        }
    }
?>