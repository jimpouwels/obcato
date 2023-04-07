<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "view/views/form_checkbox_single.php";
    require_once CMS_ROOT . "view/views/form_template_picker.php";
    
    abstract class ElementVisual extends Visual {
    
        private static $TEMPLATE = "system/element.tpl";
    
        abstract function getElement(): Element;
        
        abstract function renderElementForm();
    
        public function renderVisual(): string {
            $element = $this->getElement();
            $template_picker = new TemplatePicker("element_" . $element->getId() . "_template", "", false, "template_picker", $element->getTemplate(), $element->getType()->getScope());
            $template_engine = TemplateEngine::getInstance();
            
            $template_engine->assign("element_form", $this->renderElementForm());
            $template_engine->assign("index", $element->getIndex());
            $template_engine->assign("id", $element->getId());
            $template_engine->assign("icon_url", '/admin/static.php?file=/elements/' . $element->getType()->getIdentifier() . '/' . $element->getType()->getIconUrl());
            $template_engine->assign("type", $element->getType()->getName());
            $template_engine->assign("template_picker", $template_picker->render());
            
            $table_of_contents_html = "";
            if ($element->getType()->getName() != 'Inhoudsopgave') {
                $include_in_table_of_contents_field = new SingleCheckbox("element_" . $element->getId() . "_toc", $this->getTextResource("element_include_in_table_of_contents"), $element->includeInTableOfContents() ? 1 : 0, false, "element_include_in_toc");
                $table_of_contents_html = $include_in_table_of_contents_field->render();
            }
            $template_engine->assign("include_in_table_of_contents", $table_of_contents_html);
            $template_engine->assign("identifier", $element->getType()->getIdentifier());
            $template_engine->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
            $template_engine->assign("summary_text", $element->getSummaryText());
            return $template_engine->fetch(self::$TEMPLATE);
        }
    }

?>