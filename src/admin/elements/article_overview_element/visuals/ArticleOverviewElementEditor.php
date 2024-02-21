<?php

namespace Obcato\Core\admin\elements\article_overview_element\visuals;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\core\model\Element;
use Obcato\Core\admin\elements\article_overview_element\ArticleOverviewElement;
use Obcato\Core\admin\utilities\DateUtility;
use Obcato\Core\admin\view\views\DateField;
use Obcato\Core\admin\view\views\ElementVisual;
use Obcato\Core\admin\view\views\Pulldown;
use Obcato\Core\admin\view\views\TermSelector;
use Obcato\Core\admin\view\views\TextField;

class ArticleOverviewElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/article_overview_element/article_overview_element_form.tpl";
    private ArticleOverviewElement $element;

    public function __construct(TemplateEngine $templateEngine, ArticleOverviewElement $element) {
        parent::__construct($templateEngine);
        $this->element = $element;
    }

    public function getElement(): Element {
        return $this->element;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField($this->getTemplateEngine(), "element_" . $this->element->getId() . "_title", $this->getTextResource("article_overview_element_editor_title"), $this->element->getTitle(), false, true, null);
        $data->assign("title_field", $titleField->render());
        $showFromField = new DateField($this->getTemplateEngine(), "element_" . $this->element->getId() . "_show_from", $this->getTextResource("article_overview_element_editor_publication_date_from"), $this->getDateValue($this->element->getShowFrom()), false, "datepicker");
        $data->assign("show_from_field", $showFromField->render());
        $showToField = new DateField($this->getTemplateEngine(), "element_" . $this->element->getId() . "_show_to", $this->getTextResource("article_overview_element_editor_publication_date_until"), $this->getDateValue($this->element->getShowTo()), false, "datepicker");
        $data->assign("show_to_field", $showToField->render());
        $maxResultsField = new TextField($this->getTemplateEngine(), "element_" . $this->element->getId() . "_number_of_results", $this->getTextResource("article_overview_element_editor_max_results"), $this->element->getNumberOfResults(), false, true, "number_of_results_field");
        $data->assign("max_results_field", $maxResultsField->render());
        $orderByField = new Pulldown($this->getTemplateEngine(), "element_" . $this->element->getId() . "_order_by", $this->getTextResource("article_overview_element_editor_sort_by"), $this->element->getOrderBy(), $this->getOrderOptions(), false, "");
        $data->assign("order_by_field", $orderByField->render());
        $orderTypeField = new Pulldown($this->getTemplateEngine(), "element_" . $this->element->getId() . "_order_type", $this->getTextResource("article_overview_element_editor_ordering"), $this->element->getOrderType(), $this->getOrderTypeOptions(), false, "");
        $data->assign("order_type_field", $orderTypeField->render());
        $termSelectField = new TermSelector($this->getTemplateEngine(), $this->element->getTerms(), $this->element->getId());
        $data->assign("term_select_field", $termSelectField->render());
    }

    private function getDateValue(?string $date): ?string {
        return $date == null ? null : DateUtility::mysqlDateToString($date, '-');
    }

    private function getOrderOptions(): array {
        $orderOptions = array();
        $orderOptions[] = array('name' => $this->getTextResource("article_overview_element_editor_sort_publication_date"), 'value' => 'PublicationDate');
        $orderOptions[] = array('name' => $this->getTextResource("article_overview_element_editor_sort_sort_date"), 'value' => 'SortDate');
        $orderOptions[] = array('name' => $this->getTextResource("article_overview_element_editor_sort_alphabet"), 'value' => 'Alphabet');
        return $orderOptions;
    }

    private function getOrderTypeOptions(): array {
        $orderOptions = array();
        $orderOptions[] = array('name' => $this->getTextResource("article_overview_element_editor_order_ascending"), 'value' => 'asc');
        $orderOptions[] = array('name' => $this->getTextResource("article_overview_element_editor_order_descending"), 'value' => 'desc');
        return $orderOptions;
    }

}
