<?php

use PHPUnit\Framework\TestCase;

define("_ACCESS", "GRANTED");

require_once(CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php');
require_once(__DIR__ . '/../__mock/FriendlyUrlDaoMock.php');
require_once(__DIR__ . '/../__mock/SettingsDaoMock.php');
require_once(__DIR__ . '/../__mock/PageDaoMock.php');
require_once(__DIR__ . '/../__mock/ArticleDaoMock.php');

class FriendlyUrlManagerTest extends TestCase {

    protected function tearDown(): void {
        unlink(__DIR__ . '/../__mock/.htaccess');
    }

    public function testMatchUrl() {
        $friendlyUrlManager = FriendlyUrlManager::getTestInstance(new FriendlyUrlDaoMock(), new SettingsDaoMock(), new PageDaoMock(), new ArticleDaoMock());
        $friendlyUrlManager->matchUrl('/test');
        $this->assertTrue(true);
    }
}