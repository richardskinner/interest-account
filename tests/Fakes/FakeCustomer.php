<?php

namespace Account\Tests\Fakes;

use Account\Modules\Customers\Entities\CustomerInterface;
use Ramsey\Uuid\Uuid;

class FakeCustomer implements CustomerInterface
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}