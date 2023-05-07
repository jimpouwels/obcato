<?php
    defined('_ACCESS') or die;
  
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once CMS_ROOT . 'modules/webforms/handlers/form_handler.php';
    require_once CMS_ROOT . 'core/model/webform.php';
    require CMS_ROOT . 'lib/phpmailer/Exception.php';
    require CMS_ROOT . 'lib/phpmailer/PHPMailer.php';
    require CMS_ROOT . 'lib/phpmailer/SMTP.php';

    class EmailFormHandler extends Formhandler {

        public static string $TYPE = 'email_form_handler';

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
            mail('jim.pouwels@gmail.com', 'JQTravel', 'Hoiiii');
        }
    }
?>