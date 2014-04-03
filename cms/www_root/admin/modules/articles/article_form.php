<?php

	defined("_ACCESS") or die;
	
	require_once FRONTEND_REQUEST . "pre_handlers/form.php";
	include_once FRONTEND_REQUEST . "libraries/utilities/date_utility.php";
	
	class ArticleForm extends Form {
	
		private $_article;
		private $_element_order;
		private $_selected_terms;
		private $_target_page_id;
		private $_delete_lead_image;
	
		public function __construct($article) {
			$this->_article = $article;
		}
	
		public function loadFields() {
			$this->_article->setTitle($this->getMandatoryFieldValue("article_title", "Titel is verplicht"));
			$this->_article->setDescription($this->getFieldValue("article_description"));
			$this->_article->setDescription($this->getFieldValue("article_description"));
			$this->_article->setPublished($this->getCheckboxValue("article_published"));
			$this->_article->setImageId($this->getFieldValue("article_image_ref_" . $this->_article->getId()));
			$this->_article->setTargetPageId($this->getFieldValue("article_target_page"));
			$publication_date = $this->getMandatoryDate("publication_date", "Vul een datum in (bijv. 31-12-2010)");
			if (!is_null($publication_date) && $publication_date !='') {
				$publication_date = DateUtility::stringMySqlDate($publication_date);
			}
			$this->_article->setPublicationDate($publication_date);
			$this->_element_order = $this->getFieldValue("element_order");		
			$this->_selected_terms = $this->getFieldValue("select_terms_" . $this->_article->getId());
			$this->_delete_lead_image = $this->getFieldValue("delete_lead_image_field");
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
		
		public function deleteLeadImage() {
			return $this->_delete_lead_image == "true";
		}
	
	}
	