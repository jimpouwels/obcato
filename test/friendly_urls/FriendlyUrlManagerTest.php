<?php

use PHPUnit\Framework\TestCase;

define("_ACCESS", "GRANTED");

require_once(CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php');
require_once(__DIR__ . '/../__mock/FriendlyUrlDaoMock.php');
require_once(__DIR__ . '/../__mock/SettingsDaoMock.php');
require_once(__DIR__ . '/../__mock/PageDaoMock.php');
require_once(__DIR__ . '/../__mock/ArticleDaoMock.php');

class FriendlyUrlManagerTest extends TestCase {

    private FriendlyUrlDao $_friendlyUrlDao;
    private PageDao $_page_dao;

    protected function setUp(): void {
        $this->_friendlyUrlDao = new FriendlyUrlDaoMock();
        $this->_page_dao = new PageDaoMock();
    }

    protected function tearDown(): void {
        unlink(__DIR__ . '/../__mock/.htaccess');
    }

    public function testMatchUrl_noMatchingPage() {
        $this->givenPageWithUrl(1, "/page1");
        $friendlyUrlManager = FriendlyUrlManager::getTestInstance($this->_friendlyUrlDao, new SettingsDaoMock(), $this->_page_dao, new ArticleDaoMock());
        $match = $friendlyUrlManager->matchUrl('/page2');
        $this->assertNull($match);
    }

    public function testMatchUrl_matchingPage() {
        $this->givenPageWithUrl(1, "/page1");
        $friendlyUrlManager = FriendlyUrlManager::getTestInstance($this->_friendlyUrlDao, new SettingsDaoMock(), $this->_page_dao, new ArticleDaoMock());
        $match = $friendlyUrlManager->matchUrl('/page1');
        $this->assertEquals(1, $match->getPage()->getId());
        $this->assertEquals('/page1', $match->getPageUrl());
    }

    public function testMatchUrl_trimsSlashesAtEnd() {
        $this->givenPageWithUrl(1, "/page1////");
        $friendlyUrlManager = FriendlyUrlManager::getTestInstance($this->_friendlyUrlDao, new SettingsDaoMock(), $this->_page_dao, new ArticleDaoMock());
        $match = $friendlyUrlManager->matchUrl('/page1');
        $this->assertEquals(1, $match->getPage()->getId());
        $this->assertEquals('/page1', $match->getPageUrl());
    }

    private function givenPageWithUrl(int $id, string $url): void {
        $page = new Page();
        $page->setId($id);
        $this->_friendlyUrlDao->addPage($page, $url);
        $this->_page_dao->addPage($page);
    }
}