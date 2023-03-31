<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/information_message.php";

    class ElementContainer extends Panel {

        private static $TEMPLATE = "system/element_container.tpl";

        private $_template_engine;
        private $_elements;

        public function __construct($elements) {
            parent::__construct($this->getTextResource('element_holder_content_title'), 'element_container');
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_elements = $elements;
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            if (count($this->_elements) > 0)
                $this->_template_engine->assign("elements", $this->renderElements());
            else
                $this->_template_engine->assign("message", $this->renderInformationMessage());
            return $this->_template_engine->fetch(self::$TEMPLATE);
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
