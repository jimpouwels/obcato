<?php

namespace Obcato\Core\modules\settings\model;

enum IFrameSecurityPolicy: int {
    case SAMEORIGIN = 1;
    case ALLOW = 2;
    case DENY = 3;

    public static function toString(IFrameSecurityPolicy $iFrameSecurityPolicy): ?string {
        return match ($iFrameSecurityPolicy) {
            IFrameSecurityPolicy::SAMEORIGIN => "SAMEORIGIN",
            IFrameSecurityPolicy::DENY => "DENY",
        };
    }
}