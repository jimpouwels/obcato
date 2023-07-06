<?php
    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "frontend/frontend_visual.php";
    require_once CMS_ROOT . "frontend/block_visual.php";
    require_once CMS_ROOT . "frontend/form_visual.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";

    class PageVisual extends FrontendVisual {
        private PageDao $_page_dao;
        private ArticleDao $_article_dao;
        private WebFormDao $_webform_dao;
        private WebFormItemFactory $_webform_item_factory;

        public function __construct(Page $page, ?Article $article) {
            parent::__construct($page, $article);
            $this->_page_dao = PageDao::getInstance();
            $this->_webform_dao = WebFormDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_webform_item_factory = WebFormItemFactory::getInstance();
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->getPage()->getTemplate()->getFileName();
        }

        public function load(): void {
            $this->assign("website_title", WEBSITE_TITLE);
            $this->assign("page", $this->getPageContentAndMetaData($this->getPage()));
            $this->assign("page_title", $this->getPage()->getTitle());
            if (!is_null($this->getArticle()) && $this->getArticle()->isPublished()) {
                $article_data = $this->renderArticle();
                $this->assign("article", $article_data);
                $this->assign("page_title", $this->getArticle()->getTitle());
            } else {
                $this->assign("article", null);
            }
            $this->assign("canonical_url", $this->getCanonicalUrl()); 
            $this->assign("root_page", $this->getPageMetaData($this->_page_dao->getRootPage()));
        }

        private function getPageContentAndMetaData(Page $page): array {
            $page_data = array();
            $page_data["elements"] = $this->renderElementHolderContent($page);
            $page_data["blocks"] = $this->renderBlocks();
            $this->addPageMetaData($page, $page_data);
            return $page_data;
        }

        private function getPageMetaData(Page $page): array {
            $page_data = array();
            $this->addPageMetaData($page, $page_data);
            return $page_data;
        }

        private function renderChildren(Page $page): array {
            $children = array();
            foreach ($page->getSubPages() as $subPage) {
                if (!$subPage->isPublished()) continue;
                $child = array();
                $this->addPageMetaData($subPage, $child, false);
                $children[] = $child;
            }
            return $children;
        }

        private function addPageMetaData(Page $page, array &$page_data, bool $render_childen = true): void {
            $page_data["is_current_page"] = $this->getPage()->getId() == $page->getId();
            $page_data["title"] = $page->getTitle();
            $page_data["url"] = $this->getPageUrl($page);
            $page_data["is_homepage"] = $page->isHomepage();
            $page_data["navigation_title"] = $page->getNavigationTitle();
            $page_description = $page->getDescription();
            if (!is_null($this->getArticle()) && $this->getArticle()->isPublished()) {
                $page_description = $this->getArticle()->getDescription();
            }
            $page_data["description"] = $this->toHtml($page_description, $page);
            $page_data["show_in_navigation"] = $page->getShowInNavigation();
            if ($render_childen) {
                $page_data["children"] = $this->renderChildren($page);
            }
        }

        private function renderBlocks(): array {
            $blocks = array();
            $blocks['no_position'] = array();
            foreach ($this->getPage()->getBlocks() as $block) {
                if (!$block->isPublished()) continue;
                $position = $block->getPosition();
                if (!is_null($position)) {
                    $positionName = $position->getName();
                    if (!isset($blocks[$positionName]))
                        $blocks[$positionName] = array();
                    $blocks[$positionName][] = $this->renderBlock($block);
                } else {
                    $blocks["no_position"][] = $this->renderBlock($block);
                }
            }
            return $blocks;
        }

        private function renderBlock($block) {
            $block_visual = new BlockVisual($block, $this->getPage());
            return $block_visual->render();
        }
        
        private function renderArticle(): array {
            $article_data = array();
            $article_data["id"] = $this->getArticle()->getId();
            $article_data["title"] = $this->getArticle()->getTitle();
            $article_data["description"] = $this->getArticle()->getDescription();
            $article_data["publication_date"] = $this->getArticle()->getPublicationDate();
            $article_data["image"] = $this->getImageData($this->getArticle()->getImage());
            $article_data["elements"] = $this->renderElementHolderContent($this->getArticle());
            $article_data["comments"] = $this->renderArticleComments($this->getArticle());
            $article_data["comment_webform"] = $this->renderArticleCommentWebForm($this->getArticle());
            $article_template_data = $this->createChildData();
            foreach ($article_data as $key => $value) {
                $article_template_data->assign($key, $value);
            }
            $article_data["to_string"] = $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->getArticle()->getTemplate()->getFileName(), $article_template_data);
            return $article_data;
        }

        private function getImageData($image): ?array {
            $image_data = null;
            if (!is_null($image)) {
                $image_data = array();
                $image_data["title"] = $image->getTitle();
                $image_data["url"] = $this->getImageUrl($image);
            }
            return $image_data;
        }

        private function renderArticleComments(Article $article): array {
            $comments_data = array();
            foreach ($this->_article_dao->getArticleComments($article->getId()) as $comment) {
                $comment_data = array();
                $comment_data['id'] = htmlspecialchars($comment->getId());
                $comment_data['name'] = htmlspecialchars($comment->getName());
                $comment_data['message'] = htmlspecialchars($comment->getMessage());
                $comment_data['created_at'] = $comment->getCreatedAt();
                $child_comments = array();
                foreach ($this->_article_dao->getChildArticleComments($comment->getId()) as $child) {
                    $child_comment_data = array();
                    $child_comment_data['id'] = htmlspecialchars($child->getId());               
                    $child_comment_data['created_at'] = $child->getCreatedAt();
                    $child_comment_data['name'] = htmlspecialchars($child->getName());
                    $child_comment_data['message'] = htmlspecialchars($child->getMessage());
                    $child_comments[] = $child_comment_data;
                }
                $comment_data['children'] = $child_comments;
                $comments_data[] = $comment_data;
            }
            return $comments_data;
        }

        private function renderArticleCommentWebForm($article): string {
            if (!is_null($article->getCommentWebFormId())) {
                $comment_webform = $this->_webform_dao->getWebForm($article->getCommentWebFormId());
                $form_visual = new FormFrontendVisual($this->getPage(), $article, $comment_webform);
                return $form_visual->render();
            }
            return "";
        }

        private function renderElementHolderContent(ElementHolder $element_holder) {
            $elements_content = array();
            foreach ($element_holder->getElements() as $element) {
                if ($element->getTemplate()) {
                    $elements_content[] = $element->getFrontendVisual($this->getPage(), $this->getArticle())->render();
                }
            }
            return $elements_content;
        }

    }
