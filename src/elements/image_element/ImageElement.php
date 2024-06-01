<?php

namespace Obcato\Core\elements\image_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\database\MysqlConnector;
use Obcato\Core\elements\image_element\visuals\ImageElementEditor;
use Obcato\Core\elements\image_element\visuals\ImageElementStatics;
use Obcato\Core\frontend\ImageElementFrontendVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\images\model\Image;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\request_handlers\HttpRequestHandler;
use Obcato\Core\view\TemplateEngine;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\Visual;

class ImageElement extends Element {

    private ?string $align = null;
    private ?int $height = null;
    private ?int $width = null;
    private ?int $imageId = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new ImageElementMetadataProvider($this));
    }

    public function setAlign(?string $align): void {
        $this->align = $align;
    }

    public function getAlign(): ?string {
        return $this->align;
    }

    public function getWidth(): ?int {
        return $this->width;
    }

    public function setWidth(?int $width): void {
        $this->width = $width;
    }

    public function getHeight(): ?int {
        return $this->height;
    }

    public function setHeight(?int $height): void {
        $this->height = $height;
    }

    public function setImageId(?int $image_id): void {
        $this->imageId = $image_id;
    }

    public function getImageId(): ?int {
        return $this->imageId;
    }

    public function getImage(): ?Image {
        if ($this->imageId != null) {
            $image_dao = ImageDaoMysql::getInstance();
            return $image_dao->getImage($this->imageId);
        }
        return null;
    }

    public function getStatics(): Visual {
        return new ImageElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new ImageElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): ImageElementFrontendVisual {
        return new ImageElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new ImageElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        $summary_text = $this->getTitle() ?: '';
        $image = $this->getImage();
        if ($image) {
            $summary_text .= ': ' . $image->getTitle() . ' (' . $image->getFilename() . ')';
        }
        return $summary_text;
    }
}
