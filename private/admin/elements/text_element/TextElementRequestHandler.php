<?php
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/elements/text_element/TextElementForm.php";

class TextElementRequestHandler extends HttpRequestHandler {

    private TextElement $_text_element;
    private ElementDao $_element_dao;
    private TextElementForm $_text_element_form;

    public function __construct(TextElement $text_element) {
        $this->_text_element = $text_element;
        $this->_element_dao = ElementDaoMysql::getInstance();
        $this->_text_element_form = new TextElementForm($this->_text_element);
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        $this->_text_element_form->loadFields();
        $this->_element_dao->updateElement($this->_text_element);
    }
}

?>