<?php

namespace Account\Tests\Unit;

use Account\Modules\Accounts\Enums\InterestRate;
use Account\Modules\Accounts\Services\InterestRateService;
use Money\Money;
use PHPUnit\Framework\TestCase;

class InterestRateServiceTest extends TestCase
{
    /**
     * @dataProvider getIncome
     */
    public function testGetInterestRateReturnsCorrectEnum(?Money $income, string $expectedInterestRate): void
    {
        $service = new InterestRateService();
        $interestRate  = $service->getInterestRate($income);

        $this->assertInstanceOf(InterestRate::class, $interestRate);
        $this->assertEquals($expectedInterestRate, $interestRate->value);
    }

    public function getIncome()
    {
        return [
            [Money::GBP(100000000), InterestRate::UNDER_5000->value],
            [Money::GBP(600000000), InterestRate::OVER_5000->value],
            [null, InterestRate::NOT_PROVIDED->value],
        ];
    }
}