<?php

namespace Obcato\Core\elements\photo_album_element;

use Obcato\Core\core\form\FormException;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\elements\ElementContainsErrorsException;
use Obcato\Core\request_handlers\HttpRequestHandler;

class PhotoAlbumElementRequestHandler extends HttpRequestHandler {

    private PhotoAlbumElement $photoAlbumElement;
    private ElementDao $elementDao;
    private PhotoAlbumElementForm $photoAlbumElementForm;

    public function __construct(PhotoAlbumElement $photoAlbumElement) {
        $this->photoAlbumElement = $photoAlbumElement;
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->photoAlbumElementForm = new PhotoAlbumElementForm($photoAlbumElement);
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $this->photoAlbumElementForm->loadFields();
            $this->elementDao->updateElement($this->photoAlbumElement);
        } catch (FormException) {
            throw new ElementContainsErrorsException("Photo album element contains errors");
        }
    }
}