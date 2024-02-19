<?php

use Obcato\ComponentApi\Visual;

require_once CMS_ROOT . "/core/model/Element.php";
require_once CMS_ROOT . "/core/model/ElementMetadataProvider.php";
require_once CMS_ROOT . "/modules/webforms/model/Webform.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/database/dao/WebformDaoMysql.php";
require_once CMS_ROOT . "/elements/form_element/visuals/FormElementStatics.php";
require_once CMS_ROOT . "/elements/form_element/visuals/FormElementEditor.php";
require_once CMS_ROOT . "/elements/form_element/FormElementRequestHandler.php";
require_once CMS_ROOT . "/frontend/FormElementFrontendVisual.php";

class FormElement extends Element {

    private ?WebForm $webform = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new FormElementMetadataProvider($this));
    }

    public function setWebForm(?WebForm $webform): void {
        $this->webform = $webform;
    }

    public function getWebForm(): ?WebForm {
        return $this->webform;
    }

    public function getStatics(): Visual {
        return new FormElementStatics(TemplateEngine::getInstance());
    }

    public function getBackendVisual(): ElementVisual {
        return new FormElementEditor(TemplateEngine::getInstance(), $this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): ElementFrontendVisual {
        return new FormElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new FormElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        $summaryText = "";
        if ($this->webform) {
            $summaryText = $this->webform->getTitle();
        }
        return $summaryText;
    }
}

class FormElementMetadataProvider extends ElementMetadataProvider {

    private WebformDao $webformDao;
    private MysqlConnector $mysqlConnector;

    public function __construct(Element $element) {
        parent::__construct($element);
        $this->webformDao = WebformDaoMysql::getInstance();
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string {
        return "form_elements_metadata";
    }

    public function constructMetaData(array $record, Element $element): void {
        $element->setTitle($record['title']);
        if (isset($record["webform_id"])) {
            $element->setWebForm($this->webformDao->getWebForm($record['webform_id']));
        }
    }

    public function update(Element $element): void {
        $elementId = $element->getId();
        $webformId = null;
        if ($element->getWebForm()) {
            $webformId = $element->getWebForm()->getId();
        }
        $statement = $this->mysqlConnector->prepareStatement("UPDATE form_elements_metadata SET webform_id = ?, title = ? WHERE element_id = ?");
        $title = $element->getTitle();
        $statement->bind_param("isi", $webformId, $title, $elementId);

        $this->mysqlConnector->executeStatement($statement);
    }

    public function insert(Element $element): void {
        $elementId = $element->getId();
        $webformId = null;
        if ($element->getWebForm()) {
            $webformId = $element->getWebForm()->getId();
        }
        $statement = $this->mysqlConnector->prepareStatement("INSERT INTO form_elements_metadata (webform_id, element_id) VALUES (?, ?)");
        $statement->bind_param("ii", $webformId, $elementId);
        $this->mysqlConnector->executeStatement($statement);
    }

}