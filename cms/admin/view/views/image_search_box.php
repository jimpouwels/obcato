<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "view/views/search.php";
	require_once FRONTEND_REQUEST . "view/views/information_message.php";
	require_once FRONTEND_REQUEST . "database/dao/image_dao.php";
	
	class ImageSearchBox extends Visual {
		
		private static $SEARCH_QUERY_KEY = "s_term";
		private static $SEARCH_LABEL_KEY = "s_label";
		private static $TEMPLATE = "system/image_search.tpl";
		private $_template_engine;
		private $_back_click_id;
		private $_backfill_id;
		private $_objects_to_search;
		private $_image_dao;
		
		public function __construct($back_click_id, $backfill_id, $objects_to_search) {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_back_click_id = $back_click_id;
			$this->_backfill_id = $backfill_id;
			$this->_objects_to_search = $objects_to_search;
			$this->_image_dao = ImageDao::getInstance();
		}
		
		public function render() {
			$this->_template_engine->assign("object", $this->_objects_to_search);
			$this->_template_engine->assign("backfill", $this->_backfill_id);
			$this->_template_engine->assign("back_click_id", $this->_back_click_id);
			
			$this->_template_engine->assign("search_field", $this->renderSearchField());
			$this->_template_engine->assign("search_button", $this->renderSearchButton());
			$this->_template_engine->assign("image_labels_field", $this->renderImageLabelsField());
			$this->_template_engine->assign("search_results", $this->renderSearchResults());
			$this->_template_engine->assign("no_results_message", $this->renderNoResultsMessage());
			
			return $this->_template_engine->fetch(self::$TEMPLATE);
		}
		
		private function renderSearchField() {
			$search_query = $this->getCurrentSearchQuery();
			$search_field = new TextField(self::$SEARCH_QUERY_KEY, "Zoekterm", $search_query, false, false, false, null);
			return $search_field->render();
		}
		
		private function renderImageLabelsField() {
			$image_labels_values = array();
			
			$current_label_search = $this->getCurrentSearchLabel();
			$image_labels_values[] = array("name" => "&gt; Selecteer", "value" => "");
			
			$image_labels = $this->_image_dao->getAllLabels();
			foreach ($image_labels as $image_label) {
				$image_labels_values[] = array("name" => $image_label->getName(), "value" => $image_label->getId());
			}
			$image_labels_field = new Pulldown(self::$SEARCH_LABEL_KEY, "Label", $current_label_search, $image_labels_values, false, null);
			return $image_labels_field->render();
		}
		
		private function renderSearchResults() {
			$search_results_value = array();
			$search_results = null;
			$search_results = $this->_image_dao->searchImages($this->getCurrentSearchQuery(), null, $this->getCurrentSearchLabel());
			if (!is_null($search_results) && count($search_results) > 0) {
				foreach ($search_results as $search_result) {
					$search_result_value = array();
					$search_result_value["id"] = $search_result->getId();
					$search_result_value["title"] = $search_result->getTitle();
					$search_result_value["published"] = $search_result->isPublished();
					$search_results_value[] = $search_result_value;
				}
			}
			return $search_results_value;
		}
		
		private function renderNoResultsMessage() {
			$information_message = new InformationMessage("Geen resultaten gevonden");
			return $information_message->render();
		}
		
		private function getCurrentSearchQuery() {
			$search_title = "";
			if (isset($_GET[self::$SEARCH_QUERY_KEY])) {
				$search_title = $_GET[self::$SEARCH_QUERY_KEY];
			}
			return $search_title;
		}
		
		private function getCurrentSearchLabel() {
			$current_search_label = null;
			if (isset($_GET[self::$SEARCH_LABEL_KEY])) {
				$current_search_label = $_GET[self::$SEARCH_LABEL_KEY];
			}
			return $current_search_label;
		}
		
		private function renderSearchButton() {
			$search_button = new Button("", "Zoeken", "document.getElementById('search_form').submit(); return false;");
			return $search_button->render();
		}
		
	}

?>