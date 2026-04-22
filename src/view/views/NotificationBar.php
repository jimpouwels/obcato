<?php

namespace Pageflow\Core\view\views;


use Pageflow\Core\core\model\Notifications;

class NotificationBar extends Visual {

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "notification_bar.tpl";
    }

    public function load(): void {
        $this->assign("message", Notifications::getMessage());
        $this->assign("success", Notifications::getSuccess());
        Notifications::clearMessage();
    }

}