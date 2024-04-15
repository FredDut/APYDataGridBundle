<?php

namespace APY\DataGridBundle\Tests\Grid\Action;

use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use PHPUnit\Framework\TestCase;

class DeleteMassActionTest extends TestCase
{
    public function testConstructWithConfirmation(): void
    {
        $ma = new DeleteMassAction(true);
        $this->assertEquals(true, $ma->getConfirm());
    }

    public function testConstructWithoutConfirmation(): void
    {
        $ma = new DeleteMassAction();
        $this->assertEquals(false, $ma->getConfirm());
    }
}
