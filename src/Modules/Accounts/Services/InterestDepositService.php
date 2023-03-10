<?php

namespace Account\Modules\Accounts\Services;

use Account\Modules\Accounts\Entities\AccountInterface;
use Account\Modules\Accounts\Entities\TransactionInterface;
use Account\Modules\Accounts\Enums\InterestPayout;
use Carbon\Carbon;
use DateTimeInterface;
use Money\Money;

class InterestDepositService
{
    public static function calculateInterest(AccountInterface $account): int
    {
        return $account->getInterestRate() * $account->getBalance();
    }

    /**
     * @param AccountInterface $account
     * @param TransactionInterface[] $paymentsAwaiting
     * @return Money
     */
    public static function enumerateInterestPayment(AccountInterface $account, array $paymentsAwaiting = []): Money
    {
        $addPayments = [];

        foreach ($paymentsAwaiting as $payment) {
            $addPayments[] = Money::GBP($payment->getInterest());
        }

        return Money::GBP(self::calculateInterest($account))->add(...$addPayments);
    }

    public static function skipInterestDeposit(int $interestAmount): bool
    {
        if (round($interestAmount) < 1) {
            return true;
        }

        return false;
    }

    public static function payInterest(DateTimeInterface $dateTime): bool
    {
        $diff = $dateTime->diff(Carbon::now());

        if ($diff->days < InterestPayout::EVERY_X_DAYS->value) {
            return false;
        }

        return true;
    }
}