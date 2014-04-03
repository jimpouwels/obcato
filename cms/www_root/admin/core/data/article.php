<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "core/data/element_holder.php";
	include_once FRONTEND_REQUEST . "database/dao/article_dao.php";
	
	class Article extends ElementHolder {
	
		private $_description;
		private $_image_id;
		private $_publication_date;
		private $_target_page_id;
		
		public function __construct() {
			parent::__construct();
			$this->setScopeId(9);
		}
		
		public function getDescription() {
			$description = $this->_description;
			if (FRONTEND_REQUEST != '') {
				include_once FRONTEND_REQUEST . "libraries/utilities/link_utility.php";
				// replace newlines with HTML breaks
				$description = nl2br($description);
				$description = LinkUtility::createLinksInString($description, $this);
			}
			return $description;
		}
		
		public function setDescription($description) {
			$this->_description = $description;
		}
		
		public function getImageId() {
			return $this->_image_id;
		}
		
		public function setImageId($image_id) {
			$this->_image_id = $image_id;
		}
		
		public function getImage() {
			$image = NULL;
			if ($this->_image_id != '' && !is_null($this->_image_id)) {
				include_once FRONTEND_REQUEST . "database/dao/image_dao.php";
				$image_dao = ImageDao::getInstance();
				$image = $image_dao->getImage($this->_image_id);
			}
			return $image;
		}
		
		public function getPublicationDate() {
			return $this->_publication_date;
		}
		
		public function setPublicationDate($publication_date) {
			$this->_publication_date = $publication_date;
		}
		
		public function getTargetPageId() {
			return $this->_target_page_id;
		}
		
		public function setTargetPageId($target_page_id) {
			$this->_target_page_id = $target_page_id;
		}
		
		public function getTargetPage() {
			$target_page = NULL;
			if (!is_null($this->_target_page_id) && $this->_target_page_id != '') {
				$target_page = Page::findById($this->_target_page_id);
			}
			return $target_page;
		}
		
		public function getTerms() {
			$article_dao = ArticleDao::getInstance();
			return $article_dao->getTermsForArticle($this->getId());
		}
		
		public function getFrontendUrl() {
			$target_page_id = $_GET['id'];
			if (!is_null($this->getTargetPageId()) && $this->getTargetPageId() != '') {
				$target_page = $this->getTargetPage();
				// check if it is published (result is null)
				if (!is_null($target_page)) {
					$target_page_id = $target_page->getId();
				}
			} elseif (is_null($target_page_id) || $target_page_id == "") {
				include_once FRONTEND_REQUEST . "/dao/settings_dao.php";
				$target_page_id = SettingsDao::getInstance()->getHomepage()->getId();
			}
			return "/show.php?id=" . $target_page_id . "&amp;articleid=" . $this->getId();
		}
		
		public function persist() {
			parent::persist();
		}
		
		public function delete() {
			parent::delete();
		}
		
		public static function constructFromRecord($record) {
			$article = new Article();
			$article->setId($record['id']);
			$article->setTitle($record['title']);
			$article->setPublished($record['published'] == 1 ? true : false);
			$article->setDescription($record['description']);
			$article->setScopeId($record['scope_id']);
			$article->setImageId($record['image_id']);
			$article->setCreatedAt($record['created_at']);
			$article->setCreatedById($record['created_by']);
			$article->setType($record['type']);
			$article->setPublicationDate($record['publication_date']);
			$article->setTargetPageId($record['target_page']);
			
			return $article;
		}

	}