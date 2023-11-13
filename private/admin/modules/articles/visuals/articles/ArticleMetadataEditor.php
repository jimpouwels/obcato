<?php
require_once CMS_ROOT . "/view/views/ImagePicker.php";
require_once CMS_ROOT . "/view/views/ArticlePicker.php";
require_once CMS_ROOT . '/database/dao/ArticleDaoMysql.php';
require_once CMS_ROOT . '/database/dao/WebformDaoMysql.php';
require_once CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php';

class ArticleMetadataEditor extends Panel {

    private Article $currentArticle;
    private ArticleDao $articleDao;
    private WebformDao $webformDao;
    private FriendlyUrlManager $friendlyUrlManager;

    public function __construct(Article $currentArticle) {
        parent::__construct('Algemeen', 'article_metadata_editor');
        $this->currentArticle = $currentArticle;
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->webformDao = WebformDaoMysql::getInstance();
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/articles/metadata.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $titleField = new TextField("title", $this->getTextResource('article_editor_title_label'), $this->currentArticle->getTitle(), true, false, null);
        $templatePickerField = new TemplatePicker("template", $this->getTextResource("article_editor_template_field"), false, "", $this->currentArticle->getTemplate(), $this->currentArticle->getScope());
        $urlTitleField = new TextField('url_title', $this->getTextResource('article_editor_url_title_field'), $this->currentArticle->getUrlTitle(), false, false, "");
        $urlField = new ReadonlyTextField('friendly_url', $this->getTextResource('friendly_url_label'), $this->friendlyUrlManager->getFriendlyUrlForElementHolder($this->currentArticle), '');
        $keywordsField = new TextField('keywords', $this->getTextResource('article_editor_keyword_field'), $this->currentArticle->getKeywords(), false, false, "keywords_field");
        $descriptionField = new TextArea("article_description", $this->getTextResource('article_editor_description_label'), $this->currentArticle->getDescription(), false, true, null);
        $publishedField = new SingleCheckbox("article_published", $this->getTextResource('article_editor_published_label'), $this->currentArticle->isPublished(), false, "");
        $publicationDateField = new DateField("publication_date", $this->getTextResource('article_editor_publication_date_label'), $this->getDateValue($this->currentArticle->getPublicationDate()), true, null);
        $sortDateField = new DateField("sort_date", $this->getTextResource('article_editor_sort_date_label'), $this->getDateValue($this->currentArticle->getSortDate()), true, null);
        $targetPagesField = new Pulldown("article_target_page", $this->getTextResource('article_editor_target_page_label'), $this->currentArticle->getTargetPageId(), $this->getTargetPageOptions(), false, null, true);
        $commentFormsField = new Pulldown("article_comment_webform", $this->getTextResource('article_editor_comment_webform_label'), $this->currentArticle->getCommentWebFormId(), $this->getWebFormsOptions(), false, null, true);
        $parentArticlePicker = new ArticlePicker("parent_article_id", $this->getTextResource('article_editor_parent_article_label'), $this->currentArticle->getParentArticleId(), "update_element_holder");
        $parentArticleDeleteButton = new Button("delete_parent_article", $this->getTextResource('article_editor_delete_parent_article_label'), null);
        $imagePickerField = new ImagePicker("article_image_ref_" . $this->currentArticle->getId(), $this->getTextResource('article_editor_image_label'), $this->currentArticle->getImageId(), "update_element_holder");
        $imageDeleteButton = new Button("delete_lead_image", $this->getTextResource('article_editor_delete_image_button_label'), null);

        if ($this->currentArticle->getParentArticleId()) {
            $parentArticle = $this->articleDao->getArticle($this->currentArticle->getParentArticleId());
            $data->assign('parent_article', $this->renderParentArticle($parentArticle));
        }

        $data->assign("child_articles", $this->renderChildArticles());
        $data->assign("current_article_id", $this->currentArticle->getId());
        $data->assign("title_field", $titleField->render());
        $data->assign('template_field', $templatePickerField->render());
        $data->assign('keywords_field', $keywordsField->render());
        $data->assign('url_field', $urlField->render());
        $data->assign('url_title_field', $urlTitleField->render());
        $data->assign("description_field", $descriptionField->render());
        $data->assign("published_field", $publishedField->render());
        $data->assign("publication_date_field", $publicationDateField->render());
        $data->assign("sort_date_field", $sortDateField->render());
        $data->assign("target_pages_field", $targetPagesField->render());
        $data->assign("parent_article_field", $parentArticlePicker->render());
        $data->assign("delete_parent_article_button", $parentArticleDeleteButton->render());
        $data->assign("comment_forms_field", $commentFormsField->render());
        $data->assign("image_picker_field", $imagePickerField->render());
        $data->assign("lead_image_id", $this->currentArticle->getImageId());
        $data->assign("delete_lead_image_button", $imageDeleteButton->render());
        $this->assignElementHolderFormIds($data);
    }

    private function renderChildArticles(): array {
        $childArticlesData = array();
        $childArticles = $this->articleDao->getAllChildArticles($this->currentArticle->getId());
        foreach ($childArticles as $childArticle) {
            $childArticleData = array();
            $childArticleData['id'] = $childArticle->getId();
            $childArticleData['title'] = $childArticle->getTitle();
            $childArticleData['url'] = "{$this->getBackendBaseUrl()}&article={$childArticle->getId()}";
            $childArticlesData[] = $childArticleData;
        }
        return $childArticlesData;
    }

    private function renderParentArticle(Article $parentArticle): array {
        $articleData = array();
        $articleData['id'] = $parentArticle->getId();
        $articleData['title'] = $parentArticle->getTitle();
        $articleData['url'] = "{$this->getBackendBaseUrl()}&article={$parentArticle->getId()}";
        return $articleData;
    }

    private function getDateValue(string $date): string {
        return DateUtility::mysqlDateToString($date, '-');
    }

    private function getTargetPageOptions(): array {
        $targetPageOption = array();

        $allTargetPages = $this->articleDao->getTargetPages();
        foreach ($allTargetPages as $articleTargetPage) {
            $targetPageOption[] = array("name" => $articleTargetPage->getTitle(), "value" => $articleTargetPage->getId());
        }
        return $targetPageOption;
    }

    private function getWebFormsOptions(): array {
        $webformOptions = array();

        $allWebforms = $this->webformDao->getAllWebForms();
        foreach ($allWebforms as $webform) {
            $webformOptions[] = array("name" => $webform->getTitle(), "value" => $webform->getId());
        }
        return $webformOptions;
    }

    private function assignElementHolderFormIds(Smarty_Internal_Data $data): void {
        $data->assign("add_element_form_id", ADD_ELEMENT_FORM_ID);
        $data->assign("edit_element_holder_id", EDIT_ELEMENT_HOLDER_ID);
        $data->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
        $data->assign("action_form_id", ACTION_FORM_ID);
        $data->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
    }

}