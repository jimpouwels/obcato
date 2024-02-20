<?php

namespace Obcato\Core\admin\modules\webforms\model;

class WebformTextArea extends WebformField {

    public static string $TYPE = "textarea";
    private static int $SCOPE = 14;

    public function __construct() {
        parent::__construct(self::$SCOPE);
    }

    public static function constructFromRecord(array $row): WebformTextArea {
        $field = new WebformTextArea();
        $field->initFromDb($row);
        return $field;
    }

    public function getType(): string {
        return self::$TYPE;
    }

}