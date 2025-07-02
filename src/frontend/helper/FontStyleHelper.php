<?php

namespace Obcato\Core\frontend\helper;

class FontStyleHelper {

    public static function createItalic(string $text): string {
        $matches = array();
        preg_match_all('/(_.*?_)/', $text, $matches);

        for ($i = 0; $i < count($matches[0]); $i++) {
            $capture = $matches[0][$i];
            $replacement = $capture;
            $replacement = ltrim($replacement, "_");
            $replacement = rtrim($replacement, "_");
            $text = str_replace($capture, '<em>' . $replacement . '</em>', $text);
        }
        return $text;
    }
    public static function createBold(string $text): string {
        $matches = array();
        preg_match_all('/\*(.*?)\*/', $text, $matches);

        for ($i = 0; $i < count($matches[0]); $i++) {
            $text = str_replace($matches[0][$i], '<strong>' . $matches[1][$i] . '</strong>', $text);
        }
        return $text;
    }
    public static function createColors(string $text): string {
        $matches = array();
        preg_match_all('/\[color\((.*?)\)\](.*?)\[\/color\]/', $text, $matches);

        for ($i = 0; $i < count($matches[0]); $i++) {
            $color = $matches[1][$i];
            $text = str_replace($matches[0][$i], "<span style=\"color: $color\">" . $matches[2][$i] . '</span>', $text);
        }
        return $text;
    }
}