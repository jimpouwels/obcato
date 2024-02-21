<?php

namespace Obcato\Core\admin\view\views;


use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\core\model\Notifications;

class NotificationBar extends Visual {

    public function __construct(TemplateEngine $templateEngine,) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "system/notification_bar.tpl";
    }

    public function load(): void {
        $this->assign("message", Notifications::getMessage());
        $this->assign("success", Notifications::getSuccess());
        Notifications::clearMessage();
    }

}