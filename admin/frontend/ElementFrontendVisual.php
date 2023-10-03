<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/frontend/FrontendVisual.php";
require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";

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