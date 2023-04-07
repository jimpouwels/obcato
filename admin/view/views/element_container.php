<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/information_message.php";

    class ElementContainer extends Panel {

        private static $TEMPLATE = "system/element_container.tpl";
        private $_elements;

        public function __construct($elements) {
            parent::__construct($this->getTextResource('element_holder_content_title'), 'element_container');
            $this->_elements = $elements;
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            if (count($this->_elements) > 0)
                $this->getTemplateEngine()->assign("elements", $this->renderElements());
            else
                $this->getTemplateEngine()->assign("message", $this->renderInformationMessage());
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }

        private function renderInformationMessage() {
            $information_message = new InformationMessage($this->getTextResource('no_elements_found_message'));
            return $information_message->render();
        }

        private function renderElements() {
            $elements = array();
            foreach ($this->_elements as $element) {
                $elements[] = $element->getBackendVisual()->render();
            }
            return $elements;
        }
    }

?>
