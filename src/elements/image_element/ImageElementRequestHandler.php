<?php

namespace Pageflow\Core\elements\image_element;

use Pageflow\Core\core\form\FormException;
use Pageflow\Core\database\dao\ElementDao;
use Pageflow\Core\database\dao\ElementDaoMysql;
use Pageflow\Core\elements\ElementContainsErrorsException;
use Pageflow\Core\request_handlers\HttpRequestHandler;

class ImageElementRequestHandler extends HttpRequestHandler {

    private ImageElement $imageElement;
    private ImageElementForm $imageElementForm;
    private ElementDao $elementDao;

    public function __construct(ImageElement $image_element) {
        $this->imageElement = $image_element;
        $this->imageElementForm = new ImageElementForm($this->imageElement);
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $this->imageElementForm->loadFields();
            $this->elementDao->updateElement($this->imageElement);
        } catch (FormException) {
            throw new ElementContainsErrorsException("Image element contains errors");
        }
    }
}
