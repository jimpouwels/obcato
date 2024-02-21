<?php

namespace Obcato\Core\admin\modules\images\visuals\images;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\database\dao\ImageDao;
use Obcato\Core\admin\database\dao\ImageDaoMysql;
use Obcato\Core\admin\modules\images\model\Image;
use Obcato\Core\admin\view\views\ImageLabelSelector;

class ImageEditor extends Visual {
    private Image $currentImage;
    private ImageDao $imageDao;

    public function __construct(TemplateEngine $templateEngine, Image $current) {
        parent::__construct($templateEngine);
        $this->currentImage = $current;
        $this->imageDao = ImageDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "modules/images/images/editor.tpl";
    }

    public function load(): void {
        $this->assignMetadataEditor();
        $this->assignLabelSelector();
        $this->assignImageViewer();
        $this->assign("current_image_id", $this->currentImage->getId());
    }

    private function assignMetadataEditor(): void {
        $metadataEditor = new ImageMetadataEditor($this->getTemplateEngine(), $this->currentImage);
        $this->assign('metadata_editor', $metadataEditor->render());
    }

    private function assignLabelSelector(): void {
        $imageLabels = $this->imageDao->getLabelsForImage($this->currentImage->getId());
        $labelSelect = new ImageLabelSelector($this->getTemplateEngine(), $imageLabels, $this->currentImage->getId());
        $this->assign('label_selector', $labelSelect->render());
    }

    private function assignImageViewer(): void {
        $imageViewer = new ImageViewer($this->getTemplateEngine(), $this->currentImage);
        $this->assign('image_viewer', $imageViewer->render());
    }
}
