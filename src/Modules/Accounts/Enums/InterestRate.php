<?php

namespace Account\Modules\Accounts\Enums;

enum InterestRate: string
{
    case NOT_PROVIDED = '0.5';
    case UNDER_5000 = '0.93';
    case OVER_5000 = '1.02';
}
