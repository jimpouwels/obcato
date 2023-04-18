<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/information_message.php";

    class ImageViewer extends Panel {

        private static string $TEMPLATE = "images/images/viewer.tpl";

        private Image $_current_image;

        public function __construct(Image $current_image) {
            parent::__construct('Afbeelding', 'image_editor');
            $this->_current_image = $current_image;
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $this->getTemplateEngine()->assign("title", $this->_current_image->getTitle());
            $this->getTemplateEngine()->assign("url", $this->_current_image->getUrl());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }
    }
