<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/image_picker.php";
    require_once CMS_ROOT . 'database/dao/article_dao.php';
    require_once CMS_ROOT . 'friendly_urls/friendly_url_manager.php';

    class ArticleMetadataEditor extends Panel {

        private static $ARTICLE_METADATA_TEMPLATE = "articles/articles/metadata.tpl";

        private Article $_current_article;
        private ArticleDao $_article_dao;
        private FriendlyUrlManager $_friendly_url_manager;

        public function __construct(Article $current_article) {
            parent::__construct('Algemeen', 'article_metadata_editor');
            $this->_current_article = $current_article;
            $this->_article_dao = ArticleDao::getInstance();
            $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $title_field = new TextField("article_title", $this->getTextResource('article_editor_title_label'), $this->_current_article->getTitle(), true, false, null);
            $url_field = new ReadonlyTextField('friendly_url', $this->getTextResource('friendly_url_label'), $this->_friendly_url_manager->getFriendlyUrlForElementHolder($this->_current_article), '');
            $description_field = new TextArea("article_description", $this->getTextResource('article_editor_description_label'), $this->_current_article->getDescription(), false, true, null);
            $published_field = new SingleCheckbox("article_published", $this->getTextResource('article_editor_published_label'), $this->_current_article->isPublished(), false, "");
            $publication_date_field = new DateField("publication_date", $this->getTextResource('article_editor_publication_date_label'), $this->getDateValue($this->_current_article->getPublicationDate()), true, null);
            $sort_date_field = new DateField("sort_date", $this->getTextResource('article_editor_sort_date_label'), $this->getDateValue($this->_current_article->getSortDate()), true, null);
            $target_pages_field = new Pulldown("article_target_page", $this->getTextResource('article_editor_target_page_label'), $this->_current_article->getTargetPageId(), $this->getTargetPageOptions(), false, null);
            $image_picker_field = new ImagePicker($this->getTextResource('article_editor_image_label'), $this->_current_article->getImageId(), "article_image_ref_" . $this->_current_article->getId(), "update_element_holder", null);
            $image_delete_button = new Button("delete_lead_image", $this->getTextResource('article_editor_delete_image_button_label'), null);

            $this->assignElementHolderFormIds();
            $this->getTemplateEngine()->assign("current_article_id", $this->_current_article->getId());
            $this->getTemplateEngine()->assign("title_field", $title_field->render());
            $this->getTemplateEngine()->assign('url_field', $url_field->render());
            $this->getTemplateEngine()->assign("description_field", $description_field->render());
            $this->getTemplateEngine()->assign("published_field", $published_field->render());
            $this->getTemplateEngine()->assign("publication_date_field", $publication_date_field->render());
            $this->getTemplateEngine()->assign("sort_date_field", $sort_date_field->render());
            $this->getTemplateEngine()->assign("target_pages_field", $target_pages_field->render());
            $this->getTemplateEngine()->assign("image_picker_field", $image_picker_field->render());
            $this->getTemplateEngine()->assign("lead_image_id", $this->_current_article->getImageId());
            $this->getTemplateEngine()->assign("delete_lead_image_button", $image_delete_button->render());

            return $this->getTemplateEngine()->fetch("modules/" . self::$ARTICLE_METADATA_TEMPLATE);
        }

        private function getDateValue(string $date): string {
            return DateUtility::mysqlDateToString($date, '-');
        }

        private function getTargetPageOptions(): array {
            $target_page_options = array();
            array_push($target_page_options, array("name" => "&gt; Selecteer", "value" => ""));

            $all_target_pages = $this->_article_dao->getTargetPages();
            foreach ($all_target_pages as $article_target_page) {
                array_push($target_page_options, array("name" => $article_target_page->getTitle(), "value" => $article_target_page->getId()));
            }
            return $target_page_options;
        }

        private function assignElementHolderFormIds(): void {
            $this->getTemplateEngine()->assign("add_element_form_id", ADD_ELEMENT_FORM_ID);
            $this->getTemplateEngine()->assign("edit_element_holder_id", EDIT_ELEMENT_HOLDER_ID);
            $this->getTemplateEngine()->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
            $this->getTemplateEngine()->assign("action_form_id", ACTION_FORM_ID);
            $this->getTemplateEngine()->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
            $this->getTemplateEngine()->assign("element_order_id", ELEMENT_ORDER_ID);
        }

    }

?>
