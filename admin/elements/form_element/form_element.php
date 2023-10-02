<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/element.php";
require_once CMS_ROOT . "/core/model/element_metadata_provider.php";
require_once CMS_ROOT . "/core/model/webform.php";
require_once CMS_ROOT . "/database/mysql_connector.php";
require_once CMS_ROOT . "/database/dao/WebformDaoMysql.php";
require_once CMS_ROOT . "/elements/form_element/visuals/form_element_statics.php";
require_once CMS_ROOT . "/elements/form_element/visuals/form_element_editor.php";
require_once CMS_ROOT . "/elements/form_element/form_element_request_handler.php";
require_once CMS_ROOT . "/frontend/form_element_visual.php";

class FormElement extends Element {

    private ?WebForm $_webform = null;

    public function __construct(int $scope_id) {
        parent::__construct($scope_id, new FormElementMetadataProvider($this));
    }

    public function setWebForm(?WebForm $webform): void {
        $this->_webform = $webform;
    }

    public function getWebForm(): ?WebForm {
        return $this->_webform;
    }

    public function getStatics(): Visual {
        return new FormElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new FormElementEditorVisual($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): ElementFrontendVisual {
        return new FormElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new FormElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        $summary_text = "";
        if ($this->_webform) {
            $summary_text = $this->_webform->getTitle();
        }
        return $summary_text;
    }
}

class FormElementMetadataProvider extends ElementMetadataProvider {

    private WebFormDao $_webform_dao;
    private MysqlConnector $_mysql_connector;

    public function __construct(Element $element) {
        parent::__construct($element);
        $this->_webform_dao = WebFormDaoMysql::getInstance();
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public function getTableName(): string {
        return "form_elements_metadata";
    }

    public function constructMetaData(array $record, Element $element): void {
        $element->setTitle($record['title']);
        if (isset($record["webform_id"])) {
            $element->setWebForm($this->_webform_dao->getWebForm($record['webform_id']));
        }
    }

    public function update(Element $element): void {
        $element_id = $element->getId();
        $webform_id = null;
        if ($element->getWebForm()) {
            $webform_id = $element->getWebForm()->getId();
        }
        $statement = $this->_mysql_connector->prepareStatement("UPDATE form_elements_metadata SET webform_id = ?, title = ? WHERE element_id = ?");
        $title = $element->getTitle();
        $statement->bind_param("isi", $webform_id, $title, $element_id);

        $this->_mysql_connector->executeStatement($statement);
    }

    public function insert(Element $element): void {
        $element_id = $element->getId();
        $webform_id = null;
        if ($element->getWebForm()) {
            $webform_id = $element->getWebForm()->getId();
        }
        $statement = $this->_mysql_connector->prepareStatement("INSERT INTO form_elements_metadata (webform_id, element_id) VALUES (?, ?)");
        $statement->bind_param("ii", $webform_id, $element_id);
        $this->_mysql_connector->executeStatement($statement);
    }

}

?>
