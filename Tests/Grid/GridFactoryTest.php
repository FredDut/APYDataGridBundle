<?php

namespace APY\DataGridBundle\Tests\Grid;

use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Exception\UnexpectedTypeException;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\GridBuilder;
use APY\DataGridBundle\Grid\GridBuilderInterface;
use APY\DataGridBundle\Grid\GridFactory;
use APY\DataGridBundle\Grid\GridRegistryInterface;
use APY\DataGridBundle\Grid\GridTypeInterface;
use APY\DataGridBundle\Grid\Type\GridType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Doctrine\ORM\EntityManager;

/**
 * Class GridFactoryTest.
 */
class GridFactoryTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $registry;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    private $authChecker;

    private $twig;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $builder;

    private \APY\DataGridBundle\Grid\GridFactory $factory;

    public function testCreateWithUnexpectedType(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->factory->create(1234);
        $this->factory->create(['foo']);
        $this->factory->create(new \stdClass());
    }

    public function testCreateWithTypeString(): void
    {
        $this->registry->expects($this->once())
                       ->method('getType')
                       ->with('foo')
                       ->willReturn($this->createMock(GridTypeInterface::class));

        $this->assertInstanceOf(Grid::class, $this->factory->create('foo'));
    }

    public function testCreateWithTypeObject(): void
    {
        $this->registry->expects($this->never())->method('getType');

        $this->assertInstanceOf(Grid::class, $this->factory->create(new GridType()));
    }

    public function testCreateBuilderWithDefaultType(): void
    {
        $defaultType = new GridType();

        $this->registry->expects($this->once())
                       ->method('getType')
                       ->with('grid')
                       ->willReturn($defaultType);

        $builder = $this->factory->createBuilder();

        $this->assertSame($defaultType, $builder->getType());
    }

    public function testCreateBuilder(): void
    {
        $givenOptions = ['a' => 1, 'b' => 2];
        $resolvedOptions = ['a' => 1, 'b' => 2, 'c' => 3];

        $type = $this->createMock(GridTypeInterface::class);

        $type->expects($this->once())
             ->method('getName')
             ->willReturn('TYPE');

        $type->expects($this->once())
             ->method('configureOptions')
             ->with($this->callback(function ($resolver) use ($resolvedOptions) {
                 if (!$resolver instanceof OptionsResolver) {
                     return false;
                 }

                 $resolver->setDefaults($resolvedOptions);

                 return true;
             }));

        $type->expects($this->once())
             ->method('buildGrid')
             ->with($this->callback(fn ($builder) => $builder instanceof GridBuilder && $builder->getName() == 'TYPE'), $resolvedOptions);

        $builder = $this->factory->createBuilder($type, null, $givenOptions);

        $this->assertInstanceOf(GridBuilderInterface::class, $builder);
        $this->assertSame($type, $builder->getType());
        $this->assertSame('TYPE', $builder->getName());
        $this->assertEquals($resolvedOptions, $builder->getOptions());
        $this->assertNull($builder->getSource());
    }

    public function testCreateColumnWithUnexpectedType(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->factory->createColumn('foo', 1234);
    }

    public function testCreateColumnWithTypeString(): void
    {
        $expectedColumn = new TextColumn();

        $this->registry->expects($this->once())
                       ->method('getColumn')
                       ->with('text')
                       ->willReturn($expectedColumn);

        $column = $this->factory->createColumn('foo', 'text', ['title' => 'bar']);

        $this->assertInstanceOf(TextColumn::class, $column);
        $this->assertEquals('text', $column->getType());
        $this->assertEquals('foo', $column->getId());
        $this->assertEquals('bar', $column->getTitle());
        $this->assertEquals('foo', $column->getField());
        $this->assertTrue($column->isVisibleForSource());
    }

    public function testCreateColumnWithObject(): void
    {
        $column = $this->factory->createColumn('foo', new TextColumn(), ['title' => 'bar']);

        $this->assertInstanceOf(TextColumn::class, $column);
        $this->assertEquals('text', $column->getType());
        $this->assertEquals('foo', $column->getId());
        $this->assertEmpty($column->getTitle());
        $this->assertNull($column->getField());
        $this->assertFalse($column->isVisibleForSource());
    }

    protected function setUp(): void
    {
        $self = $this;

        $this->container = $this->createMock(Container::class);
        $this->authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->router = $this->createMock(RouterInterface::class);
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
                        return $this->authChecker;
                        break;
                }
            }));

        $this->registry = $this->createMock(GridRegistryInterface::class);
        $this->builder = $this->createMock(GridBuilderInterface::class);
        $this->factory = new GridFactory($this->container, $this->doctrine, $request_stack, $this->authChecker, $this->router, $this->twig, $this->registry);
    }
}
