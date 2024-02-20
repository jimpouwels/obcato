<?php

namespace Obcato\Core\admin\frontend;

use Obcato\Core\admin\core\model\Element;
use Obcato\Core\admin\database\dao\ElementDao;
use Obcato\Core\admin\database\dao\ElementDaoMysql;
use Obcato\Core\admin\database\dao\TemplateDao;
use Obcato\Core\admin\database\dao\TemplateDaoMysql;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\modules\templates\model\Presentable;
use const Obcato\Core\admin\FRONTEND_TEMPLATE_DIR;

abstract class ElementFrontendVisual extends FrontendVisual {

    private Element $element;
    private TemplateDao $templateDao;
    private ElementDao $elementDao;

    public function __construct(Page $page, ?Article $article, Element $element) {
        parent::__construct($page, $article);
        $this->element = $element;
        $this->templateDao = TemplateDaoMysql::getInstance();
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/element.tpl";
    }

    public function getElementTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->templateDao->getTemplateFile($this->getElement()->getTemplate()->getTemplateFileId())->getFileName();
    }

    abstract function loadElement(): void;

    public function loadVisual(?array &$data): void {
        $this->assign("toc_reference", $this->toAnchorValue($this->element->getTitle()));
        $this->assign("include_in_table_of_contents", $this->element->includeInTableOfContents());
        $this->assign("type", $this->elementDao->getElementTypeForElement($this->element->getId())->getIdentifier());

        $this->loadElement();
        $this->assign("element_html", $this->fetch($this->getElementTemplateFilename()));
    }

    public function getPresentable(): ?Presentable {
        return $this->element;
    }

    protected function getElement(): Element {
        return $this->element;
    }

}