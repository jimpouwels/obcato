<?php

namespace Obcato\Core\admin\elements\table_of_contents_element;

use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\database\dao\ElementDao;
use Obcato\Core\admin\database\dao\ElementDaoMysql;
use Obcato\Core\admin\elements\ElementContainsErrorsException;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;

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