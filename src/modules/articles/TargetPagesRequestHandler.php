<?php

namespace Obcato\Core\modules\articles;

use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\request_handlers\HttpRequestHandler;

class TargetPagesRequestHandler extends HttpRequestHandler {

    private TargetPagesForm $targetPagesForm;
    private ArticleService $articleService;

    public function __construct() {
        $this->targetPagesForm = new TargetPagesForm();
        $this->articleService = ArticleInteractor::getInstance();
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
            $this->articleService->deleteTargetPage($targetPageToDelete);
        }
    }

    private function changeDefaultTargetPage(): void {
        $newDefaultTargetPage = $this->targetPagesForm->getNewDefaultTargetPage();
        if ($newDefaultTargetPage) {
            $this->articleService->setDefaultArticleTargetPage($newDefaultTargetPage);
        }
    }

    private function updateOptions(): void {
        $targetPageToAdd = $this->targetPagesForm->getTargetPageToAdd();
        if ($targetPageToAdd != "") {
            $this->articleService->addTargetPage($targetPageToAdd);
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