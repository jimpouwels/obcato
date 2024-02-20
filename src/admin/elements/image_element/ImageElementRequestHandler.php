<?php

namespace Obcato\Core\admin\elements\image_element;

use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\database\dao\ElementDao;
use Obcato\Core\admin\database\dao\ElementDaoMysql;
use Obcato\Core\admin\elements\ElementContainsErrorsException;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;

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
