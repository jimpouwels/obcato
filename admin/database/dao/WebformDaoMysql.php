<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/dao/WebFormDao.php";
require_once CMS_ROOT . "/database/mysql_connector.php";
require_once CMS_ROOT . "/core/model/Webform.php";
require_once CMS_ROOT . "/core/model/WebformField.php";
require_once CMS_ROOT . "/core/model/WebformTextField.php";
require_once CMS_ROOT . "/core/model/WebformTextArea.php";
require_once CMS_ROOT . "/core/model/WebformDropdown.php";
require_once CMS_ROOT . "/core/model/WebformButton.php";
require_once CMS_ROOT . "/core/model/WebformHandlerInstance.php";
require_once CMS_ROOT . "/core/model/WebformHandlerProperty.php";
require_once CMS_ROOT . '/modules/webforms/handlers/form_handler.php';

class WebFormDaoMysql implements WebFormDao {

    private static string $myAllColumns = "i.id, i.title, i.template_id, i.include_captcha, i.captcha_key, s.id as scope_id";
    private static ?WebFormDaoMysql $instance = null;
    private MysqlConnector $_mysql_connector;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public static function getInstance(): WebFormDaoMysql {
        if (!self::$instance) {
            self::$instance = new WebFormDaoMysql();
        }
        return self::$instance;
    }

