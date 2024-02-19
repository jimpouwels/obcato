<?php
require_once CMS_ROOT . "/authentication/Authenticator.php";

class CurrentUserIndicator extends Obcato\ComponentApi\Visual {

    public function __construct(TemplateEngine $templateEngine,) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "system/current_user_indicator.tpl";
    }

    public function load(): void {
        $this->assign('username', Authenticator::getCurrentUser()->getFullName());
    }
}