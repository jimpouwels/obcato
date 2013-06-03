<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/system/mysql_connector.php";
	include_once FRONTEND_REQUEST . "dao/link_dao.php";
	
	class LinkUtility {
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/*
			Creates linktags for link codes in a string value.
			
			@param $string_value The string value to replace the link codes for
			@param $element_holder The element holder where the links belong to
		*/
		public static function createLinksInString($string_value, $element_holder) {
			$link_dao = LinkDao::getInstance();
			$links = $link_dao->getLinksForElementHolder($element_holder->getId());
						
			// replace [/LINK] with </a> tags
			$string_value = str_replace("[/LINK]", "</a>", $string_value);
			
			// now create the opening link tags
			foreach ($links as $link) {
				// check if the link code exists in this string
				$link_code_html_to_find = "[LINK C=\"" . $link->getCode() . "\"]";
				$pos = strpos($string_value, $link_code_html_to_find);
				if ($pos === false) {
					// do nothing
				} else {
					if (!is_null($link->getTargetElementHolderId())) {
						$url = self::createUrlFromLink($link);
					} else {
						$url = $link->getTargetAddress();
					}
					$link_tag = "<a title=\"" . $link->getTitle() . "\" href=\"" . $url . "\">";
					$string_value = str_replace($link_code_html_to_find, $link_tag, $string_value);
				}
			}
			return $string_value;
		}
		
		/*
			Creates a new URL for the given link object.
			
			@param $link The link to create the URL for
		*/
		private static function createUrlFromLink($link) {
			$url = NULL;
			$target_element_holder = $link->getTargetElementHolder();
			$target_element_holder_type = $target_element_holder->getType();
			switch ($target_element_holder_type) {
				case 'ELEMENT_HOLDER_PAGE':
					// create link
					return $target_element_holder->getFrontendUrl();
					break;
				case 'ELEMENT_HOLDER_ARTICLE':
					include_once FRONTEND_REQUEST . 'dao/article_dao.php';
					$article_dao = ArticleDao::getInstance();
					$target_article = $article_dao->getArticle($target_element_holder->getId());
					
					return $target_article->getFrontendUrl();
					break;
			}
		}
		
	}

?>