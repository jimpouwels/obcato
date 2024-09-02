<?php

namespace Obcato\Core\elements\separator_element;

use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\request_handlers\HttpRequestHandler;

class SeparatorElementRequestHandler extends HttpRequestHandler {

    private SeparatorElement $separatorElement;
    private ElementDao $elementDao;

    public function __construct(SeparatorElement $separatorElement) {
        $this->separatorElement = $separatorElement;
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        $this->elementDao->updateElement($this->separatorElement);
    }
}