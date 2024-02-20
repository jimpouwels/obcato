<?php

namespace Obcato\Core\admin\modules\webforms\model;

class WebformTextfield extends WebformField {

    public static string $TYPE = "textfield";
    private static int $SCOPE = 13;

    public function __construct() {
        parent::__construct(self::$SCOPE);
    }

    public static function constructFromRecord(array $row): WebformTextfield {
        $field = new WebformTextfield();
        $field->initFromDb($row);
        return $field;
    }

    public function getType(): string {
        return self::$TYPE;
    }

}