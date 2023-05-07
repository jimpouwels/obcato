<?php
    defined('_ACCESS') or die;
  
    require_once CMS_ROOT . 'modules/webforms/handlers/form_handler.php';
    require_once CMS_ROOT . 'core/model/webform.php';

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
            $headers = 'From: info@jqtravel.nl';
            $message = $this->findPropertyIn($properties, 'template')['value'];
            foreach ($fields as $field) {
                $message = str_replace('${'.$field['name'].'}', $field['value'], $message);
            }
            mail('jim.pouwels@gmail.com', 'JQTravel', $message, $headers);
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