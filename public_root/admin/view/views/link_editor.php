<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'view/views/information_message.php';
    require_once CMS_ROOT . 'view/views/object_picker.php';
    
    class LinkEditor extends Visual {
    
        private static $TEMPLATE = 'system/link_editor.tpl';
        private $_links;
        private $_template_engine;
    
        public function __construct($links) {
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_links = $links;
        }
    
        public function render() {
            if (count($this->_links) > 0)
                $this->_template_engine->assign('links', $this->getLinksData());
            else
                $this->_template_engine->assign('message', $this->renderNoLinksFoundMessage());
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }

        private function getLinksData() {
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
        
        private function getLinkTargetField($link) {
            $link_target_field = null;
            $link_target = $link->getTargetElementHolder();
            if (is_null($link_target)) {
                $target_text_field = new TextField('link_' . $link->getId() . '_url', '', $link->getTargetAddress(), false, false, null);
                $link_target_field = $target_text_field->render();
            }
            return $link_target_field;
        }

        private function renderTitleField($link) {
            $title_field = new TextField('link_' . $link->getId() . '_title', '', $link->getTitle(), false, false, null);
            return $title_field->render();
        }

        private function getLinkTitle($link) {
            $link_target = $link->getTargetElementHolder();
            $target_title = '';
            if (!is_null($link_target))
                $target_title = $link_target->getTitle();
            return $target_title;
        }

        private function renderCodeField($link) {
            $code_field = new TextField('link_' . $link->getId() . '_code', 'Code', $link->getCode(), false, false, null);
            return $code_field->render();
        }

        private function renderDeleteField($link) {
            $delete_field = new SingleCheckbox('link_' . $link->getId() . '_delete', '', false, false, '');
            return $delete_field->render();
        }

        private function renderBrowserTargetField($link) {
            $target_field = new Pulldown('link_' . $link->getId() . '_target', '', $link->getTarget(), $this->getTargetOptions(), false, 'link_target_selector');
            return $target_field->render();
        }

        private function renderLinkTargetPicker($link) {
            $element_holder_picker = new ObjectPicker('', $link->getTargetElementHolderId(), 'link_element_holder_ref_' . $link->getId(), '', 'update_element_holder');
            return $element_holder_picker->render();
        }

        private function renderNoLinksFoundMessage() {
            $message = $this->getTextResource('link_editor_no_links_found_message');
            $message = new InformationMessage($message);
            return $message->render();
        }

        private function getTargetOptions() {
            $options = array();
            $options[] = array('name' => $this->getTextResource('link_editor_target_same_page'), 'value' => '_self');
            $options[] = array('name' => $this->getTextResource('link_editor_target_new_tab'), 'value' => '_blank');
            $options[] = array('name' => $this->getTextResource('link_editor_target_popup'), 'value' => '[popup]');
            return $options;
        }
    }