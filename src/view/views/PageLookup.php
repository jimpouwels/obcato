<?php

namespace Pageflow\Core\view\views;

use Pageflow\Core\modules\pages\service\PageInteractor;
use Pageflow\Core\modules\pages\service\PageService;
use Pageflow\Core\view\TemplateData;

class PageLookup extends FormField {

    private PageService $pageService;
    private ?string $deleteFieldName;
    private ?int $excludePageId;
    private bool $allowRemove;
    private ?string $submitClickId;

    public function __construct(
        string $name,
        ?string $labelResourceIdentifier,
        ?string $value,
        string $modalTitleResourceIdentifier,
        string $selectedLabelResourceIdentifier,
        bool $allowRemove = true,
        ?string $deleteFieldName = null,
        ?int $excludePageId = null,
        ?string $submitClickId = null,
        ?string $className = null
    ) {
        parent::__construct($name, $value, $labelResourceIdentifier, false, false, $className);
        $this->pageService = PageInteractor::getInstance();
        $this->deleteFieldName = $deleteFieldName;
        $this->excludePageId = $excludePageId;
        $this->allowRemove = $allowRemove;
        $this->submitClickId = $submitClickId;

        $this->modalTitleResourceIdentifier = $modalTitleResourceIdentifier;
        $this->selectedLabelResourceIdentifier = $selectedLabelResourceIdentifier;
    }

    private string $modalTitleResourceIdentifier;
    private string $selectedLabelResourceIdentifier;

    public function getFormFieldTemplateFilename(): string {
        return 'page_lookup.tpl';
    }

    public function getFieldType(): string {
        return 'page_lookup';
    }

    public function loadFormField(TemplateData $data): void {
        $selectedPageId = $this->getValue();
        $selectedPageTitle = '';

        if (!empty($selectedPageId)) {
            $page = $this->pageService->getPageById((int)$selectedPageId);
            if ($page) {
                $selectedPageTitle = $page->getTitle();
            }
        }

        $data->assign('selected_page_id', $selectedPageId);
        $data->assign('selected_page_title', $selectedPageTitle);
        $data->assign('delete_field_name', $this->deleteFieldName);
        $data->assign('exclude_page_id', $this->excludePageId ?? 0);
        $data->assign('allow_remove', $this->allowRemove);
        $data->assign('submit_click_id', $this->submitClickId);

        $data->assign('search_endpoint', '/admin/api/page/search');
        $data->assign('lookup_modal_title', $this->getTextResource($this->modalTitleResourceIdentifier));
        $data->assign('lookup_selected_label', $this->getTextResource($this->selectedLabelResourceIdentifier));
        $data->assign('lookup_edit_button', $this->getTextResource('object_picker_button_title'));
        $data->assign('lookup_search_placeholder', $this->getTextResource('settings_page_lookup_search_placeholder'));
        $data->assign('lookup_start_typing', $this->getTextResource('settings_page_lookup_start_typing'));
        $data->assign('lookup_searching', $this->getTextResource('settings_page_lookup_searching'));
        $data->assign('lookup_no_results', $this->getTextResource('settings_page_lookup_no_results'));
        $data->assign('lookup_remove', $this->getTextResource('settings_page_lookup_remove'));
    }
}
