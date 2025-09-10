<?php

namespace Obcato\Core\view\views;

use Obcato\Core\authentication\Authenticator;

class CurrentUserIndicator extends Visual {
    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "current_user_indicator.tpl";
    }

    public function load(): void {
        $this->assign('username', Authenticator::getCurrentUser()->getFullName());
    }
}