<?php

use Obcato\Core\database\Prepared;
use Obcato\Core\database\SelectStatement;
use Obcato\Core\database\WhereType;
use PHPUnit\Framework\TestCase;

class SelectStatementTest extends TestCase {

    public function testCreateSelectStatementWithWhereClause() {
        $statement = new SelectStatement();
        $statement->from("table1", ["col1", "col2"]);
        $statement->from("table2", ["col1", "col2"]);
        $statement->where("table1", "col1", WhereType::Equals, "someString1");
        $statement->where("table2", "col2", WhereType::Like, "someString2");
        $this->assertEquals("SELECT table1.col1, table1.col2, table2.col1, table2.col2 FROM table1 table1, table2 table2 WHERE table1.col1 = ? AND table2.col2 LIKE ?", $statement->toQuery());
        $this->assertEquals("ss", $statement->getBindString());
        $this->assertEquals(["someString1", "%someString2%"], $statement->getMatches());
    }

    public function testCreateDistinctSelectStatementWithWhereClause() {
        $statement = new SelectStatement(true);
        $statement->from("table1", ["col1", "col2"]);
        $statement->from("table2", ["col1", "col2"]);
        $statement->where("table1", "col1", WhereType::Equals, "someString1");
        $statement->where("table2", "col2", WhereType::Like, "someString2");
        $this->assertEquals("SELECT DISTINCT table1.col1, table1.col2, table2.col1, table2.col2 FROM table1 table1, table2 table2 WHERE table1.col1 = ? AND table2.col2 LIKE ?", $statement->toQuery());
    }

    public function testCreateSelectStatementWithOrderBy() {
        $statement = new SelectStatement();
        $statement->from("table1", ["col1", "col2"]);
        $statement->from("table2", ["col1", "col2"]);
        $statement->where("table1", "col1", WhereType::Equals, "someString1");
        $statement->orderBy("table2", "col2");
        $this->assertEquals("SELECT table1.col1, table1.col2, table2.col1, table2.col2 FROM table1 table1, table2 table2 WHERE table1.col1 = ? ORDER BY table2.col2", $statement->toQuery());
    }

    public function testCreateSelectStatementWithInnerJoin() {
        $statement = new SelectStatement();
        $statement->from("table1", ["name"]);
        $statement->from("table2", ["otherData"]);
        $statement->innerJoin("table1", "table2_id", "table2", "id");
        $statement->where("table1", "name", WhereType::Equals, "someString1");
        $this->assertEquals("SELECT table1.name, table2.otherData FROM table1 table1 INNER JOIN table2 ON table1.table2_id = table2.id WHERE table1.name = ?", $statement->toQuery());
    }

    public function testCreateSelectStatementWithDateComparison() {
        $statement = new SelectStatement();
        $statement->from("table", ["name", "publication_date"]);
        $statement->where("table", "publication_date", WhereType::LowerThan, "now()", Prepared::No);
        $this->assertEquals("SELECT table.name, table.publication_date FROM table table WHERE table.publication_date <= now()", $statement->toQuery());
        $this->assertEquals("", $statement->getBindString());
    }

    public function testCreateSelectStatementWithIntValueComparison() {
        $statement = new SelectStatement();
        $statement->from("table1", ["col1", "col2"]);
        $statement->where("table1", "col1", WhereType::Equals, 1);
        $statement->where("table1", "col2", WhereType::Equals, "test");
        $this->assertEquals("SELECT table1.col1, table1.col2 FROM table1 table1 WHERE table1.col1 = ? AND table1.col2 = ?", $statement->toQuery());
        $this->assertEquals("is", $statement->getBindString());
        $this->assertEquals([1, "test"], $statement->getMatches());
    }

}