<?php

namespace Obcato\Core\frontend;

use Obcato\Core\elements\form_element\FormElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;

class FormElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, FormElement $formElement) {
        parent::__construct($page, $article, $formElement);
    }

    public function loadElement(): void {
        $formVisual = new FormFrontendVisual($this->getPage(), $this->getArticle(), $this->getElement()->getWebForm());
        $this->assign('webform', $formVisual->render());
    }

}