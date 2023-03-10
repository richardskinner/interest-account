<?php

namespace Account\Modules\Accounts\Repositories;

use Account\Modules\Accounts\Entities\AccountInterface;
use Account\Modules\Accounts\Enums\InterestRate;
use Money\Money;

interface AccountRepositoryInterface
{
    public function get(string $accountId): AccountInterface;
    public function exists(string $userId): bool;
    public function create(string $customerId, Money $balance, InterestRate $interestRate): AccountInterface;
    public function update(AccountInterface $account): AccountInterface;

    /**
     * @return AccountInterface[]
     */
    public function all(): array;
}