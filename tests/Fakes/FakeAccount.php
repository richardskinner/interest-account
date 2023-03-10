<?php

namespace Account\Tests\Fakes;

use Account\Modules\Accounts\Entities\AccountInterface;

class FakeAccount implements AccountInterface
{
    private string $id;
    private string $customerId;
    private string $balance;
    private string $interestRate;

    public function __construct(
        string $id,
        ?string $customerId = null,
        ?string $balance = null,
        ?string $interestRate =  null
    )
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->balance = $balance;
        $this->interestRate = $interestRate;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function setId(string $id): AccountInterface
    {
        $this->id = $id;
        return $this;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function setCustomerId(string $id): AccountInterface
    {
        $this->customerId = $id;
        return $this;
    }

    public function getBalance(): string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): AccountInterface
    {
        $this->balance = $balance;
        return $this;
    }

    public function getInterestRate(): string
    {
        return $this->interestRate;
    }

    public function setInterestRate(string $interestRate): AccountInterface
    {
        $this->interestRate = $interestRate;
        return $this;
    }
}