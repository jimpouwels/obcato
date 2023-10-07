<?php
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/elements/iframe_element/IFrameElementForm.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/elements/ElementContainsErrorsException.php";

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

?>