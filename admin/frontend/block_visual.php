<?php
    defined ('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/frontend_visual.php";

    class BlockVisual extends FrontendVisual {

        private $_block;
        private $_template_engine;
        private $_current_page;

        public function __construct($block, $current_page) {
            parent::__construct($current_page);
            $this->_block = $block;
            $this->_current_page = $current_page;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render(): string {
            $this->_template_engine->assign('id', $this->_block->getId());
            $this->_template_engine->assign('title', $this->_block->getTitle());
            $this->_template_engine->assign('elements', $this->renderElements());
            return $this->_template_engine->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->_block->getTemplate()->getFileName());
        }

        private function renderElements() {
            $elements_content = array();
            foreach ($this->_block->getElements() as $element) {
                $elements_content[] = $element->getFrontendVisual($this->_current_page)->render();
            }
            return $elements_content;
        }
    }