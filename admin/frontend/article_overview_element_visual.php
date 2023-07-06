<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";
    require_once CMS_ROOT . "utilities/date_utility.php";

    class ArticleOverviewElementFrontendVisual extends ElementFrontendVisual {


        public function __construct(Page $page, ?Article $article, ArticleOverviewElement $article_overview_element) {
            parent::__construct($page, $article, $article_overview_element);
        }

        public function loadElement(Smarty_Internal_Data $data): void {
            $data->assign("title", $this->toHtml($this->getElement()->getTitle(), $this->getElementHolder()));
            $data->assign("articles", $this->getArticles());
        }

        private function getArticles(): array {
            $articles = $this->getElement()->getArticles();
            $articles_arr = array();
            foreach ($articles as $article) {
                if (!$this->isPublished($article)) continue;
                $article_item = array();
                $article_item["id"] = $article->getId();
                $article_item["title"] = $article->getTitle();
                $article_item["url"] = $this->getArticleUrl($article);
                $article_item["description"] = $this->toHtml($article->getDescription(), $article);
                $article_item["publication_date"] = DateUtility::mysqlDateToString($article->getPublicationDate(), '-');
                $article_item["sort_date_in_past"] = strtotime($article->getSortDate()) < strtotime(date('Y-m-d H:i:s', strtotime('00:00:00')));
                $article_item["sort_date"] = DateUtility::mysqlDateToString($article->getSortDate(), '-');
                $article_item["image"] = $this->getArticleImage($article);
                $articles_arr[] = $article_item;
            }
            return $articles_arr;
        }

        private function getArticleImage(Article $article): ?array {
            $image = null;
            if (!is_null($article->getImage())) {
                $image = array();
                $image["title"] = $article->getImage()->getTitle();
                $image["alt_text"] = $article->getImage()->getAltText();
                $image["url"] = $this->getImageUrl($article->getImage());
            }
            return $image;
        }

        private function isPublished(Article $article): bool {
            return $article->isPublished() && strtotime($article->getPublicationDate()) < strtotime('now');
        }
    }

?>
