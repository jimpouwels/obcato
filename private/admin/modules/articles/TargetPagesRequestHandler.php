<?php
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . "/modules/articles/TargetPagesForm.php";

class TargetPagesRequestHandler extends HttpRequestHandler {

    private TargetPagesForm $targetPagesForm;
    private ArticleDao $articleDao;

    public function __construct() {
        $this->targetPagesForm = new TargetPagesForm();
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        $this->targetPagesForm->loadFields();
        if ($this->isUpdateOptionsAction()) {
            $this->updateOptions();
        }
        if ($this->isChangeDefaultTargetPageAction()) {
            $this->changeDefaultTargetPage();
        }
        if ($this->isDeleteTargetPagesAction()) {
            $this->deleteTargetPages();
        }
    }

    private function deleteTargetPages(): void {
        foreach ($this->targetPagesForm->getTargetPagesToDelete() as $targetPageToDelete) {
            $this->articleDao->deleteTargetPage($targetPageToDelete);
        }
    }

    private function changeDefaultTargetPage(): void {
        $newDefaultTargetPage = $this->targetPagesForm->getNewDefaultTargetPage();
        if ($newDefaultTargetPage) {
            $this->articleDao->setDefaultArticleTargetPage($newDefaultTargetPage);
        }
    }

    private function updateOptions(): void {
        $targetPageToAdd = $this->targetPagesForm->getTargetPageToAdd();
        if ($targetPageToAdd != "") {
            $this->articleDao->addTargetPage($targetPageToAdd);
        }
    }

    private function isUpdateOptionsAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "target_page_to_add";
    }

    private function isChangeDefaultTargetPageAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "change_default_target_page";
    }

    private function isDeleteTargetPagesAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "delete_target_pages";
    }
}