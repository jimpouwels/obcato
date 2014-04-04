<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/handlers/form_handler.php";
	include_once FRONTEND_REQUEST . "libraries/validators/form_validator.php";
	include_once FRONTEND_REQUEST . "libraries/system/notifications.php";
	include_once FRONTEND_REQUEST . "libraries/utilities/string_utility.php";
	include_once FRONTEND_REQUEST . "database/dao/article_dao.php";
	include_once FRONTEND_REQUEST . "database/dao/authorization_dao.php";
	
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