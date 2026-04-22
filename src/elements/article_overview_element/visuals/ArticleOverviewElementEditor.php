<?php

namespace Pageflow\Core\elements\article_overview_element\visuals;

use Pageflow\Core\core\model\Element;
use Pageflow\Core\elements\article_overview_element\ArticleOverviewElement;
use Pageflow\Core\utilities\DateUtility;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\DateField;
use Pageflow\Core\view\views\ElementVisual;
use Pageflow\Core\view\views\Pulldown;
use Pageflow\Core\view\views\SingleCheckbox;
use Pageflow\Core\view\views\TermSelector;
use Pageflow\Core\view\views\TextField;

class ArticleOverviewElementEditor extends ElementVisual {

    private static string $TEMPLATE = "article_overview_element/templates/article_overview_element_form.tpl";
    private ArticleOverviewElement $element;

    public function __construct(ArticleOverviewElement $element) {
        parent::__construct();
        $this->element = $element;
    }

    public function getElement(): Element {
        return $this->element;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField("element_" . $this->element->getId() . "_title", $this->getTextResource("article_overview_element_editor_title"), $this->element->getTitle(), false, true, null);
        $data->assign("title_field", $titleField->render());
        $showFromField = new DateField("element_" . $this->element->getId() . "_show_from", $this->getTextResource("article_overview_element_editor_publication_date_from"), $this->getDateValue($this->element->getShowFrom()), false, "datepicker");
        $data->assign("show_from_field", $showFromField->render());
        $showToField = new DateField("element_" . $this->element->getId() . "_show_to", $this->getTextResource("article_overview_element_editor_publication_date_until"), $this->getDateValue($this->element->getShowTo()), false, "datepicker");
        $data->assign("show_to_field", $showToField->render());
        $maxResultsField = new TextField("element_" . $this->element->getId() . "_number_of_results", $this->getTextResource("article_overview_element_editor_max_results"), $this->element->getNumberOfResults(), false, true, "number_of_results_field");
        $data->assign("max_results_field", $maxResultsField->render());
        $orderByField = new Pulldown("element_" . $this->element->getId() . "_order_by", $this->getTextResource("article_overview_element_editor_sort_by"), $this->element->getOrderBy(), $this->getOrderOptions(), false, "");
        $data->assign("order_by_field", $orderByField->render());
        $orderTypeField = new Pulldown("element_" . $this->element->getId() . "_order_type", $this->getTextResource("article_overview_element_editor_ordering"), $this->element->getOrderType(), $this->getOrderTypeOptions(), false, "");
        $data->assign("order_type_field", $orderTypeField->render());
        $siblingsOnlyField = new SingleCheckbox("element_" . $this->element->getId() . "_siblings_only", "article_overview_element_editor_siblings_only", $this->element->getSiblingsOnly(), false, "", "article_overview_element_editor_siblings_only_helptext");
        $data->assign("siblings_only_field", $siblingsOnlyField->render());
        $includeCurrentArticleField = new SingleCheckbox("element_" . $this->element->getId() . "_include_current_article", "article_overview_element_editor_include_current_article", $this->element->includeCurrentArticle(), false, "");
        $data->assign("include_current_article_field", $includeCurrentArticleField->render());
        $randomArticlesField = new SingleCheckbox("element_" . $this->element->getId() . "_random_articles", "article_overview_element_editor_random_articles", $this->element->isRandomArticles(), false, "");
        $data->assign("random_articles_field", $randomArticlesField->render());
        $termSelectField = new TermSelector($this->element->getTerms(), $this->element->getId());
        $data->assign("term_select_field", $termSelectField->render());
    }

    private function getDateValue(?string $date): ?string {
        return $date ? substr($date, 0, 10) : null;
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
