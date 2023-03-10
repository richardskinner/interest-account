<?php

namespace Account\Tests\Unit;

use Account\Modules\Accounts\Entities\AccountInterface;
use Account\Modules\Accounts\Enums\InterestRate;
use Account\Modules\Accounts\Services\InterestDepositService;
use Account\Tests\Fakes\FakeAccount;
use Account\Tests\Fakes\FakeTransaction;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class InterestCalculatorServiceTest extends TestCase
{
    /**
     * @dataProvider getTransactionDate
     */
    public function testShouldPayInterestEveryXDays(\DateTime $transactionDate, \DateTime $testDate)
    {
        Carbon::setTestNow($testDate);
        $payInterest = InterestDepositService::payInterest($transactionDate);

        $this->assertTrue($payInterest);
    }

    /**
     * @dataProvider getEnumerationParameters
     */
    public function testEnumerateInterestPayments(string $expectedInterestPayment, AccountInterface $account, array $awaitingPayments = [])
    {
        $interest = InterestDepositService::enumerateInterestPayment($account, $awaitingPayments);
        $this->assertEquals($expectedInterestPayment, $interest->getAmount());
    }

    public function testSkipInterestDepositReturnsBool()
    {
        $this->assertIsBool(InterestDepositService::skipInterestDeposit(1));
    }

    public function testPayInterestReturnsBool()
    {
        $this->assertIsBool(InterestDepositService::payInterest(new \DateTime()));
    }

    public function getTransactionDate()
    {
        return [
            [new \DateTime('2023-03-01'), new \DateTime('2023-03-09')]
        ];
    }

    public function getEnumerationParameters()
    {
        return [
            [
                '1020000',
                new FakeAccount(1, 2, '1000000', InterestRate::OVER_5000->value),
                []
            ],
            [
                '4020000',
                new FakeAccount(1, 2, '1000000', InterestRate::OVER_5000->value),
                [
                    new FakeTransaction('1', '1000000', new \DateTime()),
                    new FakeTransaction('2', '2000000', new \DateTime()),
                ]
            ],
        ];
    }
}