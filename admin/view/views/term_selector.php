<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/block_dao.php";

    class TermSelector extends Panel {

        private array $_selected_terms;
        private ArticleDao $_article_dao;
        private int $_context_id;

        public function __construct(array $selected_terms, int $context_id) {
            parent::__construct($this->getTextResource("term_selector_title"), 'term_selector');
            $this->_selected_terms = $selected_terms;
            $this->_article_dao = ArticleDao::getInstance();
            $this->_context_id = $context_id;
        }

        public function getPanelContentTemplate(): string {
            return "system/term_selector.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign("terms_to_select", $this->getTermsToSelect());
            $data->assign("selected_terms", $this->getSelectedTermsHtml());
            $data->assign("context_id", $this->_context_id);

            $data->assign("label_selected_terms", $this->getTextResource("term_selector_label_selected_terms"));
            $data->assign("label_delete_selected_term", $this->getTextResource("term_selector_label_delete_selected_term"));
            $data->assign("message_no_selected_terms", $this->getTextResource("term_selector_message_no_terms_selected"));
        }

        private function getTermsToSelect(): array {
            $terms_to_select = array();
            foreach ($this->_article_dao->getAllTerms() as $term) {
                if (!in_array($term, $this->_selected_terms)) {
                    $term_to_select['id'] = $term->getId();
                    $term_to_select['name'] = $term->getName();
                    $terms_to_select[] = $term_to_select;
                }
            }
            return $terms_to_select;
        }

        private function getSelectedTermsHtml(): array {
            $selected_terms = array();
            foreach ($this->_selected_terms as $selected_term) {
                $selected_term_item = array();
                $selected_term_item['name'] = $selected_term->getName();
                $delete_field = new SingleCheckbox("term_" . $this->_context_id . "_" . $selected_term->getId() . "_delete", "", false, false, "");
                $selected_term_item['delete_field'] = $delete_field->render();
                $selected_terms[] = $selected_term_item;
            }
            return $selected_terms;
        }

    }

?>
