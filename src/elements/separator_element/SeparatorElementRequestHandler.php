<?php

namespace Pageflow\Core\elements\separator_element;

use Pageflow\Core\database\dao\ElementDao;
use Pageflow\Core\database\dao\ElementDaoMysql;
use Pageflow\Core\elements\text_element\TextElementForm;
use Pageflow\Core\request_handlers\HttpRequestHandler;

class SeparatorElementRequestHandler extends HttpRequestHandler {

    private SeparatorElement $separatorElement;
    private ElementDao $elementDao;
    private SeparatorElementForm $separatorElementForm;

    public function __construct(SeparatorElement $separatorElement) {
        $this->separatorElement = $separatorElement;
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->separatorElementForm = new SeparatorElementForm($this->separatorElement);
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        $this->separatorElementForm->loadFields();
        $this->elementDao->updateElement($this->separatorElement);
    }
}