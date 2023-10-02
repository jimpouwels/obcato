<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/frontend/ElementVisual.php';
require_once CMS_ROOT . '/frontend/FormVisual.php';
require_once CMS_ROOT . '/frontend/handlers/FormStatus.php';

class FormElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, FormElement $form_element) {
        parent::__construct($page, $article, $form_element);
    }

    public function loadElement(): void {
        $form_visual = new FormFrontendVisual($this->getPage(), $this->getArticle(), $this->getElement()->getWebForm());
        $this->assign('webform', $form_visual->render());
    }

}