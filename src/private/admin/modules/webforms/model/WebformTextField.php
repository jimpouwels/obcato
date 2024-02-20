<?php

namespace Obcato\Core;

class WebformTextField extends WebFormField {

    public static string $TYPE = "textfield";
    private static int $SCOPE = 13;

    public function __construct() {
        parent::__construct(self::$SCOPE);
    }

    public static function constructFromRecord(array $row): WebformTextField {
        $field = new WebformTextField();
        $field->initFromDb($row);
        return $field;
    }

    public function getType(): string {
        return self::$TYPE;
    }

}