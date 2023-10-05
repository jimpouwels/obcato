<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../setup.php";
require_once CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php';
require_once MOCK_DIR . '/FriendlyUrlDaoMock.php';
require_once MOCK_DIR . '/PageDaoMock.php';
require_once MOCK_DIR . '/ArticleDaoMock.php';

class FriendlyUrlManagerTest extends TestCase {

    private FriendlyUrlDao $friendlyUrlDao;
    private PageDao $pageDao;
    private ArticleDao $articleDao;
    private FriendlyUrlManager $friendlyUrlManager;

    protected function setUp(): void {
        $this->friendlyUrlDao = new FriendlyUrlDaoMock();
        $this->pageDao = new PageDaoMock();
        $this->articleDao = new ArticleDaoMock();
        $this->friendlyUrlManager = new FriendlyUrlManager($this->friendlyUrlDao, $this->pageDao, $this->articleDao);
    }

    protected function tearDown(): void {
        unlink(__DIR__ . '/../__mock/.htaccess');
    }

    public function testMatchUrl_noMatchingPage() {
        $this->givenPageWithUrl(1, "/elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl('/elementHolder3');
        $this->assertNull($match);
    }

    public function testMatchUrl_matchingPage() {
        $this->givenPageWithUrl(1, "/elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl("/elementHolder1");
        $this->assertEquals(1, $match->getPage()->getId());
        $this->assertEquals('/elementHolder1', $match->getPageUrl());
        $this->assertNull($match->getArticle());
    }

    public function testMatchUrl_doesNotMatchWhenFirstPartOfUrlDoesNotMatchPage() {
        $this->givenPageWithUrl(1, "/elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl("/someParent/elementHolder1");
        $this->assertNull($match);
    }

    public function testMatchUrl_doesNotMatchWhenPageHasParentButUrlDoesNot() {
        $this->givenPageWithUrl(1, "/parent/elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl('/elementHolder1');
        $this->assertNull($match);
    }

    public function testMatchUrl_doesNotMatchWhenOnlyLastPartOfArticleMatches() {
        $this->givenPageWithUrl(1, "/elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl('/elementHolder2');
        $this->assertNull($match);
    }

    public function testMatchUrl_doesNotMatchWhenOnlyFirstPartOfArticleMatches() {
        $this->givenPageWithUrl(1, "/elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl('/targetPage');
        $this->assertNull($match);
    }

    public function testMatchUrl_doesNotMatchWhenOnlyArticleMatchesButPageDoesNot() {
        $this->givenPageWithUrl(1, "/elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl('/targetPage/elementHolder2');
        $this->assertNull($match);
    }

    public function testMatchUrl_doesNotMatchWhenPageMatchesButArticleDoesNot() {
        $this->givenPageWithUrl(1, "elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl('/elementHolder1/targetPage/someOtherArticle');
        $this->assertNull($match);
    }

    public function testMatchUrl_matchesWhenPageWithMultipleParentsMatches() {
        $this->givenPageWithUrl(1, "/parent1/parent2/elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl('/parent1/parent2/elementHolder1');
        $this->assertEquals(1, $match->getPage()->getId());
        $this->assertEquals("/parent1/parent2/elementHolder1", $match->getPageUrl());
        $this->assertNull($match->getArticle());
    }

    public function testMatchUrl_matchesWhenBothPageAndArticleMatches() {
        $this->givenPageWithUrl(1, "/parent1/parent2/elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl('/parent1/parent2/elementHolder1/targetPage/elementHolder2');
        $this->assertEquals(1, $match->getPage()->getId());
        $this->assertEquals("/parent1/parent2/elementHolder1", $match->getPageUrl());
        $this->assertEquals(2, $match->getArticle()->getId());
        $this->assertEquals("/targetPage/elementHolder2", $match->getArticleUrl());
    }

    public function testMatchUrl_trimsSlashesAtEnd() {
        $this->givenPageWithUrl(1, "/elementHolder1");
        $this->givenArticleWithUrl(2, "/targetPage/elementHolder2");
        $match = $this->friendlyUrlManager->matchUrl("/elementHolder1/");
        $this->assertEquals(1, $match->getPage()->getId());
        $this->assertEquals('/elementHolder1', $match->getPageUrl());
        $this->assertNull($match->getArticle());
    }

    private function givenPageWithUrl(int $id, string $url): void {
        $page = new Page();
        $page->setId($id);
        $this->friendlyUrlDao->addElementHolder($page, $url);
        $this->pageDao->addPage($page);
    }

    private function givenArticleWithUrl(int $id, string $url): void {
        $article = new Article();
        $article->setId($id);
        $this->friendlyUrlDao->addElementHolder($article, $url);
        $this->articleDao->addArticle($article);
    }

}