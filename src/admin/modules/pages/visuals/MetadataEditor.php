<?php

namespace Obcato\Core\admin\modules\pages\visuals;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\friendly_urls\FriendlyUrlManager;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\ReadonlyTextField;
use Obcato\Core\admin\view\views\SingleCheckbox;
use Obcato\Core\admin\view\views\TemplatePicker;
use Obcato\Core\admin\view\views\TextArea;
use Obcato\Core\admin\view\views\TextField;
use const Obcato\Core\admin\ACTION_FORM_ID;
use const Obcato\Core\admin\ADD_ELEMENT_FORM_ID;
use const Obcato\Core\admin\DELETE_ELEMENT_FORM_ID;
use const Obcato\Core\admin\EDIT_ELEMENT_HOLDER_ID;
use const Obcato\Core\admin\ELEMENT_HOLDER_FORM_ID;

class MetadataEditor extends Panel {

    private Page $currentPage;
    private FriendlyUrlManager $friendUrlManager;

    public function __construct( Page $currentPage) {
        parent::__construct( $this->getTextResource('edit_metadata_title'), "page_metadata_editor");
        $this->currentPage = $currentPage;
        $this->friendUrlManager = FriendlyUrlManager::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/pages/metadata.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $titleField = new TextField("page_title", $this->getTextResource('pages_edit_metadata_title_field_label'), $this->currentPage->getTitle(), true, false, null);
        $urlTitleField = new TextField("url_title", $this->getTextResource('pages_edit_metadata_url_title_field_label'), $this->currentPage->getUrlTitle(), false, false, null);
        $navigationTitleField = new TextField("navigation_title", $this->getTextResource('pages_edit_metadata_navigation_title_field_label'), $this->currentPage->getNavigationTitle(), true, false, null);
        $keywordsField = new TextField("keywords", $this->getTextResource('pages_edit_metadata_keywords_field_label'), $this->currentPage->getKeywords(), false, false, "keywords_field");
        $urlField = new ReadonlyTextField('friendly_url', $this->getTextResource('pages_edit_metadata_friendly_url_label'), $this->friendUrlManager->getFriendlyUrlForElementHolder($this->currentPage), '');
        $descriptionField = new TextArea("description", $this->getTextResource('pages_edit_metadata_description_field_label'), $this->currentPage->getDescription(), false, true, null);
        $publishedField = new SingleCheckbox("published", $this->getTextResource('pages_edit_metadata_ispublished_field_label'), $this->currentPage->isPublished(), false, "");
        $includeInSearchEngineField = new SingleCheckbox("include_in_search_engine", $this->getTextResource('pages_edit_metadata_include_in_search_engine_field_label'), $this->currentPage->getIncludeInSearchEngine(), false, "");
        $showInNavigationField = new SingleCheckbox("show_in_navigation", $this->getTextResource('pages_edit_metadata_showinnavigation_field_label'), $this->currentPage->getShowInNavigation(), false, "");
        $templatePickerField = new TemplatePicker("page_template", $this->getTextResource('pages_edit_metadata_template_field_label'), false, "", $this->currentPage->getTemplate(), $this->currentPage->getScope());

        $data->assign("current_page_id", $this->currentPage->getId());
        $data->assign("page_title_field", $titleField->render());
        $data->assign("keywords_field", $keywordsField->render());
        $data->assign("navigation_title_field", $navigationTitleField->render());
        $data->assign('url_title_field', $urlTitleField->render());
        $data->assign('url_field', $urlField->render());
        $data->assign("description_field", $descriptionField->render());
        $data->assign("published_field", $publishedField->render());
        $data->assign("include_in_search_engine_field", $includeInSearchEngineField->render());
        $data->assign("show_in_navigation_field", $showInNavigationField->render());
        $data->assign("template_picker_field", $templatePickerField->render());
        $this->assignElementHolderFormIds($data);
    }

    private function assignElementHolderFormIds(TemplateData $data): void {
        $data->assign("add_element_form_id", ADD_ELEMENT_FORM_ID);
        $data->assign("edit_element_holder_id", EDIT_ELEMENT_HOLDER_ID);
        $data->assign("action_form_id", ACTION_FORM_ID);
        $data->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
        $data->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
    }
}
