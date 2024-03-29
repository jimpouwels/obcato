<?php

namespace Obcato\Core\modules\webforms\model;

use Obcato\Core\modules\templates\model\Presentable;

abstract class WebformItem extends Presentable {

    private string $_label = "";
    private string $_name = "";
    private int $_order_nr = 0;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId);
    }

    public function getLabel(): string {
        return $this->_label;
    }

    public function setLabel(string $label): void {
        $this->_label = $label;
    }

    public function getName(): string {
        return $this->_name;
    }

    public function setName(string $name): void {
        $this->_name = $name;
    }

    public function getOrderNr(): int {
        return $this->_order_nr;
    }

    public function setOrderNr(int $order_nr): void {
        $this->_order_nr = $order_nr;
    }

    public abstract function getType(): string;

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setLabel($row['label']);
        $this->setOrderNr($row['order_nr']);
        parent::initFromDb($row);
    }

}