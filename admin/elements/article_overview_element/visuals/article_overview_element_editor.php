<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/element_visual.php";
    require_once CMS_ROOT . "view/views/form_textfield.php";
    require_once CMS_ROOT . "view/views/form_date.php";
    require_once CMS_ROOT . "view/views/term_selector.php";

    class ArticleOverviewElementEditor extends ElementVisual {
    
        private static string $TEMPLATE = "elements/article_overview_element/article_overview_element_form.tpl";
        private ArticleOverviewElement $_element;
    
        public function __construct(ArticleOverviewElement $_element) {
            parent::__construct();
            $this->_element = $_element;
        }
    
        public function getElement(): Element {
            return $this->_element;
        }
        
        public function renderElementForm(): string {
            $title_field = new TextField("element_" . $this->_element->getId() . "_title", "Titel", $this->_element->getTitle(), false, true, null);
            $show_from_field = new DateField("element_" . $this->_element->getId() . "_show_from", "Publicatiedatum vanaf", $this->getDateValue($this->_element->getShowFrom()), false, "datepicker");
            $show_to_field = new DateField("element_" . $this->_element->getId() . "_show_to", "Publicatiedatum tot", $this->getDateValue($this->_element->getShowTo()), false, "datepicker");
            $max_results_field = new TextField("element_" . $this->_element->getId() . "_number_of_results", "Max. aantal resultaten", $this->_element->getNumberOfResults(), false, true, "number_of_results_field");
            $order_by_field = new Pulldown("element_" . $this->_element->getId() . "_order_by", "Sorteren op", $this->_element->getOrderBy(), $this->getOrderOptions(), false, "");
            $order_type_field = new Pulldown("element_" . $this->_element->getId() . "_order_type", "Volgorde", $this->_element->getOrderType(), $this->getOrderTypeOptions(), false, "");
            $term_select_field = new TermSelector($this->_element->getTerms(), $this->_element->getId());

            $this->getTemplateEngine()->assign("title_field", $title_field->render());
            $this->getTemplateEngine()->assign("show_from_field", $show_from_field->render());
            $this->getTemplateEngine()->assign("show_to_field", $show_to_field->render());
            $this->getTemplateEngine()->assign("max_results_field", $max_results_field->render());
            $this->getTemplateEngine()->assign("order_by_field", $order_by_field->render());
            $this->getTemplateEngine()->assign("order_type_field", $order_type_field->render());
            $this->getTemplateEngine()->assign("term_select_field", $term_select_field->render());
            
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
        
        private function getDateValue(?string $date): ?string {            
            return $date == null ? null : DateUtility::mysqlDateToString($date, '-');
        }
        
        private function getOrderOptions(): array {
            $order_options = array();
            array_push($order_options, array('name' => 'Publicatiedatum', 'value' => 'PublicationDate'));
            array_push($order_options, array('name' => 'Sorteerdatum', 'value' => 'SortDate'));
            array_push($order_options, array('name' => 'Alfabet', 'value' => 'Alphabet'));
            return $order_options;
        }

        private function getOrderTypeOptions(): array {
            $order_options = array();
            array_push($order_options, array('name' => 'Oplopend', 'value' => 'asc'));
            array_push($order_options, array('name' => 'Aflopend', 'value' => 'desc'));
            return $order_options;
        }
    
    }
    
?>