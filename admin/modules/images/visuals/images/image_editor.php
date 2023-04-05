<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . 'modules/images/visuals/images/image_metadata_editor.php';
    require_once CMS_ROOT . 'modules/images/visuals/images/image_viewer.php';
    require_once CMS_ROOT . 'view/views/image_label_selector.php';

    class ImageEditor extends Visual {

        private static $TEMPLATE = "images/images/editor.tpl";

        private $_template_engine;
        private $_current_image;
        private $_image_dao;

        public function __construct($current_image) {
            $this->_current_image = $current_image;
            $this->_image_dao = ImageDao::getInstance();
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render(): string {
            $this->assignMetadataEditor();
            $this->assignLabelSelector();
            $this->assignImageViewer();
            $this->_template_engine->assign("current_image_id", $this->_current_image->getId());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }

        private function assignMetadataEditor() {
            $metadata_editor = new ImageMetadataEditor($this->_current_image);
            $this->_template_engine->assign('metadata_editor', $metadata_editor->render());
        }

        private function assignLabelSelector() {
            $image_labels = $this->_image_dao->getLabelsForImage($this->_current_image->getId());
            $label_select = new ImageLabelSelector($image_labels, $this->_current_image->getId());
            $this->_template_engine->assign('label_selector', $label_select->render());
        }

        private function assignImageViewer() {
            $image_viewer = new ImageViewer($this->_current_image);
            $this->_template_engine->assign('image_viewer', $image_viewer->render());
        }
    }
