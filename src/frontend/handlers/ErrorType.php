<?php

namespace Obcato\Core\frontend\handlers;

enum ErrorType: string {
    case InvalidValue = 'InvalidValue';
    case Mandatory = 'Mandatory';
}