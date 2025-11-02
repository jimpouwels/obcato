<?php

namespace Obcato\Core\elements\photo_album_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\elements\photo_album_element\visuals\PhotoAlbumElementEditor;
use Obcato\Core\elements\photo_album_element\visuals\PhotoAlbumElementStatics;
use Obcato\Core\frontend\FrontendVisual;
use Obcato\Core\frontend\PhotoAlbumElementFrontendVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\images\model\ImageLabel;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\request_handlers\HttpRequestHandler;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\Visual;

class PhotoAlbumElement extends Element {
    private array $labels;
    private array $imageIds = array();
    private ?int $numberOfResults = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new PhotoAlbumElementMetadataProvider($this));
        $this->labels = array();
    }

    public function setNumberOfResults(?int $numberOfResults): void {
        $this->numberOfResults = $numberOfResults;
    }

    public function getNumberOfResults(): ?int {
        return $this->numberOfResults;
    }

    public function addLabel(ImageLabel $label): void {
        $this->labels[] = $label;
    }

    public function removeLabel(ImageLabel $label): void {
        if (($key = array_search($label, $this->labels, true)) !== false) {
            unset($this->labels[$key]);
        }
    }

    public function setLabels(array $labels): void {
        $this->labels = $labels;
    }

    public function getLabels(): array {
        return $this->labels;
    }

    public function addImage(int $imageId): void {
        $this->imageIds[] = $imageId;
    }

    public function deleteImage(int $imageIdToDelete): void {
        $this->imageIds = array_filter($this->imageIds, fn($imageId) => $imageId != $imageIdToDelete);
    }

    public function getImageIds(): array {
        return $this->imageIds;
    }

    public function setImageIds(array $imageIds): void {
        $this->imageIds = $imageIds;
    }

    public function getImages(): array {
        $results = array();
        $imageDao = ImageDaoMysql::getInstance();
        if (count($this->labels) > 0) {
            $results = $imageDao->searchImagesByLabels($this->labels);
        }
        if (count($this->imageIds) > 0) {
            foreach ($this->imageIds as $imageId) {
                $results[] = $imageDao->getImage($imageId);
            }
        }
        if ($this->getNumberOfResults()) {
            $results = array_slice($results, 0, $this->getNumberOfResults());
        }
        return $results;
    }

    public function getStatics(): Visual {
        return new PhotoAlbumElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new PhotoAlbumElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article, ?Block $block = null): FrontendVisual {
        return new PhotoAlbumElementFrontendVisual($page, $article, $block, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new PhotoAlbumElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        $summaryText = $this->getTitle() || '';
        if ($this->getLabels()) {
            $summaryText .= " (Labels:";
            foreach ($this->getLabels() as $label) {
                $summaryText .= " " . $label->getName();
            }
            $summaryText .= ")";
        }
        return $summaryText;
    }
}

