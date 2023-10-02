<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/authentication/Session.php';
require_once CMS_ROOT . '/view/views/visual.php';

class FormError extends Visual {

    private string $_message;

    public function __construct(string $message) {
        parent::__construct();
        $this->_message = $message;
    }

    public function getTemplateFilename(): string {
        return "system/form_error.tpl";
    }

    public function load(): void {
        $this->assign("error", $this->_message);
    }

}