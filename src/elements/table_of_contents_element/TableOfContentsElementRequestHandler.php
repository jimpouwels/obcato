<?php

namespace Pageflow\Core\elements\table_of_contents_element;

use Pageflow\Core\core\form\FormException;
use Pageflow\Core\database\dao\ElementDao;
use Pageflow\Core\database\dao\ElementDaoMysql;
use Pageflow\Core\elements\ElementContainsErrorsException;
use Pageflow\Core\request_handlers\HttpRequestHandler;

class TableOfContentsElementRequestHandler extends HttpRequestHandler {

    private TableOfContentsElement $tableOfContentsElement;
    private ElementDao $elementDao;
    private TableOfContentsElementForm $tableOfContentsElementForm;

    public function __construct(TableOfContentsElement $tableOfContentsElement) {
        $this->tableOfContentsElement = $tableOfContentsElement;
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->tableOfContentsElementForm = new TableOfContentsElementForm($this->tableOfContentsElement);
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $this->tableOfContentsElementForm->loadFields();
            $this->elementDao->updateElement($this->tableOfContentsElement);
        } catch (FormException) {
            throw new ElementContainsErrorsException("Table of contents element contains errors");
        }
    }
}