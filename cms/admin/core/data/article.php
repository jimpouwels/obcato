<?php

	// No direct access
	defined('_ACCESS') or die;

	require_once CMS_ROOT . "/core/data/element_holder.php";
	require_once CMS_ROOT . "/database/dao/article_dao.php";
    require_once CMS_ROOT . "/database/dao/page_dao.php";
	
	class Article extends ElementHolder {
	
		private $_description;
		private $_image_id;
		private $_publication_date;
		private $_target_page_id;
        private $_page_dao;
		
		public function __construct() {
			parent::__construct();
            $this->_page_dao = PageDao::getInstance();
			$this->setScopeId(9);
			$this->setPublished(false);
		}
		
		public function getDescription() {
			$description = $this->_description;
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
			$image = null;
			if ($this->_image_id != '' && !is_null($this->_image_id)) {
				include_once CMS_ROOT . "/database/dao/image_dao.php";
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
			$target_page = null;
			if (!is_null($this->_target_page_id) && $this->_target_page_id != '')
				$target_page = $this->_page_dao->getPage($this->_target_page_id);
			return $target_page;
		}
		
		public function getTerms() {
			$article_dao = ArticleDao::getInstance();
			return $article_dao->getTermsForArticle($this->getId());
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