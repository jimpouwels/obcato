<?php

namespace Obcato\Core;

use Obcato\Core\admin\core\form\Form;

class TargetPagesForm extends Form {

    private string $targetPageToAdd;
    private string $newDefaultTargetPage;
    private array $targetPagesToDelete;
    private ArticleDao $articleDao;

    public function __construct() {
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $this->targetPageToAdd = $this->getFieldValue("add_target_page_ref");
        $this->newDefaultTargetPage = $this->getFieldValue("new_default_target_page");
        $this->loadTargetPagesToDelete();
    }

    public function getTargetPageToAdd(): string {
        return $this->targetPageToAdd;
    }

    public function getNewDefaultTargetPage(): string {
        return $this->newDefaultTargetPage;
    }

    public function getTargetPagesToDelete(): array {
        return $this->targetPagesToDelete;
    }

    private function loadTargetPagesToDelete(): void {
        $targetPages = $this->articleDao->getTargetPages();
        foreach ($targetPages as $targetPage) {
            $fieldToCheck = "target_page_" . $targetPage->getId() . "_delete";
            if (isset($_POST[$fieldToCheck]) && $_POST[$fieldToCheck] != "") {
                $this->targetPagesToDelete[] = $targetPage;
            }
        }
    }

}
    