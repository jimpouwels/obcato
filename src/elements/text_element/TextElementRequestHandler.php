<?php

namespace Pageflow\Core\elements\text_element;

use Pageflow\Core\database\dao\ElementDao;
use Pageflow\Core\database\dao\ElementDaoMysql;
use Pageflow\Core\request_handlers\HttpRequestHandler;

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