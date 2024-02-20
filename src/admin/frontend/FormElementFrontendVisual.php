<?php

namespace Obcato\Core\admin\frontend;

use Obcato\Core\admin\elements\form_element\FormElement;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\pages\model\Page;

class FormElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, FormElement $formElement) {
        parent::__construct($page, $article, $formElement);
    }

    public function loadElement(): void {
        $formVisual = new FormFrontendVisual($this->getPage(), $this->getArticle(), $this->getElement()->getWebForm());
        $this->assign('webform', $formVisual->render());
    }

}