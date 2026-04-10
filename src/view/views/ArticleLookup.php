<?php

namespace Obcato\Core\view\views;

use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\view\TemplateData;

class ArticleLookup extends FormField {

    private ?string $deleteFieldName;
    private ?int $excludeArticleId;
    private ArticleService $articleService;

    public function __construct(
        string $name,
        ?string $labelResourceIdentifier,
        ?string $value,
        ?string $deleteFieldName = null,
        ?int $excludeArticleId = null,
        ?string $className = null
    ) {
        parent::__construct($name, $value, $labelResourceIdentifier, false, false, $className);
        $this->deleteFieldName = $deleteFieldName;
        $this->excludeArticleId = $excludeArticleId;
        $this->articleService = ArticleInteractor::getInstance();
    }

    public function getFormFieldTemplateFilename(): string {
        return "article_lookup.tpl";
    }

    public function getFieldType(): string {
        return 'article_lookup';
    }

    public function loadFormField(TemplateData $data): void {
        $selectedArticleId = $this->getValue();
        $selectedArticleTitle = '';

        if (!empty($selectedArticleId)) {
            $selectedArticle = $this->articleService->getArticle((int)$selectedArticleId);
            if ($selectedArticle) {
                $selectedArticleTitle = $selectedArticle->getTitle() ?? '';
            }
        }

        $data->assign('selected_article_id', $selectedArticleId);
        $data->assign('selected_article_title', $selectedArticleTitle);
        $data->assign('delete_field_name', $this->deleteFieldName);
        $data->assign('exclude_article_id', $this->excludeArticleId ?? 0);
        $data->assign('search_endpoint', '/admin/api/article/search');
    }
}
