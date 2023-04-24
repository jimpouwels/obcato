<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/tab_menu.php";
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "modules/articles/visuals/articles/articles_tab.php";
    require_once CMS_ROOT . "modules/articles/visuals/terms/terms_tab.php";
    require_once CMS_ROOT . "modules/articles/visuals/target_pages/list.php";
    require_once CMS_ROOT . "modules/articles/article_request_handler.php";
    require_once CMS_ROOT . "modules/articles/term_request_handler.php";
    require_once CMS_ROOT . "modules/articles/target_pages_request_handler.php";

    class ArticleModuleVisual extends ModuleVisual {

        private static string $HEAD_INCLUDES_TEMPLATE = "articles/head_includes.tpl";
        private static int $ARTICLES_TAB = 0;
        private static int $TERMS_TAB = 1;
        private static int $TARGET_PAGES_TAB = 2;
        private ArticleDao $_article_dao;
        private ?ArticleTerm $_current_term;
        private ?Article $_current_article;
        private Module $_article_module;
        private ArticleRequestHandler $_article_request_handler;
        private TermRequestHandler $_term_request_handler;
        private TargetPagesRequestHandler $_target_pages_request_handler;

        public function __construct(Module $article_module) {
            parent::__construct($article_module);
            $this->_article_module = $article_module;
            $this->_article_dao = ArticleDao::getInstance();
            $this->_article_request_handler = new ArticleRequestHandler();
            $this->_term_request_handler = new TermRequestHandler();
            $this->_target_pages_request_handler = new TargetPagesRequestHandler();
        }

        public function getTemplateFilename(): string {
            return "modules/articles/root.tpl";
        }

        public function load(): void {
            $this->assign("tab_menu", $this->renderTabMenu());
            $content = null;
            if ($this->getCurrentTabId() == self::$ARTICLES_TAB) {
                $content = new ArticleTab($this->_article_request_handler);
            } else if ($this->getCurrentTabId() == self::$TERMS_TAB) {
                $content = new TermTab($this->_current_term);
            } else if ($this->getCurrentTabId() == self::$TARGET_PAGES_TAB) {
                $content = new TargetPagesList();
            }
            if (!is_null($content)) {
                $this->assign("content", $content->render());
            }
        }

        public function getRequestHandlers(): array {
            $pre_handlers = array();
            if ($this->getCurrentTabId() == self::$ARTICLES_TAB) {
                $pre_handlers[] = $this->_article_request_handler;
            } else if ($this->getCurrentTabId() == self::$TERMS_TAB) {
                $pre_handlers[] = $this->_term_request_handler;
            } else if ($this->getCurrentTabId() == self::$TARGET_PAGES_TAB) {
                $pre_handlers[] = $this->_target_pages_request_handler;
            }
            return $pre_handlers;
        }

        public function getActionButtons(): array {
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

        public function renderHeadIncludes(): string {
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

        public function onRequestHandled(): void {
            $this->_current_article = $this->_article_request_handler->getCurrentArticle();
            $this->_current_term = $this->_term_request_handler->getCurrentTerm();
        }

        private function renderTabMenu(): string {
            $tab_items = array();
            
            $tab_item = array();
            $tab_item["text"] = $this->getTextResource("articles_tab_articles");
            $tab_item["id"] = self::$ARTICLES_TAB;
            $tab_items[] = $tab_item;

            $tab_item = array();
            $tab_item["text"] = $this->getTextResource("articles_tab_terms");
            $tab_item["id"] = self::$TERMS_TAB;
            $tab_items[] = $tab_item;

            $tab_item = array();
            $tab_item["text"] = $this->getTextResource("articles_tab_target_pages");
            $tab_item["id"] = self::$TARGET_PAGES_TAB;
            $tab_items[] = $tab_item;

            $tab_menu = new TabMenu($tab_items, $this->getCurrentTabId());
            return $tab_menu->render();
        }

    }

?>
