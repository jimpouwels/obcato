<?php

namespace Obcato\Core\admin\elements\text_element;

use Obcato\Core\admin\database\dao\ElementDao;
use Obcato\Core\admin\database\dao\ElementDaoMysql;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;

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