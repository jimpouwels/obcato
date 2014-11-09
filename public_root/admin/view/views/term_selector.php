<?php

    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/block_dao.php";
    
    class TermSelector extends Visual {
    
        private static $TEMPLATE = "system/term_selector.tpl";
        private $_template_engine;
        private $_selected_terms;
        private $_article_dao;
        private $_context_id;
        
        public function __construct($selected_terms, $context_id) {
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_selected_terms = $selected_terms;
            $this->_article_dao = ArticleDao::getInstance();
            $this->_context_id = $context_id;
        }
        
        public function render() {
            $this->_template_engine->assign("terms_to_select", $this->getTermsToSelect());
            $this->_template_engine->assign("selected_terms", $this->getSelectedTermsHtml());
            $this->_template_engine->assign("context_id", $this->_context_id);
            
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
        
        private function getTermsToSelect() {
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
        
        private function getSelectedTermsHtml() {
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