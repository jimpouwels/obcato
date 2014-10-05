<?php

	defined("_ACCESS") or die;
	
	require_once CMS_ROOT . "/view/forms/form.php";
    require_once CMS_ROOT . "/database/dao/article_dao.php";
    require_once CMS_ROOT . "/libraries/utilities/date_utility.php";

	class ArticleForm extends Form {
	
		private $_article;
		private $_element_order;
		private $_selected_terms;
		private $_target_page_id;
        private $_article_dao;

		public function __construct($article) {
			$this->_article = $article;
            $this->_article_dao = ArticleDao::getInstance();
		}

		public function loadFields() {
			$this->_article->setTitle($this->getMandatoryFieldValue("article_title", "Titel is verplicht"));
			$this->_article->setDescription($this->getFieldValue("article_description"));
			$this->_article->setPublished($this->getCheckboxValue("article_published"));
			$this->_article->setImageId($this->getFieldValue("article_image_ref_" . $this->_article->getId()));
			$this->_article->setTargetPageId($this->getFieldValue("article_target_page"));
			$this->loadPublicationDate();
            $this->loadSortDate();
			$this->deleteLeadImageIfNeeded();
			$this->_element_order = $this->getFieldValue("element_order");		
			$this->_selected_terms = $this->getFieldValue("select_terms_" . $this->_article->getId());
			if ($this->hasErrors())
				throw new FormException();
		}
		
		public function getElementOrder() {
			return $this->_element_order;
		}
		
		public function getSelectedTerms() {
			return $this->_selected_terms;
		}
		
		public function getTargetPageId() {
			return $this->_target_page_id;
		}

        public function getTermsToDelete() {
            $terms_to_delete = array();
            $article_terms = $this->_article_dao->getTermsForArticle($this->_article->getId());
            foreach ($article_terms as $article_term)
                if (!is_null($this->getFieldValue("term_" . $this->_article->getId() . "_" . $article_term->getId() . "_delete")))
                    $terms_to_delete[] = $article_term;
            return $terms_to_delete;
        }
		
		private function deleteLeadImageIfNeeded() {
			if ($this->getFieldValue("delete_lead_image_field") == "true") {
				$this->_article->setImageId(null);
			}
		}
		
		private function loadPublicationDate() {
			$publication_date = $this->getMandatoryDate("publication_date", "Vul een datum in (bijv. 31-12-2010)");
			$this->_article->setPublicationDate(DateUtility::stringMySqlDate($publication_date));
		}

        private function loadSortDate() {
            $sort_date = $this->getMandatoryDate("sort_date", "Vul een datum in (bijv. 31-12-2010)");
            $this->_article->setSortDate(DateUtility::stringMySqlDate($sort_date));
        }
	
	}
	