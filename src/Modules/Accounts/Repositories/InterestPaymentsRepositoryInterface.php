<?php

namespace Account\Modules\Accounts\Repositories;

use Account\Modules\Accounts\Entities\TransactionInterface;

interface InterestPaymentsRepositoryInterface
{
    public function getLastInterestDeposit(): TransactionInterface;
    public function create(string $accountId, string $interestAmount, \DateTime $dateTime): TransactionInterface;
    /**
     * @return TransactionInterface[]
     */
    public function all(int $accountId): array;
}