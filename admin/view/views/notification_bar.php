<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "core/notifications.php";

class NotificationBar extends Visual {


    public function __construct() {
        parent::__construct();
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