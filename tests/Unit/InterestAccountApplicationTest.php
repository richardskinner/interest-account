<?php

namespace Account\Tests\Unit;

use Account\InterestAccountApplication;
use Account\Modules\Accounts\Entities\AccountInterface;
use Account\Modules\Accounts\Exceptions\AccountExistsException;
use Account\Modules\Accounts\Exceptions\AccountNotCreatedException;
use Account\Modules\Accounts\Repositories\AccountRepositoryInterface;
use Account\Modules\Accounts\Repositories\InterestPaymentsRepositoryInterface;
use Account\Tests\Fakes\FakeAccountRepository;
use Account\Tests\Fakes\FakeCustomer;
use Account\Tests\Fakes\FakeInterestPaymentRepository;
use Carbon\Carbon;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InterestAccountApplicationTest extends TestCase
{
    public function testOpenAccountSuccessfully()
    {
        $accountApp = $this->initApp();
        $account = $accountApp->open(new FakeCustomer(Uuid::uuid4()), Money::GBP(100000));

        $this->assertInstanceOf(AccountInterface::class, $account);
    }

    public function testOpenAccountThrowsExceptionWhenCustomerHasAccount()
    {
        $this->expectException(AccountExistsException::class);

        $accountRepository = $this->getAccountRepository();
        $accountApp = $this->initApp($accountRepository);
        $accountApp->open(new FakeCustomer($accountRepository->get(1)->getCustomerId()), Money::GBP(1000));
    }

    public function testOpenAccountThrowsExceptionWhenAccountNotCreated()
    {
        $this->expectException(AccountNotCreatedException::class);

        $accountApp = $this->initApp();
        $accountApp->open(new FakeCustomer(0), Money::GBP(1000));
    }

    public function testShowListCorrectly()
    {
        $accountRepository = $this->getAccountRepository();
        $acounts = $accountRepository->all();

        $this->assertIsArray($acounts);
        foreach ($acounts as $account) {
            $this->assertInstanceOf(AccountInterface::class, $account);
            $this->assertIsString($account->getId());
            $this->assertIsString($account->getCustomerId());
            $this->assertIsString($account->getBalance());
            $this->assertIsString($account->getInterestRate());
        }
    }

    public function testDepositFundsSuccessfully()
    {
        $accountRepository = $this->getAccountRepository();
        $accountApp = $this->initApp($accountRepository);

        $account = $accountApp->deposit($accountRepository->get(1), Money::GBP(100000000));

        $this->assertEquals('200000000', $account->getBalance());
    }

    public function testCalculateInterest()
    {
        Carbon::setTestNow('2023-03-01');
        $accountRepository = $this->getAccountRepository();
        $accountApp = $this->initApp($accountRepository);
        $account = $accountApp->payInterest($accountRepository->get(3));

        $this->assertEquals(195, $account->getBalance());
    }

    private function initApp(
        ?AccountRepositoryInterface $accountRepository = null,
        ?InterestPaymentsRepositoryInterface $interestPaymentRepository = null
    ): InterestAccountApplication
    {
        if (!$accountRepository) {
            $accountRepository = $this->getAccountRepository();
        }

        if (!$interestPaymentRepository) {
            $interestPaymentRepository = $this->getInterestPaymentRepository();
        }

        return new InterestAccountApplication($accountRepository, $interestPaymentRepository);
    }

    private function getInterestPaymentRepository(): InterestPaymentsRepositoryInterface
    {
        return new FakeInterestPaymentRepository();
    }

    private function getAccountRepository(): AccountRepositoryInterface
    {
        return new FakeAccountRepository();
    }
}