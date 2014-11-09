<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "elements/image_element/image_element_form.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";

    class ImageElementRequestHandler extends HttpRequestHandler {

        private $_image_element;
        private $_image_element_form;
        private $_element_dao;

        public function __construct($image_element) {
            $this->_image_element = $image_element;
            $this->_image_element_form = new ImageElementForm($this->_image_element);
            $this->_element_dao = ElementDao::getInstance();
        }

        public function handleGet() {

        }

        public function handlePost() {
            $this->_image_element_form->loadFields();
            $this->_element_dao->updateElement($this->_image_element);
        }
    }
?>