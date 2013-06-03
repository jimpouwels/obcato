<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/handlers/form_handler.php";
	include_once "libraries/validators/form_validator.php";
	include_once "libraries/system/notifications.php";
	include_once "libraries/utilities/string_utility.php";
	include_once "dao/article_dao.php";
	
	// =================================== ARTICLES ============================================================
	
	// handle post requests
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'update_element_holder':
					if (isset($_POST['element_holder_id'])) {
						updateArticle($_POST['element_holder_id']);
					}
					break;
				case 'delete_article':
					if (isset($_POST['element_holder_id'])) {
						deleteArticle($_POST['element_holder_id']);
					}
					break;
			}
		} else if (isset($_POST['add_article_action'])) {
			addArticle();
		}
	}
	
	function deleteArticle($element_holder_id) {
		$article_dao = ArticleDao::getInstance();
		$article = $article_dao->getArticle($element_holder_id);
		$article_dao->deleteArticle($article);
		
		Notifications::setSuccessMessage("Artikel succesvol verwijderd");
		header('Location: /admin/index.php');
		exit(); 
	}
	
	function addArticle() {
		$article_dao = ArticleDao::getInstance();
		$new_article = $article_dao->createArticle();
		
		Notifications::setSuccessMessage("Artikel succesvol aangemaakt");
		header('Location: /admin/index.php?article=' . $new_article->getId());
		exit(); 
	}
	
	function updateArticle($element_holder_id) {
		include_once "libraries/utilities/date_utility.php";
	
		global $errors;
		
		$article_dao = ArticleDao::getInstance();
		$element_dao = ElementDao::getInstance();
		$title = FormValidator::checkEmpty('article_title', 'Titel is verplicht');
		$description = FormHandler::getFieldValue('article_description');
		$current_element_holder = $article_dao->getArticle($element_holder_id);
		$published = FormHandler::getFieldValue('article_published');
		$element_order = FormHandler::getFieldValue('element_order');
		$selected_terms = FormHandler::getFieldValue('select_terms_' . $current_element_holder->getId());
		$image_id = FormHandler::getFieldValue('article_image_ref_' . $current_element_holder->getId());
		$target_page_id = FormHandler::getFieldValue('article_target_page');
		$delete_image = FormHandler::getFieldValue('delete_lead_image_field');
		$publication_date = FormValidator::checkDate('publication_date', true, 'Vul een datum in (bijv. 31-12-2010)');
		
		if (count($errors) == 0) {
			$element_dao->updateElementOrder($element_order, $current_element_holder);
			$current_element_holder->setTitle($title);
			$current_element_holder->setDescription($description);
			$current_element_holder->setImageId($image_id);
			$current_element_holder->setTargetPageId($target_page_id);
			if (!is_null($publication_date) && $publication_date !='') {
				$publication_date = DateUtility::stringMySqlDate($publication_date);
			}
			$current_element_holder->setPublicationDate($publication_date);
			
			$published_value = 0;
			if ($published == 'on') {
				$published_value = 1;
			}
			
			if ($delete_image == 'true') {
				$current_element_holder->setImageId(null);
			}
			
			if (!is_null($selected_terms) && count($selected_terms) > 0) {
				$existing_terms = $article_dao->getTermsForArticle($current_element_holder->getId());
				foreach ($selected_terms as $selected_term_id) {
					// make sure the term is not added twice
					if (is_null($existing_terms) || count($existing_terms) == 0 || !in_array($article_dao->getTerm($selected_term_id), $existing_terms)) {
						$article_dao->addTermToArticle($selected_term_id, $current_element_holder);
					}
				}
			}
			
			$article_terms = $article_dao->getTermsForArticle($current_element_holder->getId());
			foreach ($article_terms as $article_term) {
				if (isset($_POST['term_' . $current_element_holder->getId() . '_' . $article_term->getId() . '_delete'])) {
					$article_dao->deleteTermFromArticle($article_term->getId(), $current_element_holder);
				}
			}
			
			$current_element_holder->setPublished($published_value);
			$article_dao->updateArticle($current_element_holder);
			
			Notifications::setSuccessMessage("Artikel succesvol opgeslagen");
		} else {
			Notifications::setFailedMessage("Artikel niet opgeslagen, verwerk de fouten");
		}
	}
	
	// =================================== TERMS ============================================================
	
	// handle post requests
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'update_term':
					updateTerm();
					break;
			}
		}
		if (isset($_POST['term_delete_action'])) {
			deleteTerms();
		}
	}
	
	// term is being updated
	function updateTerm() {
		global $errors;
		
		$article_dao = ArticleDao::getInstance();

		$name = FormValidator::checkEmpty('name', 'Titel is verplicht');
		
		if ($name != '') {
			$existing_term = $article_dao->getTermByName($name);
			
			if (!is_null($existing_term) && !(isset($_GET['term']) && $_GET['term'] == $existing_term->getId())) {
				$errors['name_error'] = 'Er bestaat al een term met deze naam';
			}
		}
		
		if (count($errors) == 0) {
			if (isset($_POST['term_id']) && $_POST['term_id'] != '') {
				$current_term = $article_dao->getTerm($_POST['term_id']);
				$current_term->setName($name);
				$article_dao->updateTerm($current_term);
				Notifications::setSuccessMessage("Term succesvol opgeslagen");
			} else if (isset($_GET['new_term'])) {
				// create new term
				$new_term = $article_dao->createTerm();
				$new_term->setName($name);
				$article_dao->updateTerm($new_term);
				Notifications::setSuccessMessage("Term succesvol aangemaakt");
				header('Location: /admin/index.php?term=' . $new_term->getId());
				exit();
			}
		} else {
			Notifications::setFailedMessage("Term niet opgeslagen, verwerk de fouten");
		}
	}
	
	// terms must be deleted
	function deleteTerms() {
		$article_dao = ArticleDao::getInstance();
		$terms = $article_dao->getAllTerms();
		foreach ($terms as $term) {
			if (isset($_POST['term_' . $term->getId() . '_delete'])) {
				$article_dao->deleteTerm($term);
			}
		}
		Notifications::setSuccessMessage("Term(en) succesvol verwijderd");
	}
	
	// =================================== OPTIONS ============================================================
	
	// handle post requests
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'update_article_option':
					updateArticleOptions();
					break;
				case 'change_default_target_page':
					updateDefaultArticlePage();
					break;
				case 'delete_target_pages':
					deleteTargetPages();
					break;
			}
		}
	}
	
	function updateArticleOptions() {
		if (isset($_POST['add_target_page_ref']) && $_POST['add_target_page_ref'] != '') {
			$article_dao = ArticleDao::getInstance();
			$article_dao->addTargetPage($_POST['add_target_page_ref']);
		}
	}
	
	function updateDefaultArticlePage() {
		if (isset($_POST['change_default_value']) && !is_null($_POST['change_default_value']) && $_POST['change_default_value'] != '') {
			$article_dao = ArticleDao::getInstance();
			$article_dao->setDefaultArticleTargetPage($_POST['change_default_value']);
		}
	}
	
	function deleteTargetPages() {
		$article_dao = ArticleDao::getInstance();
		$target_pages = $article_dao->getTargetPages();
		foreach($target_pages as $target_page) {
			$field_to_check = 'target_page_' . $target_page->getId() . '_delete';
			if (isset($_POST[$field_to_check]) && $_POST[$field_to_check] != '') {
				$article_dao->deleteTargetPage($target_page->getId());
			}
		}
	}
?>