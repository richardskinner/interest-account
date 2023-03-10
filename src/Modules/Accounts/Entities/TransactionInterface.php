<?php

namespace Account\Modules\Accounts\Entities;

use DateTimeInterface;

interface TransactionInterface
{
    public function getInterest(): int;
    public function getTransactionDate(): DateTimeInterface;
}