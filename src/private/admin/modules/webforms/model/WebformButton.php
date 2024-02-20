<?php

namespace Obcato\Core;

class WebFormButton extends WebFormItem {

    public static string $TYPE = "button";
    private static int $SCOPE = 17;

    public function __construct() {
        parent::__construct(self::$SCOPE);
    }

    public static function constructFromRecord(array $row): WebFormButton {
        $field = new WebFormButton();
        $field->initFromDb($row);
        return $field;
    }

    public function getType(): string {
        return self::$TYPE;
    }

}