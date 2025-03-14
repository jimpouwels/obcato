<?php

namespace Obcato\Core\modules\images\visuals\images;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\modules\images\model\Image;
use Obcato\Core\view\views\ImageLabelSelector;
use Obcato\Core\view\views\TextField;
use Obcato\Core\view\views\Visual;

class ImageEditor extends Visual {
    private Image $currentImage;
    private ImageDao $imageDao;

    public function __construct(Image $current) {
        parent::__construct();
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
        $metadataEditor = new ImageMetadataEditor($this->currentImage);
        $this->assign('metadata_editor', $metadataEditor->render());
    }

    private function assignLabelSelector(): void {
        $imageLabels = $this->imageDao->getLabelsForImage($this->currentImage->getId());
        $labelSelect = new ImageLabelSelector($imageLabels, $this->currentImage->getId(), true);
        $this->assign('label_selector', $labelSelect->render());
    }

    private function assignImageViewer(): void {
        $imageViewer = new ImageViewer($this->currentImage);
        $this->assign('image_viewer', $imageViewer->render());
    }
}
