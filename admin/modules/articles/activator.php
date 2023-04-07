<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/tab_menu.php";
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "modules/articles/visuals/articles/articles_tab.php";
    require_once CMS_ROOT . "modules/articles/visuals/terms/terms_tab.php";
    require_once CMS_ROOT . "modules/articles/visuals/target_pages/list.php";
    require_once CMS_ROOT . "modules/articles/article_pre_handler.php";
    require_once CMS_ROOT . "modules/articles/term_pre_handler.php";
    require_once CMS_ROOT . "modules/articles/target_pages_pre_handler.php";

    class ArticleModuleVisual extends ModuleVisual {

        private static $TEMPLATE = "articles/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "articles/head_includes.tpl";
        private static $ARTICLES_TAB = 0;
        private static $TERMS_TAB = 1;
        private static $TARGET_PAGES_TAB = 2;
        private $_article_dao;
        private $_current_term;
        private $_current_article;
        private $_article_module;
        private $_article_pre_handler;
        private $_term_pre_handler;
        private $_target_pages_pre_handler;

        public function __construct($article_module) {
            parent::__construct($article_module);
            $this->_article_module = $article_module;
            $this->_article_dao = ArticleDao::getInstance();
            $this->_article_pre_handler = new ArticlePreHandler();
            $this->_term_pre_handler = new TermPreHandler();
            $this->_target_pages_pre_handler = new TargetPagesPreHandler();
        }

        public function renderVisual(): string {
            $this->getTemplateEngine()->assign("tab_menu", $this->renderTabMenu());
            $content = null;
            if ($this->getCurrentTabId() == self::$ARTICLES_TAB) {
                $content = new ArticleTab($this->_article_pre_handler);
            } else if ($this->getCurrentTabId() == self::$TERMS_TAB) {
                $content = new TermTab($this->_current_term);
            } else if ($this->getCurrentTabId() == self::$TARGET_PAGES_TAB) {
                $content = new TargetPagesList();
            }
            if (!is_null($content)) {
                $this->getTemplateEngine()->assign("content", $content->render());
            }

            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }

        public function getRequestHandlers() {
            $pre_handlers = array();
            if ($this->getCurrentTabId() == self::$ARTICLES_TAB) {
                $pre_handlers[] = $this->_article_pre_handler;
            } else if ($this->getCurrentTabId() == self::$TERMS_TAB) {
                $pre_handlers[] = $this->_term_pre_handler;
            } else if ($this->getCurrentTabId() == self::$TARGET_PAGES_TAB) {
                $pre_handlers[] = $this->_target_pages_pre_handler;
            }
            return $pre_handlers;
        }

        public function getActionButtons() {
            $action_buttons = array();
            if ($this->getCurrentTabId() == self::$ARTICLES_TAB) {
                $save_button = null;
                $delete_button = null;
                if (!is_null($this->_current_article)) {
                    $save_button = new ActionButtonSave('update_element_holder');
                    $delete_button = new ActionButtonDelete('delete_element_holder');
                }
                $action_buttons[] = $save_button;
                $action_buttons[] = new ActionButtonAdd('add_element_holder');
                $action_buttons[] = $delete_button;
            }
            if ($this->getCurrentTabId() == self::$TERMS_TAB) {
                if (!is_null($this->_current_term) || TermTab::isEditTermMode()) {
                    $action_buttons[] = new ActionButtonSave('update_term');
                }
                $action_buttons[] = new ActionButtonAdd('add_term');
                $action_buttons[] = new ActionButtonDelete('delete_terms');
            }
            if ($this->getCurrentTabId() == self::$TARGET_PAGES_TAB) {
                $action_buttons[] = new ActionButtonDelete('delete_target_pages');
            }

            return $action_buttons;
        }

        public function getHeadIncludes() {
            $this->getTemplateEngine()->assign("path", $this->_article_module->getIdentifier());

            $element_statics_values = array();
            if (!is_null($this->_current_article)) {
                $element_statics = $this->_current_article->getElementStatics();
                foreach ($element_statics as $element_static) {
                    $element_statics_values[] = $element_static->render();
                }
            }
            $this->getTemplateEngine()->assign("element_statics", $element_statics_values);
            $this->getTemplateEngine()->assign("path", $this->_article_module->getIdentifier());
            return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function onPreHandled() {
            $this->_current_article = $this->_article_pre_handler->getCurrentArticle();
            $this->_current_term = $this->_term_pre_handler->getCurrentTerm();
        }

        private function renderTabMenu() {
            $tab_items = array();
            
            $tab_item = array();
            $tab_item["text"] = $this->getTextResource("articles_tab_articles");
            $tab_item["url"] = $this->getBackendBaseUrlWithoutTab() . "&module_tab_id=" . self::$ARTICLES_TAB;
            $tab_items[] = $tab_item;

            $tab_item = array();
            $tab_item["text"] = $this->getTextResource("articles_tab_terms");
            $tab_item["url"] = $this->getBackendBaseUrlWithoutTab() . "&module_tab_id=" . self::$TERMS_TAB;
            $tab_items[] = $tab_item;

            $tab_item = array();
            $tab_item["text"] = $this->getTextResource("articles_tab_target_pages");
            $tab_item["url"] = $this->getBackendBaseUrlWithoutTab() . "&module_tab_id=" . self::$TARGET_PAGES_TAB;
            $tab_items[] = $tab_item;

            $tab_menu = new TabMenu($tab_items, $this->getCurrentTabId());
            return $tab_menu->render();
        }

    }

?>
