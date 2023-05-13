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
                array('name' => 'email_address', 'type' => 'textfield', 'editor' => null),
                array('name' => 'template', 'type' => 'textarea', 'editor' => null)
            );
        }

        public function getNameResourceIdentifier(): string {
            return 'webforms_email_form_handler_name';
        }

        public function getType(): string {
            return self::$TYPE;
        }

        public function handlePost(WebFormHandlerInstance $webform_handler_instance, array $fields): void {
            $message = $webform_handler_instance->getProperty('template')->getValue();
            foreach ($fields as $field) {
                $message = str_replace('${'.$field['name'].'}', $field['value'], $message);
            }
            $target_email_address = $this->_settings_dao->getSettings()->getEmailAddress();
            if (!$target_email_address) {
                $target_email_address = $webform_handler_instance->getProperty('email_address')->getValue();
            }
            $headers = array(
                'From' => $target_email_address
            );
            mail($target_email_address, 'JQTravel', $message, $headers);
        }
    }
?>