<?php
    defined ('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/frontend_visual.php";

    class BlockVisual extends FrontendVisual {

        private Block $_block;

        public function __construct(Block $block, Page $page) {
            parent::__construct($page, null);
            $this->_block = $block;
        }

        public function render(): string {
            $this->getTemplateEngine()->assign('id', $this->_block->getId());
            $this->getTemplateEngine()->assign('title', $this->_block->getTitle());
            $this->getTemplateEngine()->assign('elements', $this->renderElements());
            return $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->_block->getTemplate()->getFileName());
        }

        private function renderElements(): array {
            $elements_content = array();
            foreach ($this->_block->getElements() as $element) {
                $elements_content[] = $element->getFrontendVisual($this->getPage(), null)->render();
            }
            return $elements_content;
        }
    }