    public function getWebForm(int $webform_id): ?WebForm {
        $query = "SELECT " . self::$myAllColumns . " FROM webforms i, scopes s WHERE i.id = ${webform_id} AND s.id = " . WebForm::$SCOPE;
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return WebForm::constructFromRecord($row, $this->getWebFormItemsByWebForm($webform_id));
        }
        return null;
    }

    public function getAllWebForms(): array {
        $webforms = array();
        $query = "SELECT " . self::$myAllColumns . " FROM webforms i, scopes s WHERE s.id = " . WebForm::$SCOPE;
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            $webforms[] = WebForm::constructFromRecord($row, $this->getWebFormItemsByWebForm($row["id"]));
        }
        return $webforms;
    }

    public function persistWebForm(WebForm $webform): void {
        $query = "INSERT INTO webforms (title, include_captcha) VALUES (?, ?)";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $title = $webform->getTitle();
        $include_captcha = 0;
        $statement->bind_param('si', $title, $include_captcha);
        $this->_mysql_connector->executeStatement($statement);
        $webform->setId($this->_mysql_connector->getInsertId());
    }

    public function updateWebForm(WebForm $webform): void {
        $query = "UPDATE webforms SET title = ?, template_id = ?, include_captcha = ?, captcha_key = ? WHERE id = ?";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $id = $webform->getId();
        $title = $webform->getTitle();
        $template_id = $webform->getTemplateId();
        $include_captcha = $webform->getIncludeCaptcha() ? 1 : 0;
        $captcha_key = $webform->getCaptchaKey();
        $statement->bind_param("siisi", $title, $template_id, $include_captcha, $captcha_key, $id);
        $this->_mysql_connector->executeStatement($statement);
        foreach ($webform->getFormFields() as $form_field) {
            $this->updateWebFormItem($form_field);
        }
    }

    public function deleteWebForm(WebForm $webform): void {
        $query = 'DELETE FROM webforms WHERE id = ?';
        $statement = $this->_mysql_connector->prepareStatement($query);
        $id = $webform->getId();
        $statement->bind_param('i', $id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function persistWebFormItem(WebForm $webform, WebFormItem $webform_item): void {
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

    public function updateWebFormItem(WebFormItem $webform_item): void {
        $query = "UPDATE webforms_fields SET `name` = ?, label = ?, template_id = ?, mandatory = ?, order_nr = ? WHERE id = ?";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $label = $webform_item->getLabel();
        $name = $webform_item->getName();
        $order_nr = $webform_item->getOrderNr();
        $template_id = $webform_item->getTemplateId();
        $webform_field_id = $webform_item->getId();

        $mandatory = 0;
        if ($webform_item instanceof WebFormField) {
            $mandatory = $webform_item->getMandatory() ? 1 : 0;
        }
        $statement->bind_param("ssiiii", $name, $label, $template_id, $mandatory, $order_nr, $webform_field_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function deleteWebFormItem(int $item_id): void {
        $query = 'DELETE FROM webforms_fields WHERE id = ?';
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('i', $item_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function getWebFormItem(int $id): ?WebFormItem {
        $query = 'SELECT * FROM webforms_fields WHERE id = ?';
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('i', $id);
        $result = $this->_mysql_connector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            switch ($row["type"]) {
                case WebFormTextField::$TYPE:
                    return WebFormTextField::constructFromRecord($row);
                case WebFormTextArea::$TYPE:
                    return WebFormTextArea::constructFromRecord($row);
                case WebFormDropDown::$TYPE:
                    return WebFormDropDown::constructFromRecord($row);
                case WebFormButton::$TYPE:
                    return WebFormButton::constructFromRecord($row);
            }
        }
        return null;
    }

    public function getWebFormItemsByWebForm(int $webform_id): array {
        $form_fields = array();
        $query = "SELECT * FROM webforms_fields WHERE webform_id = ? ORDER BY order_nr ASC";
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
                    $form_fields[] = WebFormDropDown::constructFromRecord($row);
                    break;
                case WebFormButton::$TYPE:
                    $form_fields[] = WebFormButton::constructFromRecord($row);
                    break;
            }
        }
        return $form_fields;
    }

    public function addWebFormHandler(WebForm $webform, FormHandler $handler) {
        $query = 'INSERT INTO webforms_handlers (`type`, webform_id) VALUES (?, ?)';
        $statement = $this->_mysql_connector->prepareStatement($query);
        $type = $handler->getType();
        $webform_id = $webform->getId();
        $statement->bind_param('si', $type, $webform_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function getWebFormHandlersFor(WebForm $webform): array {
        $query = 'SELECT * FROM webforms_handlers WHERE webform_id = ?';
        $statement = $this->_mysql_connector->prepareStatement($query);
        $webform_id = $webform->getId();
        $statement->bind_param('i', $webform_id);

        $result = $this->_mysql_connector->executeStatement($statement);

        $handlers = array();
        while ($row = $result->fetch_assoc()) {
            $handlers[] = WebFormHandlerInstance::constructFromRecord($row, $this->getPropertiesFor($row['id']));
        }
        return $handlers;
    }

    public function deleteWebFormHandler(WebForm $webform, int $webform_handler_id): void {
        $query = 'DELETE FROM webforms_handlers WHERE webform_id = ? AND `id` = ?';
        $statement = $this->_mysql_connector->prepareStatement($query);
        $webform_id = $webform->getId();
        $statement->bind_param('ii', $webform_id, $webform_handler_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function storeProperty(int $handler_id, WebFormHandlerProperty $property): void {
        $name = $property->getName();
        $type = $property->getType();
        $query = "INSERT INTO webforms_handlers_properties (handler_id, `name`, `value`, `type`) VALUES ('{$handler_id}', '{$name}', '', '{$type}')";
        $this->_mysql_connector->executeQuery($query);
        $property->setId($this->_mysql_connector->getInsertId());
    }

    public function deleteProperty(WebFormHandlerProperty $webform_handler_property): void {
        $query = 'DELETE FROM webforms_handlers_properties WHERE id = ?';
        $statement = $this->_mysql_connector->prepareStatement($query);
        $id = $webform_handler_property->getId();
        $statement->bind_param('i', $id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function updateHandlerProperty(WebFormHandlerProperty $property): void {
        $query = 'UPDATE webforms_handlers_properties SET `value` = ? WHERE id = ?';
        $statement = $this->_mysql_connector->prepareStatement($query);

        $id = $property->getId();
        $value = $property->getValue();
        $statement->bind_param('si', $value, $id);
        $this->_mysql_connector->executeStatement($statement);
    }

    private function getPropertiesFor(int $handler_id): array {
        $query = 'SELECT * FROM webforms_handlers_properties WHERE handler_id = ?';
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('i', $handler_id);
        $result = $this->_mysql_connector->executeStatement($statement);

        $properties = array();
        while ($row = $result->fetch_assoc()) {
            $properties[] = WebFormHandlerProperty::constructFromRecord($row);
        }
        return $properties;
    }
}