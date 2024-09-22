<?php

namespace Obcato\Core\elements\separator_element;

use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\elements\text_element\TextElementForm;
use Obcato\Core\request_handlers\HttpRequestHandler;

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