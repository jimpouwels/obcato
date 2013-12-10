<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "view/views/action_button.php";
	require_once FRONTEND_REQUEST . "view/views/tab_menu.php";
	require_once FRONTEND_REQUEST . "view/views/module_visual.php";
	require_once FRONTEND_REQUEST . "database/dao/article_dao.php";
	require_once FRONTEND_REQUEST . "view/template_engine.php";
	require_once FRONTEND_REQUEST . "modules/articles/visuals/articles/articles_tab.php";
	require_once FRONTEND_REQUEST . "modules/articles/visuals/terms/terms_tab.php";
	require_once FRONTEND_REQUEST . "modules/articles/visuals/target_pages/target_pages_tab.php";
	require_once FRONTEND_REQUEST . "modules/articles/article_pre_handler.php";

	class ArticleModuleVisual extends ModuleVisual {
	
		private static $TEMPLATE = "articles/root.tpl";
		private static $HEAD_INCLUDES_TEMPLATE = "articles/head_includes.tpl";
		private static $ARTICLES_TAB = 0;
		private static $TERMS_TAB = 1;
		private static $TARGET_PAGES_TAB = 2;
		
		private $_template_engine;
		private $_article_dao;
		private $_current_article;
		private $_current_term;
		private $_article_module;
		private $_article_pre_handler;
		
		public function __construct($article_module) {
			$this->_article_module = $article_module;
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_article_dao = ArticleDao::getInstance();
			$this->_article_pre_handler = new ArticlePreHandler();
			$this->initialize();
		}
	
		public function render() {
			$this->_template_engine->assign("tab_menu", $this->renderTabMenu());
			$content = null;
			if ($this->_article_pre_handler->getCurrentTabId() == self::$ARTICLES_TAB) {
				$content = new ArticleTab($this->_current_article, $this->_article_module->getIdentifier());
			} else if ($this->_article_pre_handler->getCurrentTabId() == self::$TERMS_TAB) {
				$content = new TermTab($this->_current_term, $this->_article_module->getIdentifier());
			} else if ($this->_article_pre_handler->getCurrentTabId() == self::$TARGET_PAGES_TAB) {
				$content = new TargetPagesTab($this->_article_module->getIdentifier());
			}
			if (!is_null($content)) {
				$this->_template_engine->assign("content", $content->render());
			}
			
			return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
		}
		
		public function getTitle() {
			return $this->_article_module->getTitle();
		}
	
		public function getActionButtons() {
			$action_buttons = array();
			
			if ($this->_article_pre_handler->getCurrentTabId() == self::$ARTICLES_TAB) {
				$save_button = null;
				$delete_button = null;
				if (!is_null($this->_current_article)) {
					$save_button = new ActionButton("Opslaan", "update_element_holder", "icon_apply");
					$delete_button = new ActionButton("Verwijderen", "delete_element_holder", "icon_delete");
				}
				$action_buttons[] = $save_button;
				$action_buttons[] = new ActionButton("Toevoegen", "add_element_holder", "icon_add");
				$action_buttons[] = $delete_button;				
			}
			if ($this->_article_pre_handler->getCurrentTabId() == self::$TERMS_TAB) {
				if (!is_null($this->_current_term) || TermTab::isEditTermMode()) {
					$action_buttons[] = new ActionButton("Opslaan", "update_term", "icon_apply");
				}
				$action_buttons[] = new ActionButton("Toevoegen", "add_term", "icon_add");
				$action_buttons[] = new ActionButton("Verwijderen", "delete_terms", "icon_delete");
			}
			if ($this->_article_pre_handler->getCurrentTabId() == self::$TARGET_PAGES_TAB) {
				$action_buttons[] = new ActionButton("Verwijderen", "delete_target_pages", "icon_delete");
			}
			
			return $action_buttons;
		}
		
		public function getHeadIncludes() {
			$this->_template_engine->assign("path", $this->_article_module->getIdentifier());
			
			$element_statics_values = array();	
			if (!is_null($this->_current_article)) {		
				$element_statics = $this->_current_article->getElementStatics();
				if (count($element_statics) > 0) {
					foreach ($element_statics as $element_static) {
						$element_statics_values[] = $element_static->render();
					}
				}
			}
			$this->_template_engine->assign("element_statics", $element_statics_values);
			$this->_template_engine->assign("path", $this->_article_module->getIdentifier());
			return $this->_template_engine->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
		}
		
		public function preHandle() {
			include_once FRONTEND_REQUEST . "modules/articles/pre_handler.php";
			$this->initialize();
		}
		
		private function initialize() {
			$this->loadCurrentTerm();
			$this->loadCurrentArticle();
		}
		
		private function loadCurrentArticle() {
			if (isset($_GET['article'])) {
				$this->_current_article = $this->_article_dao->getArticle($_GET['article']);
			}
		}
		
		private function loadCurrentTerm() {
			if (isset($_GET['term'])) {
				$this->_current_term = $this->_article_dao->getTerm($_GET['term']);
			}
		}
		
		private function renderTabMenu() {
			$tab_items = array();
			$tab_items[self::$ARTICLES_TAB] = "Artikelen";
			$tab_items[self::$TERMS_TAB] = "Termen";
			$tab_items[self::$TARGET_PAGES_TAB] = "Doelpagina's";
			$tab_menu = new TabMenu($tab_items, $this->_article_pre_handler->getCurrentTabId());
			return $tab_menu->render();
		}
	
	}
	
?>