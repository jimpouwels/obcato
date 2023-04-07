<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/information_message.php";

    class ImageViewer extends Panel {

        private static $TEMPLATE = "images/images/viewer.tpl";

        private $_current_image;

        public function __construct($current_image) {
            parent::__construct('Afbeelding', 'image_editor');
            $this->_current_image = $current_image;
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign("title", $this->_current_image->getTitle());
            $this->getTemplateEngine()->assign("url", $this->_current_image->getUrl());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }
    }
