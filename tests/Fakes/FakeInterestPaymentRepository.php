<?php

namespace Account\Tests\Fakes;

use Account\Modules\Accounts\Entities\TransactionInterface;
use Account\Modules\Accounts\Repositories\InterestPaymentsRepositoryInterface;
use DateTime;

class FakeInterestPaymentRepository implements InterestPaymentsRepositoryInterface
{
    private array $data = [];
    public function __construct()
    {
        $this->data = [
            new FakeTransaction('1', '1', new DateTime()),
            new FakeTransaction('2', '1', new DateTime('2023-03-04')),
        ];
    }

    public function getLastInterestDeposit(): TransactionInterface
    {
        return end($this->data);
    }

    public function create(string $accountId, string $interestAmount, DateTime $dateTime): TransactionInterface
    {
        $transaction = new FakeTransaction(count($this->data) + 1, $interestAmount, new DateTime());
        $this->data[] = $transaction;

        return $transaction;
    }

    public function all(int $accountId): array
    {
        return $this->data;
    }
}