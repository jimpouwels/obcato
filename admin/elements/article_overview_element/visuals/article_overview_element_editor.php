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
            $data = $this->getTemplateEngine()->createChildData();
            $title_field = new TextField("element_" . $this->_element->getId() . "_title", $this->getTextResource("article_overview_element_editor_title"), $this->_element->getTitle(), false, true, null);
            $data->assign("title_field", $title_field->render());
            $show_from_field = new DateField("element_" . $this->_element->getId() . "_show_from", $this->getTextResource("article_overview_element_editor_publication_date_from"), $this->getDateValue($this->_element->getShowFrom()), false, "datepicker");
            $data->assign("show_from_field", $show_from_field->render());
            $show_to_field = new DateField("element_" . $this->_element->getId() . "_show_to", $this->getTextResource("article_overview_element_editor_publication_date_until"), $this->getDateValue($this->_element->getShowTo()), false, "datepicker");
            $data->assign("show_to_field", $show_to_field->render());
            $max_results_field = new TextField("element_" . $this->_element->getId() . "_number_of_results", $this->getTextResource("article_overview_element_editor_max_results"), $this->_element->getNumberOfResults(), false, true, "number_of_results_field");
            $data->assign("max_results_field", $max_results_field->render());
            $order_by_field = new Pulldown("element_" . $this->_element->getId() . "_order_by", $this->getTextResource("article_overview_element_editor_sort_by"), $this->_element->getOrderBy(), $this->getOrderOptions(), false, "");
            $data->assign("order_by_field", $order_by_field->render());
            $order_type_field = new Pulldown("element_" . $this->_element->getId() . "_order_type", $this->getTextResource("article_overview_element_editor_ordering"), $this->_element->getOrderType(), $this->getOrderTypeOptions(), false, "");
            $data->assign("order_type_field", $order_type_field->render());
            $term_select_field = new TermSelector($this->_element->getTerms(), $this->_element->getId());
            $data->assign("term_select_field", $term_select_field->render());
            
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE, $data);
        }
        
        private function getDateValue(?string $date): ?string {            
            return $date == null ? null : DateUtility::mysqlDateToString($date, '-');
        }
        
        private function getOrderOptions(): array {
            $order_options = array();
            array_push($order_options, array('name' => $this->getTextResource("article_overview_element_editor_sort_publication_date"), 'value' => 'PublicationDate'));
            array_push($order_options, array('name' => $this->getTextResource("article_overview_element_editor_sort_sort_date"), 'value' => 'SortDate'));
            array_push($order_options, array('name' => $this->getTextResource("article_overview_element_editor_sort_alphabet"), 'value' => 'Alphabet'));
            return $order_options;
        }

        private function getOrderTypeOptions(): array {
            $order_options = array();
            array_push($order_options, array('name' => $this->getTextResource("article_overview_element_editor_order_ascending"), 'value' => 'asc'));
            array_push($order_options, array('name' => $this->getTextResource("article_overview_element_editor_order_descending"), 'value' => 'desc'));
            return $order_options;
        }
    
    }
    
?>