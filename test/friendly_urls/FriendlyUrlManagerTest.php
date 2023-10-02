<?php

use PHPUnit\Framework\TestCase;

define("_ACCESS", "GRANTED");
define("CMS_ROOT", __DIR__ . "/../../admin");

require_once(CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php');
require_once(__DIR__ . '/../__mock/FriendlyUrlDaoMock.php');
require_once(__DIR__ . '/../__mock/SettingsDaoMock.php');
require_once(__DIR__ . '/../__mock/PageDaoMock.php');
require_once(__DIR__ . '/../__mock/ArticleDaoMock.php');

class FriendlyUrlManagerTest extends TestCase {

    private FriendlyUrlDao $friendlyUrlDao;
    private PageDao $pageDao;
    private FriendlyUrlManager $friendlyUrlManager;

    protected function setUp(): void {
        $this->friendlyUrlDao = new FriendlyUrlDaoMock();
        $this->pageDao = new PageDaoMock();
        $this->friendlyUrlManager = new FriendlyUrlManager($this->friendlyUrlDao, new SettingsDaoMock(), $this->pageDao, new ArticleDaoMock());
    }

    protected function tearDown(): void {
        unlink(__DIR__ . '/../__mock/.htaccess');
    }

    public function testMatchUrl_noMatchingPage() {
        $this->givenPageWithUrl(1, "/page1");
        $match = $this->friendlyUrlManager->matchUrl('/page2');
        $this->assertNull($match);
    }

    public function testMatchUrl_matchingPage() {
        $this->givenPageWithUrl(1, "/page1");
        $match = $this->friendlyUrlManager->matchUrl("/page1");
        $this->assertEquals(1, $match->getPage()->getId());
        $this->assertEquals('/page1', $match->getPageUrl());
    }

    public function testMatchUrl_trimsSlashesAtEnd() {
        $this->givenPageWithUrl(1, "/page1");
        $match = $this->friendlyUrlManager->matchUrl("/page1/");
        $this->assertEquals(1, $match->getPage()->getId());
        $this->assertEquals('/page1', $match->getPageUrl());
    }

    public function testMatchUrl_doesNotMatchWhenFirstPartOfUrlDoesNotMatchPage() {
        $this->givenPageWithUrl(1, "/page1");
        $match = $this->friendlyUrlManager->matchUrl("/someParent/page1");
        $this->assertNull($match);
    }

    public function testMatchUrl_doesNotMatchWhenPageHasParentButUrlDoesNot() {
        $this->givenPageWithUrl(1, "/test/page1");
        $match = $this->friendlyUrlManager->matchUrl('/page1');
        $this->assertNull($match);
    }

    private function givenPageWithUrl(int $id, string $url): void {
        $page = new Page();
        $page->setId($id);
        $this->friendlyUrlDao->addPage($page, $url);
        $this->pageDao->addPage($page);
    }
}