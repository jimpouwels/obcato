<?php

namespace Obcato\Core\utilities;

class StringUtility {

    private function __construct() {}

    public static function hashStringValue(string $string_value): string {
        return md5($string_value);
    }

    public static function unescapeXml(string $string_value): string {
        $string_value = str_replace("&lt;", "<", $string_value);
        $string_value = str_replace("&gt;", ">", $string_value);
        $string_value = str_replace("&amp;", "&", $string_value);
        $string_value = str_replace("&quot;", '"', $string_value);
        $string_value = str_replace("&ndash;", "�", $string_value);
        $string_value = str_replace("&mdash;", "�", $string_value);
        $string_value = str_replace("&copy;", "�", $string_value);
        $string_value = str_replace("&iexcl;", "�", $string_value);
        $string_value = str_replace("&iquest;", "�", $string_value);
        $string_value = str_replace("&ldquo;", "�", $string_value);
        return str_replace("&rdquo;", "�", $string_value);
    }

    public static function escapeXml(?string $stringVisual): string {
        if (empty($stringVisual)) {
            return "";
        }
        $stringVisual = str_replace("&", "&amp;", $stringVisual);
        $stringVisual = str_replace("<", "&lt;", $stringVisual);
        $stringVisual = str_replace(">", "&gt;", $stringVisual);
        $stringVisual = str_replace("\"", "&quot;", $stringVisual);
        $stringVisual = str_replace("�", "&ndash;", $stringVisual);
        $stringVisual = str_replace("�", "&mdash;", $stringVisual);
        $stringVisual = str_replace("�", "&copy;", $stringVisual);
        $stringVisual = str_replace("�", "&iexcl;", $stringVisual);
        $stringVisual = str_replace("�", "&iquest;", $stringVisual);
        $stringVisual = str_replace("'", "&#39;", $stringVisual);
        $stringVisual = str_replace("�", "&ldquo;", $stringVisual);
        return str_replace("�", "&rdquo;", $stringVisual);
    }

}
