<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\authentication\Authenticator;

class CurrentUserIndicator extends Visual {

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