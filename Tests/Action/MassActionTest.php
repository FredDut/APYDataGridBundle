<?php

namespace APY\DataGridBundle\Tests\Action;

use APY\DataGridBundle\Grid\Action\MassAction;
use PHPUnit\Framework\TestCase;

class MassActionTest extends TestCase
{
    private \APY\DataGridBundle\Grid\Action\MassAction $massAction;

    private string $title = 'foo';

    private string $callback = 'static::massAction';

    private bool $confirm = true;

    private array $parameters = ['foo' => 'foo', 'bar' => 'bar'];

    private string $role = 'ROLE_FOO';

    public function testMassActionConstruct(): void
    {
        $this->assertEquals($this->title, $this->massAction->getTitle());
        $this->assertEquals($this->callback, $this->massAction->getCallback());
        $this->assertEquals($this->confirm, $this->massAction->getConfirm());
        $this->assertEquals($this->parameters, $this->massAction->getParameters());
        $this->assertEquals($this->role, $this->massAction->getRole());
    }

    public function testSetTile(): void
    {
        $title = 'bar';
        $this->massAction->setTitle($title);

        $this->assertEquals($title, $this->massAction->getTitle());
    }

    public function testGetTitle(): void
    {
        $title = 'foobar';
        $this->massAction->setTitle($title);

        $this->assertEquals($title, $this->massAction->getTitle());
    }

    public function testSetCallback(): void
    {
        $callback = 'self::fooMassAction';
        $this->massAction->setCallback($callback);

        $this->assertEquals($callback, $this->massAction->getCallback());
    }

    public function testGetCallback(): void
    {
        $callback = 'self::barMassAction';
        $this->massAction->setCallback($callback);

        $this->assertEquals($callback, $this->massAction->getCallback());
    }

    public function testSetConfirm(): void
    {
        $confirm = false;
        $this->massAction->setConfirm($confirm);

        $this->assertEquals($confirm, $this->massAction->getConfirm());
    }

    public function testGetConfirm(): void
    {
        $confirm = false;
        $this->massAction->setConfirm($confirm);

        $this->assertFalse($this->massAction->getConfirm());
    }

    public function testDefaultConfirmMessage(): void
    {
        $this->assertIsString($this->massAction->getConfirmMessage());
    }

    public function testSetConfirmMessage(): void
    {
        $message = 'A foo test message';
        $this->massAction->setConfirmMessage($message);

        $this->assertEquals($message, $this->massAction->getConfirmMessage());
    }

    public function testGetConfirmMessage(): void
    {
        $message = 'A bar test message';
        $this->massAction->setConfirmMessage($message);

        $this->assertEquals($message, $this->massAction->getConfirmMessage());
    }

    public function testSetParameters(): void
    {
        $params = [1 => 1, 2 => 2];
        $this->massAction->setParameters($params);

        $this->assertEquals($params, $this->massAction->getParameters());
    }

    public function testGetParameters(): void
    {
        $params = [1, 2, 3];
        $this->massAction->setParameters($params);

        $this->assertEquals($params, $this->massAction->getParameters());
    }

    public function testSetRole(): void
    {
        $role = 'ROLE_ADMIN';
        $this->massAction->setRole($role);

        $this->assertEquals($role, $this->massAction->getRole());
    }

    public function testGetRole(): void
    {
        $role = 'ROLE_SUPER_ADMIN';
        $this->massAction->setRole($role);

        $this->assertEquals($role, $this->massAction->getRole());
    }

    public function setUp(): void
    {
        $this->massAction = new MassAction($this->title, $this->callback, $this->confirm, $this->parameters, $this->role);
    }
}
