<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "frontend/frontend_visual.php";
require_once CMS_ROOT . "database/dao/template_dao.php";

abstract class ElementFrontendVisual extends FrontendVisual {

    private Element $_element;
    private TemplateDao $_template_dao;

    public function __construct(Page $page, ?Article $article, Element $element) {
        parent::__construct($page, $article);
        $this->_element = $element;
        $this->_template_dao = TemplateDao::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/element.tpl";
    }

    public function getElementTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->_template_dao->getTemplateFile($this->getElement()->getTemplate()->getTemplateFileId())->getFileName();
    }

    abstract function loadElement(): void;

    public function loadVisual(?array &$data): void {
        $this->assign("toc_reference", $this->toAnchorValue($this->_element->getTitle()));
        $this->assign("include_in_table_of_contents", $this->_element->includeInTableOfContents());
        $this->assign("type", $this->_element->getType()->getIdentifier());

        $this->loadElement();
        $this->assign("element_html", $this->fetch($this->getElementTemplateFilename()));
    }

    public function getPresentable(): ?Presentable {
        return $this->_element;
    }

    protected function getElement(): Element {
        return $this->_element;
    }

}