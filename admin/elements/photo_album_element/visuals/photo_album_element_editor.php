<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/element_visual.php";
    require_once CMS_ROOT . "view/views/form_textfield.php";
    require_once CMS_ROOT . "view/views/form_date.php";
    require_once CMS_ROOT . "view/views/image_label_selector.php";

    class PhotoAlbumElementEditor extends ElementVisual {
    
        private static $TEMPLATE = "elements/photo_album_element/photo_album_element_form.tpl";
        
        private $_template_engine;
        private $_element;
    
        public function __construct($_element) {
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_element = $_element;
        }
    
        public function getElement() {
            return $this->_element;
        }
        
        public function renderElementForm() {
            $title_field = new TextField("element_" . $this->_element->getId() . "_title", "Titel", $this->_element->getTitle(), false, true, null);
            $max_results_field = new TextField("element_" . $this->_element->getId() . "_number_of_results", "Max. aantal resultaten", $this->_element->getNumberOfResults(), false, true, "number_of_results_field");
            $label_select_field = new ImageLabelSelector($this->_element->getLabels(), $this->_element->getId());

            $this->_template_engine->assign("title_field", $title_field->render());
            $this->_template_engine->assign("max_results_field", $max_results_field->render());
            $this->_template_engine->assign("label_select_field", $label_select_field->render());
            
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
        
    }
    
?>