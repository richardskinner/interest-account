<?php

namespace Account\Tests\Fakes;

use Account\Modules\Accounts\Entities\AccountInterface;
use Account\Modules\Accounts\Enums\InterestRate;
use Account\Modules\Accounts\Repositories\AccountRepositoryInterface;
use Exception;
use Money\Money;
use Ramsey\Uuid\Uuid;

class FakeAccountRepository implements AccountRepositoryInterface
{
    private array $accounts = [];
    public function __construct()
    {
        $this->accounts[1] = new FakeAccount(
            1,
            Uuid::uuid4()->toString(),
            Money::GBP(100000000)->getAmount(),
            InterestRate::NOT_PROVIDED->value
        );
        $this->accounts[2] = new FakeAccount(
            2,
            Uuid::uuid4()->toString(),
            Money::GBP(100000000)->getAmount(),
            InterestRate::UNDER_5000->value
        );
        $this->accounts[3] = new FakeAccount(
            3,
            Uuid::uuid4()->toString(),
            Money::GBP(100)->getAmount(),
            InterestRate::UNDER_5000->value
        );
    }

    public function get(string $accountId): AccountInterface
    {
        return $this->accounts[$accountId];
    }

    public function exists(string $userId): bool
    {
        /** @var FakeAccount $account */
        $account = array_values(array_filter($this->accounts, fn($account) => ($account->getCustomerId() === $userId)));

        return !empty($account[0]);
    }

    public function create(string $customerId, Money $balance, InterestRate $interestRate): AccountInterface
    {
        if ($customerId === '0') {
            throw new Exception('Failed to insert.');
        }

        $id = count($this->accounts) + 1;
        $account = new FakeAccount($id, Uuid::uuid4(), $balance->getAmount(), $interestRate->value);
        $this->accounts[$id] = $account;

        return $account;
    }

    public function update(AccountInterface $account): AccountInterface
    {
        return $account;
    }

    public function all(): array
    {
        return $this->accounts;
    }
}