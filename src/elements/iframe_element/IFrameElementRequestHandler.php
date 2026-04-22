<?php

namespace Pageflow\Core\elements\iframe_element;

use Pageflow\Core\core\form\FormException;
use Pageflow\Core\database\dao\ElementDao;
use Pageflow\Core\database\dao\ElementDaoMysql;
use Pageflow\Core\elements\ElementContainsErrorsException;
use Pageflow\Core\request_handlers\HttpRequestHandler;

class IFrameElementRequestHandler extends HttpRequestHandler {

    private IFrameElement $iframeElement;
    private IFrameElementForm $iframeElementForm;
    private ElementDao $elementDao;

    public function __construct(IFrameElement $iframeElement) {
        $this->iframeElement = $iframeElement;
        $this->iframeElementForm = new IFrameElementForm($this->iframeElement);
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $this->iframeElementForm->loadFields();
            $this->elementDao->updateElement($this->iframeElement);
        } catch (FormException) {
            throw new ElementContainsErrorsException("IFrame element contains errors");
        }
    }
}