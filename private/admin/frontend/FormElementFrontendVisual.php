<?php
require_once CMS_ROOT . '/frontend/ElementFrontendVisual.php';
require_once CMS_ROOT . '/frontend/FormFrontendVisual.php';
require_once CMS_ROOT . '/frontend/handlers/FormStatus.php';

class FormElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, FormElement $formElement) {
        parent::__construct($page, $article, $formElement);
    }

    public function loadElement(): void {
        $formVisual = new FormFrontendVisual($this->getPage(), $this->getArticle(), $this->getElement()->getWebForm());
        $this->assign('webform', $formVisual->render());
    }

}