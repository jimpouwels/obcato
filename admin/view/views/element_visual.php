<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/form_template_picker.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";

abstract class ElementVisual extends Visual {

    private ElementDao $elementDao;

    abstract function getElement(): Element;

    abstract function renderElementForm(Smarty_Internal_Data $data): string;

    public function __construct(?Visual $parent = null) {
        parent::__construct($parent);
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "system/element.tpl";
    }

    public function load(): void {
        $element = $this->getElement();
        $elementType = $this->elementDao->getElementTypeForElement($element->getId());

        $template_picker = new TemplatePicker("element_" . $element->getId() . "_template", "", false, "template_picker", $element->getTemplate(), $elementType->getScope());

        $panel_content_template_data = $this->getTemplateEngine()->createChildData();
        $this->assign("element_form", $this->renderElementForm($panel_content_template_data));
        $this->assign("index", $element->getOrderNr());
        $this->assign("id", $element->getId());
        $this->assign("icon_url", '/admin/static.php?file=/elements/' . $elementType->getIdentifier() . '/' . $elementType->getIconUrl());
        $this->assign("type", $this->getTextResource($elementType->getIdentifier() . '_label'));
        $this->assign("template_picker", $template_picker->render());

        $table_of_contents_html = "";
        if ($elementType->getIdentifier() != 'table_of_contents_element') {
            $include_in_table_of_contents_field = new SingleCheckbox("element_" . $element->getId() . "_toc", $this->getTextResource("element_include_in_table_of_contents"), $element->includeInTableOfContents() ? 1 : 0, false, "element_include_in_toc");
            $table_of_contents_html = $include_in_table_of_contents_field->render();
        }
        $this->assign("include_in_table_of_contents", $table_of_contents_html);
        $this->assign("identifier", $elementType->getIdentifier());
        $this->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
        $this->assign("summary_text", $element->getSummaryText());
    }
}