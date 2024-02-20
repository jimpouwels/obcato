<?php

namespace Obcato\Core\admin\elements\photo_album_element;

use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\database\dao\ElementDao;
use Obcato\Core\admin\database\dao\ElementDaoMysql;
use Obcato\Core\admin\database\dao\ImageDao;
use Obcato\Core\admin\database\dao\ImageDaoMysql;
use Obcato\Core\admin\elements\ElementContainsErrorsException;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;

class PhotoAlbumElementRequestHandler extends HttpRequestHandler {

    private PhotoAlbumElement $photoAlbumElement;
    private ElementDao $elementDao;
    private ImageDao $imageDao;
    private PhotoAlbumElementForm $photoAlbumElementForm;

    public function __construct(PhotoAlbumElement $photoAlbumElement) {
        $this->photoAlbumElement = $photoAlbumElement;
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->photoAlbumElementForm = new PhotoAlbumElementForm($photoAlbumElement);
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $this->photoAlbumElementForm->loadFields();
            $this->removeSelectedLabels();
            $this->addSelectedLabels();
            $this->elementDao->updateElement($this->photoAlbumElement);
        } catch (FormException) {
            throw new ElementContainsErrorsException("Photo album element contains errors");
        }
    }

    private function addSelectedLabels(): void {
        $selectedLabels = $this->photoAlbumElementForm->getSelectedLabels();
        if ($selectedLabels) {
            foreach ($selectedLabels as $selectedLabelId) {
                $label = $this->imageDao->getLabel($selectedLabelId);
                $this->photoAlbumElement->addLabel($label);
            }
        }
    }

    private function removeSelectedLabels(): void {
        foreach ($this->photoAlbumElementForm->getLabelsToRemove() as $label) {
            $this->photoAlbumElement->removeLabel($label);
        }
    }
}