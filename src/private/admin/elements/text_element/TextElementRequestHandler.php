<?php
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/elements/text_element/TextElementForm.php";

class TextElementRequestHandler extends HttpRequestHandler {

    private TextElement $textElement;
    private ElementDao $elementDao;
    private TextElementForm $textElementForm;

    public function __construct(TextElement $textElement) {
        $this->textElement = $textElement;
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->textElementForm = new TextElementForm($this->textElement);
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        $this->textElementForm->loadFields();
        $this->elementDao->updateElement($this->textElement);
    }
}

?>