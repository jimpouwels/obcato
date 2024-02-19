<?php
require_once CMS_ROOT . "/core/Notifications.php";

class NotificationBar extends Obcato\ComponentApi\Visual {

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