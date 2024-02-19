<?php
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/elements/table_of_contents_element/TableOfContentsElementForm.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/elements/ElementContainsErrorsException.php";

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

?>