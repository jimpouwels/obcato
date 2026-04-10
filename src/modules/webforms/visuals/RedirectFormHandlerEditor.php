<?php

namespace Obcato\Core\modules\webforms\visuals;

use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\modules\webforms\model\WebformHandlerProperty;
use Obcato\Core\view\views\PageLookup;
use Obcato\Core\view\views\Visual;

class RedirectFormHandlerEditor extends Visual {

    private ?WebformHandlerProperty $_property = null;
    private PageService $pageService;

    public function __construct() {
        parent::__construct();
        $this->pageService = PageInteractor::getInstance();
    }

    public function getTemplateFilename(): string {
        return 'webforms/templates/webforms/redirect_form_handler_editor.tpl';
    }

    public function load(): void {
        $id = $this->_property->getId();

        $selectedValue = $this->_property->getValue();
        $pageLookup = new PageLookup(
            "handler_property_{$id}_field",
            'webforms_redirect_handler_page_picker',
            $selectedValue,
            'webforms_redirect_handler_page_picker',
            'article_editor_select_parent_article_label',
            true,
            null,
            null,
            'update_webform'
        );
        $this->assign('page_lookup', $pageLookup->render());
    }

    public function setCurrentValue(WebformHandlerProperty $property): void {
        $this->_property = $property;
    }

}