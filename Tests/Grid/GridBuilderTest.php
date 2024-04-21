<?php

namespace APY\DataGridBundle\Tests\Grid;

use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Exception\InvalidArgumentException;
use APY\DataGridBundle\Grid\Exception\UnexpectedTypeException;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\GridBuilder;
use APY\DataGridBundle\Grid\GridBuilderInterface;
use APY\DataGridBundle\Grid\GridFactory;
use APY\DataGridBundle\Grid\GridFactoryInterface;
use APY\DataGridBundle\Grid\GridRegistryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment ;
use Doctrine\ORM\EntityManager;


/**
 * Class GridBuilderTest.
 */
class GridBuilderTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    private $registry;

    private $doctrine;
    private $request_stack;

    private $twig;

    private $router;

    private $authChecker;

    private \APY\DataGridBundle\Grid\GridBuilder $builder;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        //self::bootKernel();

        // returns the real and unchanged service container
        //$container = self::$kernel->getContainer();
        //$container = self::$container;
        //$this->container = $container;
        $self = $this;
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->authChecker = $authChecker;
        $this->router = $this->createMock(RouterInterface::class);
        $this->container = $this->createMock(Container::class);
        $this->doctrine = $this->createMock(Entitymanager::class);
        $this->request_stack = $this->createMock(RequestStack::class);
        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function ($param) use ($self) {
                switch ($param) {
                    case 'router':
                        return $self->createMock(RouterInterface::class);
                        break;
                    case 'request_stack':
                        $request = new Request([], [], ['key' => 'value']);
                        $session = new Session();
                        $request->setSession($session);
                        $requestStack = new RequestStack();
                        $requestStack->push($request);
                        return $requestStack;
                        break;
                    case 'security.authorization_checker':
                        return $authChecker;
                        break;
                }
            }));
        $this->twig = $this->createMock(Environment::class);
        $this->factory = $this->createMock(GridFactoryInterface::class);
        $this->builder = new GridBuilder($this->container, $this->doctrine, $this->request_stack, $this->authChecker, $this->router, $this->twig, $this->factory, 'name');
    }

    public function testAddUnexpectedType(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->builder->add('foo', 123);
        $this->builder->add('foo', ['test']);
    }

    public function testAddColumnTypeString(): void
    {
        $this->assertFalse($this->builder->has('foo'));

        $this->factory->expects($this->once())
                      ->method('createColumn')
                      ->with('foo', 'text', [])
                      ->willReturn($this->createMock(Column::class));

        $this->builder->add('foo', 'text');

        $this->assertTrue($this->builder->has('foo'));
    }

    public function testAddColumnType(): void
    {
        $this->factory->expects($this->never())->method('createColumn');

        $this->assertFalse($this->builder->has('foo'));
        $this->builder->add('foo', $this->createMock(Column::class));
        $this->assertTrue($this->builder->has('foo'));
    }

    public function testAddIsFluent(): void
    {
        $builder = $this->builder->add('name', 'text', ['key' => 'value']);
        $this->assertSame($builder, $this->builder);
    }

    public function testGetUnknown(): void
    {
        $this->expectException(
            InvalidArgumentException::class
        );

        $this->builder->get('foo');
    }

    public function testGetExplicitColumnType(): void
    {
        $expectedColumn = $this->createMock(Column::class);

        $this->factory->expects($this->once())
                      ->method('createColumn')
                      ->with('foo', 'text', [])
                      ->willReturn($expectedColumn);

        $this->builder->add('foo', 'text');

        $column = $this->builder->get('foo');

        $this->assertSame($expectedColumn, $column);
    }

    public function testHasColumnType(): void
    {
        $this->factory->expects($this->once())
                      ->method('createColumn')
                      ->with('foo', 'text', [])
                      ->willReturn($this->createMock(Column::class));

        $this->builder->add('foo', 'text');

        $this->assertTrue($this->builder->has('foo'));
    }

    public function assertHasNotColumnType(): void
    {
        $this->assertFalse($this->builder->has('foo'));
    }

    public function testRemove(): void
    {
        $this->factory->expects($this->once())
                      ->method('createColumn')
                      ->with('foo', 'text', [])
                      ->willReturn($this->createMock(Column::class));

        $this->builder->add('foo', 'text');

        $this->assertTrue($this->builder->has('foo'));
        $this->builder->remove('foo');
        $this->assertFalse($this->builder->has('foo'));
    }

    public function testRemoveIsFluent(): void
    {
        $builder = $this->builder->remove('foo');
        $this->assertSame($builder, $this->builder);
    }

    public function testGetGrid(): void
    {
        $this->assertInstanceOf(Grid::class, $this->builder->getGrid());
    }

    protected function tearDown(): void
    {
        $this->factory = null;
        //$this->builder = null;
    }
}
