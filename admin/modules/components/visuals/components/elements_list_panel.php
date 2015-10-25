<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/element_dao.php';

    class ElementsListPanel extends Panel {

        private static $TEMPLATE = 'components/elements_list.tpl';
        private $_element_dao;
        private $_template_engine;
        private $_components_request_handler;

        public function __construct($components_request_handler) {
            parent::__construct('Elementen', 'component-list-fieldset');
            $this->_components_request_handler = $components_request_handler;
            $this->_element_dao = ElementDao::getInstance();
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign('elements', $this->getElementsData());
            return $this->_template_engine->fetch('modules/components/' . self::$TEMPLATE);
        }

        private function getElementsData() {
            $elements_data = array();
            foreach ($this->_element_dao->getElementTypes() as $element_type) {
                $element_data = array();
                $element_data['id'] = $element_type->getId();
                $element_data['name'] = $element_type->getName();
                $element_data['icon_url'] = '/admin/static.php?file=/elements/' . $element_type->getIdentifier() . $element_type->getIconUrl();
                $element_data['is_current'] = $this->isCurrentElement($element_type);
                $elements_data[] = $element_data;
            }
            return $elements_data;
        }

        private function isCurrentElement($element) {
            $current_element = $this->_components_request_handler->getCurrentElement();
            return $current_element && $current_element->getId() == $element->getId();
        }
    }
