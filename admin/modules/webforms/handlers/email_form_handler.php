<?php
    defined('_ACCESS') or die;
  
    require_once CMS_ROOT . 'modules/webforms/handlers/form_handler.php';
    require_once CMS_ROOT . 'core/model/webform.php';
    require_once CMS_ROOT . 'database/dao/settings_dao.php';

    class EmailFormHandler extends Formhandler {

        public static string $TYPE = 'email_form_handler';
        private SettingsDao $_settings_dao;

        public function __construct() {
            $this->_settings_dao = SettingsDao::getInstance();
        }

        public function getRequiredProperties(): array {
            return array(
                array('name' => 'email_address', 'type' => 'textfield'),
                array('name' => 'template', 'type' => 'textarea')
            );
        }

        public function getNameResourceIdentifier(): string {
            return 'webforms_email_form_handler_name';
        }

        public function getType(): string {
            return self::$TYPE;
        }

        public function handlePost(array $properties, array $fields): void {
            $headers = 'From: info@jqtravel.nl';
            $message = $this->findPropertyIn($properties, 'template')['value'];
            foreach ($fields as $field) {
                $message = str_replace('${'.$field['name'].'}', $field['value'], $message);
            }
            $email_address = $this->_settings_dao->getSettings()->getEmailAddress();
            if (!$email_address) {
                $email_address = $this->findPropertyIn($properties, 'email_address')['value'];
            }
            mail($email_address, 'JQTravel', $message, $headers);
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