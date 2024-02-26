<?php

namespace Obcato\Core\utilities;
class Logs {
    public static array $LOGS = array();

    public static function asString(): string {
        $value = '';
        foreach (self::$LOGS as $log) {
            $value .= "<span class=\"system-logs\">{$log}</span>";
        }
        return $value;
    }

    public static function hasLogs(): bool {
        return !empty(self::$LOGS);
    }
}

function dumpVal(mixed $value): void {
    $trace = debug_backtrace();
    $class = $trace[1]['class'];
    Logs::$LOGS[] = "<strong>{$class}</strong>: $value";
}

function dumpVar(mixed $var): void {
    $trace = debug_backtrace();
    $class = $trace[1]['class'];
    Logs::$LOGS[] = "<strong>{$class}</strong>: " . var_export($var, true);
}