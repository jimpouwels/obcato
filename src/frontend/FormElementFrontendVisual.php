<?php

namespace Obcato\Core\frontend;

use Obcato\Core\elements\form_element\FormElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;

class FormElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ?Block $block, FormElement $formElement) {
        parent::__construct($page, $article, $block, $formElement);
    }

    public function loadElement(array &$data): void {
        $formVisual = new FormFrontendVisual($this->getPage(), $this->getArticle(), $this->getElement()->getWebForm());
        $data['webform'] = $formVisual->render();
    }

}