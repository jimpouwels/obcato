<?php

namespace Obcato\Core\elements\form_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\dao\WebformDao;
use Obcato\Core\database\dao\WebformDaoMysql;
use Obcato\Core\database\MysqlConnector;

class FormElementMetadataProvider extends ElementMetadataProvider
{

    private WebformDao $webformDao;
    private MysqlConnector $mysqlConnector;

    public function __construct(Element $element)
    {
        parent::__construct($element);
        $this->webformDao = WebformDaoMysql::getInstance();
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getTableName(): string
    {
        return "form_elements_metadata";
    }

    public function constructMetaData(array $record, Element $element): void
    {
        $element->setTitle($record['title']);
        if (isset($record["webform_id"])) {
            $element->setWebForm($this->webformDao->getWebForm($record['webform_id']));
        }
    }

    public function update(Element $element): void
    {
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

    public function insert(Element $element): void
    {
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