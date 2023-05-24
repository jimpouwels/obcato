<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "elements/iframe_element/iframe_element_form.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "elements/element_contains_errors_exception.php";

    class IFrameElementRequestHandler extends HttpRequestHandler {

        private IFrameElement $_iframe_element;
        private IFrameElementForm $_iframe_element_form;
        private ElementDao $_element_dao;

        public function __construct(IFrameElement $iframe_element) {
            $this->_iframe_element = $iframe_element;
            $this->_iframe_element_form = new IFrameElementForm($this->_iframe_element);
            $this->_element_dao = ElementDao::getInstance();
        }

        public function handleGet(): void {
        }

        public function handlePost(): void {
            try {
                $this->_iframe_element_form->loadFields();
                $this->_element_dao->updateElement($this->_iframe_element);
            } catch (FormException $e) {
                throw new ElementContainsErrorsException("IFrame element contains errors");
            }
        }
    }
?>