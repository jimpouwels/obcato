<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class IFrameElementFrontendVisual extends ElementFrontendVisual {

        public function __construct(Page $page, ?Article $article, IFrameElement $iframe_element) {
            parent::__construct($page, $article, $iframe_element);
        }

        public function loadElement(Smarty_Internal_Data $data): void {
            $element_holder = $this->getElement()->getElementHolder();
            $data->assign("title", $this->getElement()->getTitle());
            $data->assign("url", $this->toHtml($this->getElement()->getUrl(), $element_holder));
            $data->assign("height", $this->getElement()->getHeight());
            $data->assign("width", $this->getElement()->getWidth());
        }

    }