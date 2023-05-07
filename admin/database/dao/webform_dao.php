<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "core/model/webform.php";
    require_once CMS_ROOT . "core/model/webform_field.php";
    require_once CMS_ROOT . "core/model/webform_textfield.php";
    require_once CMS_ROOT . "core/model/webform_textarea.php";
    require_once CMS_ROOT . "core/model/webform_dropdown.php";
    require_once CMS_ROOT . "core/model/webform_button.php";
    require_once CMS_ROOT . 'modules/webforms/handlers/form_handler.php';
    
    class WebFormDao {

        private static string $myAllColumns = "i.id, i.title";
        private static ?WebFormDao $instance = null;
        private MysqlConnector $_mysql_connector;

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
        }

        public static function getInstance(): WebFormDao {
            if (!self::$instance) {
                self::$instance = new WebFormDao();
            }
            return self::$instance;
        }

        public function getWebForm(int $webform_id): ?WebForm {
            $query = "SELECT " . self::$myAllColumns . " FROM webforms i WHERE id = " . $webform_id;
            $result = $this->_mysql_connector->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                return WebForm::constructFromRecord($row, $this->getFormFieldsByWebForm($webform_id));
            }
            return null;
        }

        public function getAllWebForms(): array {
            $webforms = array();
            $query = "SELECT " . self::$myAllColumns . " FROM webforms i";
            $result = $this->_mysql_connector->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                $webforms[] = WebForm::constructFromRecord($row, $this->getFormFieldsByWebForm($row["id"]));
            }
            return $webforms;
        }

        public function persistWebForm(WebForm $webform): void {
            $query = "INSERT INTO webforms (title) VALUES (?)";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $title = $webform->getTitle();
            $statement->bind_param("s", $title);
            $this->_mysql_connector->executeStatement($statement);
            $webform->setId($this->_mysql_connector->getInsertId());
        }

        public function updateWebForm(WebForm $webform): void {
            $query = "UPDATE webforms SET title = ? WHERE id = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $id = $webform->getId();
            $title = $webform->getTitle();
            $statement->bind_param("si", $title, $id);
            $this->_mysql_connector->executeStatement($statement);
            foreach ($webform->getFormFields() as $form_field) {
                $this->updateFormField($form_field);
            }
        }

        public function persistWebFormField(WebForm $webform, WebFormItem $webform_item): void {
            $query = "INSERT INTO webforms_fields (label, `name`, mandatory, webform_id, `type`, scope_id) VALUE (?, ?, ?, ?, ?, ?)";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $label = $webform_item->getLabel();
            $name = $webform_item->getName();
            $webform_id = $webform->getId();

            $mandatory = false;
            if ($webform_item instanceof WebFormField) {
                $mandatory = $webform_item->getMandatory() ? 1 : 0;
            }

            $type = $webform_item->getType();
            $scope_id = $webform_item->getScopeId();
            $statement->bind_param("ssiisi", $label, $name, $mandatory, $webform_id, $type, $scope_id);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function updateFormField(WebFormItem $webform_item): void {
            $query = "UPDATE webforms_fields SET `name` = ?, label = ?, template_id = ?, mandatory = ? WHERE id = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $label = $webform_item->getLabel();
            $name = $webform_item->getName();
            $template_id = $webform_item->getTemplateId();
            $webform_field_id = $webform_item->getId();

            $mandatory = 0;
            if ($webform_item instanceof WebFormField) {
                $mandatory = $webform_item->getMandatory() ? 1 : 0;
            }
            $statement->bind_param("ssiii", $name, $label, $template_id, $mandatory, $webform_field_id);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function deleteWebFormItem(int $item_id): void {
            $query = 'DELETE FROM webforms_fields WHERE id = ?';
            $statement = $this->_mysql_connector->prepareStatement($query);
            $statement->bind_param('i', $item_id);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function getFormFieldsByWebForm(int $webform_id): array {
            $form_fields = array();
            $query = "SELECT * FROM webforms_fields WHERE webform_id = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $statement->bind_param("i", $webform_id);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc()) {
                switch ($row["type"]) {
                    case WebFormTextField::$TYPE:
                        $form_fields[] = WebFormTextField::constructFromRecord($row);
                        break;
                    case WebFormTextArea::$TYPE:
                        $form_fields[] = WebFormTextArea::constructFromRecord($row);
                        break;
                    case WebFormDropDown::$TYPE:
                        $form_fields[] = WebFormDropDown::constructFromRecord($row, array());
                        break;
                    case WebFormButton::$TYPE:
                        $form_fields[] = WebFormButton::constructFromRecord($row);
                        break;
                }
            }
            return $form_fields;
        }

        public function addHandler(WebForm $webform, FormHandler $handler) {
            $query = 'INSERT INTO webforms_handlers (`type`, webform_id) VALUES (?, ?)';
            $statement = $this->_mysql_connector->prepareStatement($query);
            $type = $handler->getType();
            $webform_id = $webform->getId();
            $statement->bind_param('si', $type, $webform_id);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function getHandlersFor(WebForm $webform): array {
            $handlers = array();
            $query = 'SELECT * FROM webforms_handlers WHERE webform_id = ?';
            $statement = $this->_mysql_connector->prepareStatement($query);
            $webform_id = $webform->getId();
            $statement->bind_param('i', $webform_id);

            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc()) {
                $handlers[] = array(
                    "id" => $row["id"],
                    "type" => $row['type']
                );
            }
            return $handlers;
        }

        public function deleteFormHandler(WebForm $webform, int $webform_handler_id): void {
            $query = 'DELETE FROM webforms_handlers WHERE webform_id = ? AND `id` = ?';
            $statement = $this->_mysql_connector->prepareStatement($query);
            $webform_id = $webform->getId();
            $statement->bind_param('ii', $webform_id, $webform_handler_id);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function getPropertiesFor(int $handler_id): array {
            $properties = array();
            $query = 'SELECT * FROM webforms_handlers_properties WHERE handler_id = ?';
            $statement = $this->_mysql_connector->prepareStatement($query);
            $statement->bind_param('i', $handler_id);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc()) {
                $properties[] = array(
                    "id" => $row["id"],
                    "name" => $row["name"],
                    "value" => $row['value']
                );
            }
            return $properties;
        }

        public function storeProperty(int $handler_id, array $property): array {
            $property_name = $property['name'];
            $property_type = $property['type'];
            $query = "INSERT INTO webforms_handlers_properties (handler_id, `name`, `value`, `type`) VALUES ('{$handler_id}', '{$property_name}', '', '{$property_type}')";
            $this->_mysql_connector->executeQuery($query);

            return array(
                'id' => $this->_mysql_connector->getInsertId(),
                'name' => $property_name,
                'value' => ''
            );
        }

        public function updateHandlerProperty(array $property): void {
            $query = 'UPDATE webforms_handlers_properties SET `value` = ? WHERE id = ?';
            $statement = $this->_mysql_connector->prepareStatement($query);
            $statement->bind_param('si', $property['value'], $property['id']);
            $this->_mysql_connector->executeStatement($statement);
        }
    }