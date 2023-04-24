<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . 'modules/images/visuals/images/image_metadata_editor.php';
    require_once CMS_ROOT . 'modules/images/visuals/images/image_viewer.php';
    require_once CMS_ROOT . 'view/views/image_label_selector.php';

    class ImageEditor extends Visual {
        private Image $_current_image;
        private ImageDao $_image_dao;

        public function __construct(Image $current_image) {
            parent::__construct();
            $this->_current_image = $current_image;
            $this->_image_dao = ImageDao::getInstance();
        }

        public function getTemplateFilename(): string {
            return "modules/images/images/editor.tpl";
        }
        
        public function load(): void {
            $this->assignMetadataEditor();
            $this->assignLabelSelector();
            $this->assignImageViewer();
            $this->assign("current_image_id", $this->_current_image->getId());
        }

        private function assignMetadataEditor(): void {
            $metadata_editor = new ImageMetadataEditor($this->_current_image);
            $this->assign('metadata_editor', $metadata_editor->render());
        }

        private function assignLabelSelector(): void {
            $image_labels = $this->_image_dao->getLabelsForImage($this->_current_image->getId());
            $label_select = new ImageLabelSelector($image_labels, $this->_current_image->getId());
            $this->assign('label_selector', $label_select->render());
        }

        private function assignImageViewer(): void {
            $image_viewer = new ImageViewer($this->_current_image);
            $this->assign('image_viewer', $image_viewer->render());
        }
    }
