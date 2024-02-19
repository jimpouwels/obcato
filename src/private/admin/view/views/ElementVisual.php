<?php
require_once CMS_ROOT . "/view/views/TemplatePicker.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";

abstract class ElementVisual extends Obcato\ComponentApi\Visual {

    private ElementDao $elementDao;

    abstract function getElement(): Element;

    abstract function loadElementForm(TemplateData $data): void;

    abstract function getElementFormTemplateFilename(): string;

    public function __construct(TemplateEngine $templateEngine,) {
        parent::__construct($templateEngine);
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "system/element.tpl";
    }

    public function load(): void {
        $element = $this->getElement();
        $elementType = $this->elementDao->getElementTypeForElement($element->getId());

        $templatePicker = new TemplatePicker($this->getTemplateEngine(), "element_" . $element->getId() . "_template", "", false, "template_picker", $element->getTemplate(), $elementType->getScope());

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
            $includeInTocField = new SingleCheckbox($this->getTemplateEngine(), "element_" . $element->getId() . "_toc", $this->getTextResource("element_include_in_table_of_contents"), $element->includeInTableOfContents() ? 1 : 0, false, "element_include_in_toc");
            $tableOfContentsHtml = $includeInTocField->render();
        }
        $this->assign("include_in_table_of_contents", $tableOfContentsHtml);
        $this->assign("identifier", $elementType->getIdentifier());
        $this->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
        $this->assign("summary_text", $element->getSummaryText());
    }
}