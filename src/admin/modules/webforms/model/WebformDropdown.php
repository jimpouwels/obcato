<?php

namespace Obcato\Core;

class WebFormDropDown extends WebFormField {

    public static string $TYPE = "dropdown";
    private static int $SCOPE = 15;
    private array $_options = array();

    public function __construct() {
        parent::__construct(self::$SCOPE);
    }

    public static function constructFromRecord(array $row): WebFormDropDown {
        $field = new WebFormDropDown();
        $field->initFromDb($row);
        return $field;
    }

    public function getOptions(): array {
        return $this->_options;
    }

    public function setOptions(array $options): void {
        $this->_options = $options;
    }

    public function addOption(WebFormDropDownOption $option) {
        $this->_options[] = $option;
    }

    public function getType(): string {
        return self::$TYPE;
    }

}