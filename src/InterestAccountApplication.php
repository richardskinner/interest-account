<?php

namespace Account;

use Account\Modules\Accounts\Exceptions\AccountExistsException;
use Account\Modules\Accounts\Exceptions\AccountNotCreatedException;
use Account\Modules\Accounts\Repositories\AccountRepositoryInterface;
use Account\Modules\Accounts\Repositories\InterestPaymentsRepositoryInterface;
use Account\Modules\Accounts\Services\InterestDepositService;
use Account\Modules\Accounts\Services\InterestRateService;
use Account\Modules\Customers\Entities\CustomerInterface;
use Account\Modules\Accounts\Entities\AccountInterface;
use Money\Money;
use Exception;

class InterestAccountApplication
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private InterestPaymentsRepositoryInterface $interestPayments
    )
    {
    }

    public function open(CustomerInterface $customer, Money $income): AccountInterface
    {
        if ($this->accountRepository->exists($customer->getId())) {
            throw new AccountExistsException('Account already exists.');
        }

        try {
            return $this->accountRepository->create(
                $customer->getId(),
                Money::GBP(0),
                InterestRateService::getInterestRate($income)
            );
        } catch (Exception $e) {
            throw new AccountNotCreatedException('Interest account could not be opened.', null, $e);
        }
    }

    public function deposit(AccountInterface $account, Money $depositAmount): AccountInterface
    {
        $depositedValue = $depositAmount->add(Money::GBP($account->getBalance()));
        $account = $account->setBalance($depositedValue->getAmount());

        return $this->accountRepository->update($account);
    }

    public function list(): array
    {
        return $this->accountRepository->all();
    }

    public function payInterest(AccountInterface $account): AccountInterface
    {
        // All retrieve awaiting interest payments
        $interestAmount = InterestDepositService::enumerateInterestPayment(
            $account,
            $this->interestPayments->all($account->getId())
        );

        if (InterestDepositService::skipInterestDeposit($interestAmount->getAmount())) {
            $this->interestPayments->create($account->getId(), $interestAmount->getAmount(), new \DateTime());
            throw new \RuntimeException('Skipped interest deposit');
        }

        $transaction = $this->interestPayments->getLastInterestDeposit();

        if (!InterestDepositService::payInterest($transaction->getTransactionDate())) {
            throw new \RuntimeException('Interest payment not to be paid within the given timeframe.');
        }

        return $this->deposit($account, Money::GBP($interestAmount->getAmount()));
    }
}