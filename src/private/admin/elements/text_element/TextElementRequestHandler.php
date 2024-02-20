<?php

namespace Obcato\Core;

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