<?php

namespace Obcato\Core;

class WebFormTextArea extends WebFormField {

    public static string $TYPE = "textarea";
    private static int $SCOPE = 14;

    public function __construct() {
        parent::__construct(self::$SCOPE);
    }

    public static function constructFromRecord(array $row): WebFormTextArea {
        $field = new WebFormTextArea();
        $field->initFromDb($row);
        return $field;
    }

    public function getType(): string {
        return self::$TYPE;
    }

}