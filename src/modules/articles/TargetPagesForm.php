<?php

namespace Obcato\Core\modules\articles;

use Obcato\Core\core\form\Form;
use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;

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
    