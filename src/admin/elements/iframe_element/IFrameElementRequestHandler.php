<?php

namespace Obcato\Core\admin\elements\iframe_element;

use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\database\dao\ElementDao;
use Obcato\Core\admin\database\dao\ElementDaoMysql;
use Obcato\Core\admin\elements\ElementContainsErrorsException;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;

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