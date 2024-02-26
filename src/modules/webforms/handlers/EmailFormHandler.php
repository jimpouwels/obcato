<?php

namespace Obcato\Core\modules\webforms\handlers;

use Obcato\Core\database\dao\SettingsDao;
use Obcato\Core\database\dao\SettingsDaoMysql;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;

class EmailFormHandler extends FormHandler {

    public static string $TYPE = 'email_form_handler';
    private SettingsDao $_settings_dao;

    public function __construct() {
        parent::__construct();
        $this->_settings_dao = SettingsDaoMysql::getInstance();
    }

    public function getRequiredProperties(): array {
        return array(
            new HandlerProperty('target_email_address', 'textfield'),
            new HandlerProperty('subject', 'textfield'),
            new HandlerProperty('template', 'textarea')
        );
    }

    public function getNameResourceIdentifier(): string {
        return 'webforms_email_form_handler_name';
    }

    public function getType(): string {
        return self::$TYPE;
    }

    public function handle(array $fields, Page $page, ?Article $article): void {
        $message = $this->getFilledInPropertyValue('template');
        $subject = $this->getFilledInPropertyValue('subject');
        $target_email_address = $this->_settings_dao->getSettings()->getEmailAddress();
        if (!$target_email_address) {
            $target_email_address = $this->getProperty('target_email_address');
        }
        $headers = array(
            'From' => $target_email_address
        );
        mail($target_email_address, $subject, $message, $headers);
    }
}