<?php

namespace Obcato\Core\frontend;

use Obcato\Core\frontend\handlers\FormStatus;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\templates\model\Presentable;
use Obcato\Core\modules\webforms\model\Webform;
use Obcato\Core\modules\webforms\model\WebformItem;
use const Obcato\core\FRONTEND_TEMPLATE_DIR;

abstract class FormItemVisual extends FrontendVisual {

    private WebFormItem $webformIte;

    public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormItem $webformItem) {
        parent::__construct($page, $article);
        $this->webformIte = $webformItem;
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . '/form_item.tpl';
    }

    public function loadVisual(?array &$data): void {
        $this->assign('label', $this->getFormItem()->getLabel());
        $this->assign('name', $this->getFormItem()->getName());
        $this->assign('value', FormStatus::getFieldValue($this->getFormItem()->getName()));
        $this->assign('has_error', FormStatus::getError($this->getFormItem()->getName()) != null);

        $this->loadFormItem();
        $this->assign('form_item_html', $this->fetch($this->getFormItemTemplateFilename()));
    }

    protected function getFormItem(): WebFormItem {
        return $this->webformIte;
    }

    abstract function loadFormItem(): void;

    abstract function getFormItemTemplateFilename(): string;

    public function getPresentable(): ?Presentable {
        return $this->webformIte;
    }
}