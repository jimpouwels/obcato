<?php

namespace Obcato\Core\elements\image_element;

use Obcato\Core\core\form\FormException;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\elements\ElementContainsErrorsException;
use Obcato\Core\request_handlers\HttpRequestHandler;

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
