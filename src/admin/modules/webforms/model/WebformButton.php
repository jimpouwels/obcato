<?php

namespace Obcato\Core\admin\modules\webforms\model;

class WebformButton extends WebFormItem {

    public static string $TYPE = "button";
    private static int $SCOPE = 17;

    public function __construct() {
        parent::__construct(self::$SCOPE);
    }

    public static function constructFromRecord(array $row): WebformButton {
        $field = new WebformButton();
        $field->initFromDb($row);
        return $field;
    }

    public function getType(): string {
        return self::$TYPE;
    }

}