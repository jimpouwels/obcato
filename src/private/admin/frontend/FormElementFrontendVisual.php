<?php

namespace Obcato\Core;

class FormElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, FormElement $formElement) {
        parent::__construct($page, $article, $formElement);
    }

    public function loadElement(): void {
        $formVisual = new FormFrontendVisual($this->getPage(), $this->getArticle(), $this->getElement()->getWebForm());
        $this->assign('webform', $formVisual->render());
    }

}