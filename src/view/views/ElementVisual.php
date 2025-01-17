<?php

namespace Obcato\Core\view\views;

use Obcato\Core\core\model\Element;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\database\dao\LinkDao;
use Obcato\Core\database\dao\LinkDaoMysql;
use Obcato\Core\utilities\StringUtility;
use Obcato\Core\view\TemplateData;
use const Obcato\Core\DELETE_ELEMENT_FORM_ID;

abstract class ElementVisual extends Visual {

    private ElementDao $elementDao;
    private LinkDao $linkDao;

    abstract function includeLinkSelector(): bool;

    abstract function getElement(): Element;

    abstract function loadElementForm(TemplateData $data): void;

    abstract function getElementFormTemplateFilename(): string;

    public function __construct() {
        parent::__construct();
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->linkDao = LinkDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "system/element.tpl";
    }

    public function load(): void {
        $element = $this->getElement();
        $elementType = $this->elementDao->getElementTypeForElement($element->getId());

        $templatePicker = new TemplatePicker("element_" . $element->getId() . "_template", "", false, "template_picker", $element->getTemplate(), $elementType->getScope());

        $panelContentTemplateData = $this->createChildData();
        $this->loadElementForm($panelContentTemplateData);
        $this->assign("element_form", $this->fetch($this->getElementFormTemplateFilename(), $panelContentTemplateData));
        $this->assign("index", $element->getOrderNr());
        $this->assign("id", $element->getId());
        $this->assign("icon_url", '/admin/static.php?file=/elements/' . $elementType->getIdentifier() . '/img/' . $elementType->getIdentifier() . ".png");
        $this->assign("type", $this->getTextResource($elementType->getIdentifier() . '_label'));
        $this->assign("template_picker", $templatePicker->render());

        $tableOfContentsHtml = "";
        if ($elementType->getIdentifier() != 'table_of_contents_element') {
            $includeInTocField = new SingleCheckbox("element_" . $element->getId() . "_toc", $this->getTextResource("element_include_in_table_of_contents"), $element->includeInTableOfContents() ? 1 : 0, false, "element_include_in_toc");
            $tableOfContentsHtml = $includeInTocField->render();
        }
        $this->assign("include_in_table_of_contents", $tableOfContentsHtml);
        $this->assign("identifier", $elementType->getIdentifier());
        $this->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
        $this->assign("summary_text", StringUtility::escapeXml($element->getSummaryText()));

        $this->assign("link_options", "");
        if ($this->includeLinkSelector()) {
            $this->assign("link_options", $this->getLinkOptions());
        }
    }
    protected function getLinkOptions(): array {
        $linkOptions = array();
        foreach ($this->linkDao->getLinksForElementHolder($this->getElement()->getElementHolderId()) as $link) {
            $linkOptions[] = array('name' => $link->getTitle(), 'value' => $link->getId());
        }
        return $linkOptions;
    }
}