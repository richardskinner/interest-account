<?php

namespace Account\Modules\Accounts\Entities;

interface AccountInterface
{
    public function getId(): string;
    public function getCustomerId(): string;
    public function setCustomerId(string $id): AccountInterface;
    public function getBalance(): string;
    public function setBalance(string $balance): AccountInterface;
    public function getInterestRate(): string;
    public function setInterestRate(string $interestRate): AccountInterface;
}