<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/image_picker.php";
require_once CMS_ROOT . "/view/views/article_picker.php";
require_once CMS_ROOT . '/database/dao/ArticleDaoMysql.php';
require_once CMS_ROOT . '/database/dao/webform_dao.php';
require_once CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php';

class ArticleMetadataEditor extends Panel {

    private Article $_current_article;
    private ArticleDao $_article_dao;
    private WebFormDao $_webform_dao;
    private FriendlyUrlManager $_friendly_url_manager;

    public function __construct(Article $current_article) {
        parent::__construct('Algemeen', 'article_metadata_editor');
        $this->_current_article = $current_article;
        $this->_article_dao = ArticleDaoMysql::getInstance();
        $this->_webform_dao = WebFormDao::getInstance();
        $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/articles/metadata.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $title_field = new TextField("article_title", $this->getTextResource('article_editor_title_label'), $this->_current_article->getTitle(), true, false, null);
        $template_picker_field = new TemplatePicker("template", $this->getTextResource("article_editor_template_field"), false, "", $this->_current_article->getTemplate(), $this->_current_article->getScope());
        $url_field = new ReadonlyTextField('friendly_url', $this->getTextResource('friendly_url_label'), $this->_friendly_url_manager->getFriendlyUrlForElementHolder($this->_current_article), '');
        $keywords_field = new TextField('keywords', $this->getTextResource('article_editor_keyword_field'), $this->_current_article->getKeywords(), false, false, "keywords_field");
        $description_field = new TextArea("article_description", $this->getTextResource('article_editor_description_label'), $this->_current_article->getDescription(), false, true, null);
        $published_field = new SingleCheckbox("article_published", $this->getTextResource('article_editor_published_label'), $this->_current_article->isPublished(), false, "");
        $publication_date_field = new DateField("publication_date", $this->getTextResource('article_editor_publication_date_label'), $this->getDateValue($this->_current_article->getPublicationDate()), true, null);
        $sort_date_field = new DateField("sort_date", $this->getTextResource('article_editor_sort_date_label'), $this->getDateValue($this->_current_article->getSortDate()), true, null);
        $target_pages_field = new Pulldown("article_target_page", $this->getTextResource('article_editor_target_page_label'), $this->_current_article->getTargetPageId(), $this->getTargetPageOptions(), false, null, true);
        $comment_forms_field = new PullDown("article_comment_webform", $this->getTextResource('article_editor_comment_webform_label'), $this->_current_article->getCommentWebFormId(), $this->getWebFormsOptions(), false, null, true);
        $parent_article_picker_field = new ArticlePicker("parent_article_id", $this->getTextResource('article_editor_parent_article_label'), $this->_current_article->getParentArticleId(), "update_element_holder");
        $parent_article_delete_button = new Button("delete_parent_article", $this->getTextResource('article_editor_delete_parent_article_label'), null);
        $image_picker_field = new ImagePicker("article_image_ref_" . $this->_current_article->getId(), $this->getTextResource('article_editor_image_label'), $this->_current_article->getImageId(), "update_element_holder");
        $image_delete_button = new Button("delete_lead_image", $this->getTextResource('article_editor_delete_image_button_label'), null);

        if (!is_null($this->_current_article->getParentArticleId())) {
            $parent_article = $this->_article_dao->getArticle($this->_current_article->getParentArticleId());
            $data->assign('parent_article', $this->renderParentArticle($parent_article));
        }

        $data->assign("child_articles", $this->renderChildArticles());

        $data->assign("current_article_id", $this->_current_article->getId());
        $data->assign("title_field", $title_field->render());
        $data->assign('template_field', $template_picker_field->render());
        $data->assign('keywords_field', $keywords_field->render());
        $data->assign('url_field', $url_field->render());
        $data->assign("description_field", $description_field->render());
        $data->assign("published_field", $published_field->render());
        $data->assign("publication_date_field", $publication_date_field->render());
        $data->assign("sort_date_field", $sort_date_field->render());
        $data->assign("target_pages_field", $target_pages_field->render());
        $data->assign("parent_article_field", $parent_article_picker_field->render());
        $data->assign("delete_parent_article_button", $parent_article_delete_button->render());
        $data->assign("comment_forms_field", $comment_forms_field->render());
        $data->assign("image_picker_field", $image_picker_field->render());
        $data->assign("lead_image_id", $this->_current_article->getImageId());
        $data->assign("delete_lead_image_button", $image_delete_button->render());
        $this->assignElementHolderFormIds($data);
    }

    private function renderChildArticles(): array {
        $child_articles_data = array();
        $child_articles = $this->_article_dao->getAllChildArticles($this->_current_article->getId());
        foreach ($child_articles as $child_article) {
            $child_article_data = array();
            $child_article_data['id'] = $child_article->getId();
            $child_article_data['title'] = $child_article->getTitle();
            $child_article_data['url'] = "{$this->getBackendBaseUrl()}&article={$child_article->getId()}";
            $child_articles_data[] = $child_article_data;
        }
        return $child_articles_data;
    }

    private function renderParentArticle(Article $parent_article): array {
        $article_data = array();
        $article_data['id'] = $parent_article->getId();
        $article_data['title'] = $parent_article->getTitle();
        $article_data['url'] = "{$this->getBackendBaseUrl()}&article={$parent_article->getId()}";
        return $article_data;
    }

    private function getDateValue(string $date): string {
        return DateUtility::mysqlDateToString($date, '-');
    }

    private function getTargetPageOptions(): array {
        $target_page_options = array();

        $all_target_pages = $this->_article_dao->getTargetPages();
        foreach ($all_target_pages as $article_target_page) {
            $target_page_options[] = array("name" => $article_target_page->getTitle(), "value" => $article_target_page->getId());
        }
        return $target_page_options;
    }

    private function getWebFormsOptions(): array {
        $webforms_options = array();

        $all_webforms = $this->_webform_dao->getAllWebForms();
        foreach ($all_webforms as $webform) {
            $webforms_options[] = array("name" => $webform->getTitle(), "value" => $webform->getId());
        }
        return $webforms_options;
    }

    private function assignElementHolderFormIds(Smarty_Internal_Data $data): void {
        $data->assign("add_element_form_id", ADD_ELEMENT_FORM_ID);
        $data->assign("edit_element_holder_id", EDIT_ELEMENT_HOLDER_ID);
        $data->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
        $data->assign("action_form_id", ACTION_FORM_ID);
        $data->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
    }

}

?>
