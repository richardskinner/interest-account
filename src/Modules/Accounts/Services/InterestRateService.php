<?php

namespace Account\Modules\Accounts\Services;

use Account\Modules\Accounts\Enums\InterestRate;
use Account\Modules\Accounts\Enums\SalaryBoundary;
use Money\Currency;
use Money\Money;

class InterestRateService
{
    public static function getInterestRate(?Money $income): InterestRate
    {
        $interestRate = InterestRate::NOT_PROVIDED;

        if (empty($income)) {
            return $interestRate;
        }

        if ($income->lessThan(new Money(SalaryBoundary::BOUNDARY->value, new Currency('GBP')))) {
            $interestRate = InterestRate::UNDER_5000;
        }

        if ($income->greaterThanOrEqual(new Money(SalaryBoundary::BOUNDARY->value, new Currency('GBP')))) {
            $interestRate = InterestRate::OVER_5000;
        }

        return $interestRate;
    }
}