<?php
    defined('_ACCESS') or die;
  
    require_once CMS_ROOT . 'modules/webforms/handlers/form_handler.php';
    require_once CMS_ROOT . 'core/model/webform.php';

    class EmailFormHandler extends Formhandler {

        public static string $TYPE = 'email_form_handler';

        public function getRequiredProperties(): array {
            return array(
                'email_address' => 'textfield',
                'email_template' => 'textarea'
            );
        }

        public function getNameResourceIdentifier(): string {
            return 'webforms_email_form_handler_name';
        }

        public function getType(): string {
            return self::$TYPE;
        }

        public function handlePost(array $properties): void {
            // send email
        }
    }
?>