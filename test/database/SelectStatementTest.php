<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../setup.php";
require_once CMS_ROOT . '/database/SelectStatement.php';

class SelectStatementTest extends TestCase {

    public function testCreateSelectStatementWithWhereClause() {
        $statement = new SelectStatement();
        $statement->addFrom("table1", ["col1", "col2"]);
        $statement->addFrom("table2", ["col1", "col2"]);
        $statement->addWhereByValue("table1", "col1", WhereType::Equals, "someString1");
        $statement->addWhereByValue("table2", "col2", WhereType::Like, "someString2");
        $this->assertEquals("SELECT table1.col1, table1.col2, table2.col1, table2.col2 FROM table1 table1, table2 table2 WHERE table1.col1 = ? AND table2.col2 LIKE ?", $statement->toQuery());
        $this->assertEquals("ss", $statement->getBindString());
        $this->assertEquals(["someString1", "%someString2%"], $statement->getMatches());
    }

    public function testCreateDistinctSelectStatementWithWhereClause() {
        $statement = new SelectStatement(true);
        $statement->addFrom("table1", ["col1", "col2"]);
        $statement->addFrom("table2", ["col1", "col2"]);
        $statement->addWhereByValue("table1", "col1", WhereType::Equals, "someString1");
        $statement->addWhereByValue("table2", "col2", WhereType::Like, "someString2");
        $this->assertEquals("SELECT DISTINCT table1.col1, table1.col2, table2.col1, table2.col2 FROM table1 table1, table2 table2 WHERE table1.col1 = ? AND table2.col2 LIKE ?", $statement->toQuery());
    }

    public function testCreateSelectStatementWithWhereClauseMatchingColumns() {
        $statement = new SelectStatement();
        $statement->addFrom("table1", ["col1", "col2"]);
        $statement->addFrom("table2", ["col1", "col2"]);
        $statement->addWhereByValue("table1", "col1", WhereType::Equals, "someString1");
        $statement->addWhereByRef("table1", "col2", "table2", "col2");
        $this->assertEquals("SELECT table1.col1, table1.col2, table2.col1, table2.col2 FROM table1 table1, table2 table2 WHERE table1.col1 = ? AND table1.col2 = table2.col2", $statement->toQuery());
    }

    public function testCreateSelectStatementWithOrderBy() {
        $statement = new SelectStatement();
        $statement->addFrom("table1", ["col1", "col2"]);
        $statement->addFrom("table2", ["col1", "col2"]);
        $statement->addWhereByValue("table1", "col1", WhereType::Equals, "someString1");
        $statement->orderBy("table2", "col2");
        $this->assertEquals("SELECT table1.col1, table1.col2, table2.col1, table2.col2 FROM table1 table1, table2 table2 WHERE table1.col1 = ? ORDER BY table2.col2", $statement->toQuery());
    }

}