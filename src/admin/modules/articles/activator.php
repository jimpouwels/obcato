<?php

namespace Obcato\Core\admin\modules\articles;

use Obcato\ComponentApi\TabMenu;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\articles\model\ArticleTerm;
use Obcato\Core\admin\modules\articles\visuals\articles\ArticleTab;
use Obcato\Core\admin\modules\articles\visuals\target_pages\TargetPagesList;
use Obcato\Core\admin\modules\articles\visuals\terms\TermTab;
use Obcato\Core\admin\view\views\ActionButtonAdd;
use Obcato\Core\admin\view\views\ActionButtonDelete;
use Obcato\Core\admin\view\views\ActionButtonSave;
use Obcato\Core\admin\view\views\ModuleVisual;

class ArticleModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "articles/head_includes.tpl";
    private static int $ARTICLES_TAB = 0;
    private static int $TERMS_TAB = 1;
    private static int $TARGET_PAGES_TAB = 2;
    private ?ArticleTerm $currentTerm;
    private ?Article $currentArticle;
    private ArticleRequestHandler $articleRequestHandler;
    private TermRequestHandler $termRequestsHandler;
    private TargetPagesRequestHandler $targetPagesRequestHandler;

    public function __construct() {
        $this->articleRequestHandler = new ArticleRequestHandler();
        $this->termRequestsHandler = new TermRequestHandler();
        $this->targetPagesRequestHandler = new TargetPagesRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/articles/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->getCurrentTabId() == self::$ARTICLES_TAB) {
            $content = new ArticleTab($this->articleRequestHandler);
        } else if ($this->getCurrentTabId() == self::$TERMS_TAB) {
            $content = new TermTab($this->currentTerm);
        } else if ($this->getCurrentTabId() == self::$TARGET_PAGES_TAB) {
            $content = new TargetPagesList();
        }
        $this->assign("content", $content?->render());
    }

    public function getRequestHandlers(): array {
        $preHandlers = array();
        if ($this->getCurrentTabId() == self::$ARTICLES_TAB) {
            $preHandlers[] = $this->articleRequestHandler;
        } else if ($this->getCurrentTabId() == self::$TERMS_TAB) {
            $preHandlers[] = $this->termRequestsHandler;
        } else if ($this->getCurrentTabId() == self::$TARGET_PAGES_TAB) {
            $preHandlers[] = $this->targetPagesRequestHandler;
        }
        return $preHandlers;
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        if ($this->getCurrentTabId() == self::$ARTICLES_TAB) {
            $saveButton = null;
            $deleteButton = null;
            if ($this->currentArticle) {
                $saveButton = new ActionButtonSave('update_element_holder');
                $deleteButton = new ActionButtonDelete('delete_element_holder');
            }
            $actionButtons[] = $saveButton;
            $actionButtons[] = new ActionButtonAdd('add_element_holder');
            $actionButtons[] = $deleteButton;
        }
        if ($this->getCurrentTabId() == self::$TERMS_TAB) {
            if ($this->currentTerm || TermTab::isEditTermMode()) {
                $actionButtons[] = new ActionButtonSave('update_term');
            }
            $actionButtons[] = new ActionButtonAdd('add_term');
            $actionButtons[] = new ActionButtonDelete('delete_terms');
        }
        if ($this->getCurrentTabId() == self::$TARGET_PAGES_TAB) {
            $actionButtons[] = new ActionButtonDelete('delete_target_pages');
        }

        return $actionButtons;
    }

    public function renderHeadIncludes(): string {
        ->assign("path", $this->articleModule->getIdentifier());

        $elementStaticValues = array();
        if ($this->currentArticle) {
            $elementStatics = $this->currentArticle->getElementStatics();
            foreach ($elementStatics as $elementStatic) {
                $elementStaticValues[] = $elementStatic->render();
            }
        }
        ->assign("element_statics", $elementStaticValues);
        ->assign("path", $this->articleModule->getIdentifier());
        return ->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function onRequestHandled(): void {
        $this->currentArticle = $this->articleRequestHandler->getCurrentArticle();
        $this->currentTerm = $this->termRequestsHandler->getCurrentTerm();
    }

    public function loadTabMenu(TabMenu $tabMenu): int {
        $tabMenu->addItem("articles_tab_articles", self::$ARTICLES_TAB);
        $tabMenu->addItem("articles_tab_terms", self::$TERMS_TAB);
        $tabMenu->addItem("articles_tab_target_pages", self::$TARGET_PAGES_TAB);
        return $this->getCurrentTabId();
    }

}