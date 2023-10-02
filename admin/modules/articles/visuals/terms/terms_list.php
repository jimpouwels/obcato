<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/form_checkbox_single.php";
require_once CMS_ROOT . "/view/views/information_message.php";

class TermsList extends Panel {

    private ArticleDao $_article_dao;

    public function __construct() {
        parent::__construct('Termen', 'term_list_panel');
        $this->_article_dao = ArticleDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/terms/list.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("all_terms", $this->getAllTerms());
        $no_terms_message = new InformationMessage("Geen termen gevonden");
        $data->assign("no_terms_message", $no_terms_message->render());
    }

    private function getAllTerms(): array {
        $all_term_values = array();
        $all_terms = $this->_article_dao->getAllTerms();

        foreach ($all_terms as $term) {
            $term_value = array();
            $term_value["id"] = $term->getId();
            $term_value["name"] = $term->getName();
            $delete_field = new SingleCheckbox("term_" . $term->getId() . "_delete", "", false, false, "");
            $term_value["delete_field"] = $delete_field->render();

            $all_term_values[] = $term_value;
        }
        return $all_term_values;
    }
}
