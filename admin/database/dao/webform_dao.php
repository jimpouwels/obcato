<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "core/model/webform.php";
    require_once CMS_ROOT . "core/model/webform_field.php";
    
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
        }

        public function persistWebFormField(WebForm $webform, WebFormField $webform_field): void {
            $query = "INSERT INTO webforms_fields (label, `name`, mandatory, webform_id, `type`) VALUE (?, ?, ?, ?, ?)";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $label = $webform_field->getLabel();
            $name = $webform_field->getName();
            $webform_id = $webform->getId();
            $mandatory = $webform_field->getMandatory() ? 1 : 0;
            $type = $webform_field->getType();
            $statement->bind_param("ssiis", $label, $name, $mandatory, $webform_id, $type);
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
                }
            }
            return $form_fields;
        }
    }