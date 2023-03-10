<?php

namespace Account\Tests\Fakes;

use Account\Modules\Accounts\Entities\TransactionInterface;
use DateTimeInterface;

class FakeTransaction implements TransactionInterface
{
    private ?string $id;
    private int $interest;
    private DateTimeInterface $dateTime;

    public function __construct(
        ?string $id,
        int $interest,
        DateTimeInterface $dateTime,
    )
    {
        $this->id = $id;
        $this->interest = $interest;
        $this->dateTime = $dateTime;
    }

    public function getInterest(): int
    {
        return $this->interest;
    }

    public function getTransactionDate(): DateTimeInterface
    {
        return $this->dateTime;
    }
}