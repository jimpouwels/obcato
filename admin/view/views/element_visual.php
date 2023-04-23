<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "view/views/form_checkbox_single.php";
    require_once CMS_ROOT . "view/views/form_template_picker.php";
    
    abstract class ElementVisual extends Visual {
    
        private static string $TEMPLATE = "system/element.tpl";
    
        abstract function getElement(): Element;
        
        abstract function renderElementForm(): string;
    
        public function render(): string {
            $element = $this->getElement();
            $template_picker = new TemplatePicker("element_" . $element->getId() . "_template", "", false, "template_picker", $element->getTemplate(), $element->getType()->getScope());
            
            $this->getTemplateEngine()->assign("element_form", $this->renderElementForm());
            $this->getTemplateEngine()->assign("index", $element->getIndex());
            $this->getTemplateEngine()->assign("id", $element->getId());
            $this->getTemplateEngine()->assign("icon_url", '/admin/static.php?file=/elements/' . $element->getType()->getIdentifier() . '/' . $element->getType()->getIconUrl());
            $this->getTemplateEngine()->assign("type", $this->getTextResource($element->getType()->getIdentifier() . '_label'));
            $this->getTemplateEngine()->assign("template_picker", $template_picker->render());
            
            $table_of_contents_html = "";
            if ($element->getType()->getIdentifier() != 'table_of_contents_element') {
                $include_in_table_of_contents_field = new SingleCheckbox("element_" . $element->getId() . "_toc", $this->getTextResource("element_include_in_table_of_contents"), $element->includeInTableOfContents() ? 1 : 0, false, "element_include_in_toc");
                $table_of_contents_html = $include_in_table_of_contents_field->render();
            }
            $this->getTemplateEngine()->assign("include_in_table_of_contents", $table_of_contents_html);
            $this->getTemplateEngine()->assign("identifier", $element->getType()->getIdentifier());
            $this->getTemplateEngine()->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
            $this->getTemplateEngine()->assign("summary_text", $element->getSummaryText());

            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    }

?>