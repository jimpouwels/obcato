<?php

namespace Obcato\Core\admin\view\views;

use Obcato\Core\admin\core\model\Notifications;

class NotificationBar extends Visual {

    public function getTemplateFilename(): string {
        return "system/notification_bar.tpl";
    }

    public function load(): void {
        $this->assign("message", Notifications::getMessage());
        $this->assign("success", Notifications::getSuccess());
        Notifications::clearMessage();
    }

}