<?php
    defined ('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/frontend_visual.php";
    require_once CMS_ROOT . 'database/dao/element_dao.php';

    class BlockVisual extends FrontendVisual {

        private Block $_block;

        public function __construct(Block $block, Page $page) {
            parent::__construct($page, null);
            $this->_block = $block;
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->_block->getTemplate()->getFileName();
        }

        public function load(): void {
            $this->assign('id', $this->_block->getId());
            $this->assign('title', $this->_block->getTitle());
            $this->assign('elements', $this->renderElements());
        }

        private function renderElements(): array {
            $elements_content = array();
            foreach ($this->_block->getElements() as $element) {
                $elements_content[] = $element->getFrontendVisual($this->getPage(), null)->render();
            }
            return $elements_content;
        }
    }