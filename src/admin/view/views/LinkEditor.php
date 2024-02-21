<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\core\model\Link;

class LinkEditor extends Panel {

    private array $_links;

    public function __construct(TemplateEngine $templateEngine, array $links) {
        parent::__construct($templateEngine, $this->getTextResource('link_editor_title'), 'element_holder_links');
        $this->_links = $links;
    }

    public function getPanelContentTemplate(): string {
        return 'system/link_editor.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        if (count($this->_links) > 0) {
            $data->assign('links', $this->getLinksData());
        } else {
            $data->assign('message', $this->renderNoLinksFoundMessage());
        }
    }

    private function getLinksData(): array {
        $links_data = array();
        foreach ($this->_links as $link) {
            $link_data = array();
            $link_data['id'] = $link->getId();
            $link_data['code'] = $link->getCode();
            $link_data['title_field'] = $this->renderTitleField($link);
            $link_data['target_field'] = $this->getLinkTargetField($link);
            $link_data['target_title'] = $this->getLinkTitle($link);
            $link_data['code_field'] = $this->renderCodeField($link);
            $link_data['delete_field'] = $this->renderDeleteField($link);
            $link_data['target_screen_field'] = $this->renderBrowserTargetField($link);
            $link_data['element_holder_picker'] = $this->renderLinkTargetPicker($link);
            $links_data[] = $link_data;
        }
        return $links_data;
    }

    private function getLinkTargetField(Link $link): ?string {
        $link_target_field = null;
        $link_target = $link->getTargetElementHolder();
        if (is_null($link_target)) {
            $target_text_field = new TextField($this->getTemplateEngine(), 'link_' . $link->getId() . '_url', '', $link->getTargetAddress(), false, false, '');
            $link_target_field = $target_text_field->render();
        }
        return $link_target_field;
    }

    private function renderTitleField(Link $link): string {
        $title_field = new TextField($this->getTemplateEngine(), 'link_' . $link->getId() . '_title', '', $link->getTitle(), false, false, '');
        return $title_field->render();
    }

    private function renderCodeField(Link $link): string {
        $code_field = new TextField($this->getTemplateEngine(), 'link_' . $link->getId() . '_code', 'Code', $link->getCode(), true, false, '');
        return $code_field->render();
    }

    private function renderDeleteField(Link $link): string {
        $delete_field = new SingleCheckbox($this->getTemplateEngine(), 'link_' . $link->getId() . '_delete', '', false, false, '');
        return $delete_field->render();
    }

    private function renderBrowserTargetField(Link $link): string {
        $target_field = new Pulldown($this->getTemplateEngine(), 'link_' . $link->getId() . '_target', '', $link->getTarget(), $this->getTargetOptions(), false, 'link_target_selector');
        return $target_field->render();
    }

    private function renderLinkTargetPicker(Link $link): string {
        $element_holder_picker = new ObjectPicker($this->getTemplateEngine(), 'link_element_holder_ref_' . $link->getId(), '', $link->getTargetElementHolderId(), 'update_element_holder');
        return $element_holder_picker->render();
    }

    private function getLinkTitle(Link $link): string {
        $link_target = $link->getTargetElementHolder();
        $target_title = "";
        if (!is_null($link_target)) {
            $target_title = $link_target->getTitle();
        }
        return $target_title;
    }

    private function renderNoLinksFoundMessage(): string {
        $message = $this->getTextResource('link_editor_no_links_found_message');
        $message = new InformationMessage($this->getTemplateEngine(), $message);
        return $message->render();
    }

    private function getTargetOptions(): array {
        $options = array();
        $options[] = array('name' => $this->getTextResource('link_editor_target_same_page'), 'value' => '_self');
        $options[] = array('name' => $this->getTextResource('link_editor_target_new_tab'), 'value' => '_blank');
        $options[] = array('name' => $this->getTextResource('link_editor_target_popup'), 'value' => '[popup]');
        return $options;
    }
}
