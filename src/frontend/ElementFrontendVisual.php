<?php

namespace Obcato\Core\frontend;

use Obcato\Core\core\model\Element;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\database\dao\TemplateDao;
use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\templates\model\Presentable;
use const Obcato\core\FRONTEND_TEMPLATE_DIR;

abstract class ElementFrontendVisual extends FrontendVisual {

    private Element $element;
    private TemplateDao $templateDao;
    private ElementDao $elementDao;

    public function __construct(Page $page, ?Article $article, ?Block $block, Element $element) {
        parent::__construct($page, $article, $block);
        $this->element = $element;
        $this->templateDao = TemplateDaoMysql::getInstance();
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/system/element.tpl";
    }

    public function getElementTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->templateDao->getTemplateFile($this->getElement()->getTemplate()->getTemplateFileId())->getFileName();
    }

    abstract function loadElement(array &$data): void;

    public function loadVisual(array &$data): void {
        $this->assign("toc_reference", $this->toAnchorValue($this->element->getTitle()));
        $this->assign("include_in_table_of_contents", $this->element->includeInTableOfContents());

        $this->loadElement($data);
        if ($data) {
            foreach ($data as $key => $value) {
                $this->assign($key, $value);
            }
        }
        $this->assign("element_html", "");
        if ($this->getElement()->getTemplate()) {
            $this->assign("element_html", $this->fetch($this->getElementTemplateFilename()));
        }
    }

    public function getPresentable(): ?Presentable {
        return $this->element;
    }

    protected function getElement(): Element {
        return $this->element;
    }

}