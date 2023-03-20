<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/information_message.php";

    class ImageViewer extends Panel {

        private static $TEMPLATE = "images/images/viewer.tpl";

        private $_template_engine;
        private $_current_image;

        public function __construct($current_image) {
            parent::__construct('Afbeelding', 'image_editor');
            $this->_current_image = $current_image;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign("title", $this->_current_image->getTitle());
            $this->_template_engine->assign("url", $this->_current_image->getUrl());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }
    }