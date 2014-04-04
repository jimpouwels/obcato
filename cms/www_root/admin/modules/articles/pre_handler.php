<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/handlers/form_handler.php";
	include_once FRONTEND_REQUEST . "libraries/validators/form_validator.php";
	include_once FRONTEND_REQUEST . "libraries/system/notifications.php";
	include_once FRONTEND_REQUEST . "libraries/utilities/string_utility.php";
	include_once FRONTEND_REQUEST . "database/dao/article_dao.php";
	include_once FRONTEND_REQUEST . "database/dao/authorization_dao.php";
	
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