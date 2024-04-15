<?php

namespace APY\DataGridBundle\Tests\Grid;

use APY\DataGridBundle\Grid\Action\RowActionInterface;
use APY\DataGridBundle\Grid\GridConfigBuilder;
use APY\DataGridBundle\Grid\GridTypeInterface;
use APY\DataGridBundle\Grid\Source\Source;
use PHPUnit\Framework\TestCase;

class GridConfigBuilderTest extends TestCase
{
    /** @var string */
    private $name = 'foo';

    private array $options = ['foo' => 'foo', 'bar' => 'bar'];

    private \APY\DataGridBundle\Grid\GridConfigBuilder $gridConfigBuilder;

    public function testGetName(): void
    {
        $this->assertEquals($this->name, $this->gridConfigBuilder->getName());
    }

    public function testSetSource(): void
    {
        $source = $this->createMock(Source::class);
        $this->gridConfigBuilder->setSource($source);

        $this->assertSame($source, $this->gridConfigBuilder->getSource());
    }

    public function testGetSource(): void
    {
        $source = $this->createMock(Source::class);
        $this->gridConfigBuilder->setSource($source);

        $this->assertSame($source, $this->gridConfigBuilder->getSource());
    }

    public function testSetType(): void
    {
        $type = $this->createMock(GridTypeInterface::class);
        $this->gridConfigBuilder->setType($type);

        $this->assertSame($type, $this->gridConfigBuilder->getType());
    }

    public function testGetType(): void
    {
        $type = $this->createMock(GridTypeInterface::class);
        $this->gridConfigBuilder->setType($type);

        $this->assertSame($type, $this->gridConfigBuilder->getType());
    }

    public function testSetRoute(): void
    {
        $route = 'vendor.bundle.foo_route';
        $this->gridConfigBuilder->setRoute($route);

        $this->assertEquals($route, $this->gridConfigBuilder->getRoute());
    }

    public function testGetRoute(): void
    {
        $route = 'vendor.bundle.foo_route';
        $this->gridConfigBuilder->setRoute($route);

        $this->assertEquals($route, $this->gridConfigBuilder->getRoute());
    }

    public function testSetRouteParameters(): void
    {
        $routeParams = ['foo' => 'foo', 'bar' => 'bar'];
        $this->gridConfigBuilder->setRouteParameters($routeParams);

        $this->assertEquals($routeParams, $this->gridConfigBuilder->getRouteParameters());
    }

    public function testGetRouteParameters(): void
    {
        $routeParams = ['foo' => 'foo', 'bar' => 'bar'];
        $this->gridConfigBuilder->setRouteParameters($routeParams);

        $this->assertEquals($routeParams, $this->gridConfigBuilder->getRouteParameters());
    }

    public function testSetPersistence(): void
    {
        $persistence = true;
        $this->gridConfigBuilder->setPersistence($persistence);

        $this->assertEquals($persistence, $this->gridConfigBuilder->getPersistence());
    }

    public function testIsPersited(): void
    {
        $persisted = false;
        $this->gridConfigBuilder->setPersistence($persisted);

        $this->assertFalse($this->gridConfigBuilder->isPersisted());
    }

    public function testSetPage(): void
    {
        $page = 1;
        $this->gridConfigBuilder->setPage($page);

        $this->assertEquals($page, $this->gridConfigBuilder->getPage());
    }

    public function testGetPage(): void
    {
        $page = 5;
        $this->gridConfigBuilder->setPage($page);

        $this->assertEquals($page, $this->gridConfigBuilder->getPage());
    }

    public function testGetOptions(): void
    {
        $this->assertEquals($this->options, $this->gridConfigBuilder->getOptions());
    }

    public function testHasOption(): void
    {
        $this->assertTrue($this->gridConfigBuilder->hasOption('foo'));
        $this->assertFalse($this->gridConfigBuilder->hasOption('foobar'));
    }

    public function testGetOption(): void
    {
        $this->assertEquals('foo', $this->gridConfigBuilder->getOption('foo'));
        $this->assertEquals('default', $this->gridConfigBuilder->getOption('foobar', 'default'));
        $this->assertNull($this->gridConfigBuilder->getOption('foobar'));
    }

    public function testSetMaxPerPage(): void
    {
        $limit = 50;
        $this->gridConfigBuilder->setMaxPerPage($limit);

        $this->assertEquals($limit, $this->gridConfigBuilder->getMaxPerPage());
    }

    public function testGetMaxPerPage(): void
    {
        $limit = 100;
        $this->gridConfigBuilder->setMaxPerPage($limit);

        $this->assertEquals($limit, $this->gridConfigBuilder->getMaxPerPage());
    }

    public function testSetMaxResults(): void
    {
        $maxResults = 50;
        $this->gridConfigBuilder->setMaxResults($maxResults);

        $this->assertEquals($maxResults, $this->gridConfigBuilder->getMaxResults());
    }

    public function testGetMaxResults(): void
    {
        $maxResults = 100;
        $this->gridConfigBuilder->setMaxResults($maxResults);

        $this->assertEquals($maxResults, $this->gridConfigBuilder->getMaxResults());
    }

    public function testSetSortable(): void
    {
        $sortable = true;
        $this->gridConfigBuilder->setSortable($sortable);

        $this->assertEquals(true, $this->gridConfigBuilder->isSortable());
    }

    public function testIsSortable(): void
    {
        $sortable = false;
        $this->gridConfigBuilder->setSortable($sortable);

        $this->assertFalse($this->gridConfigBuilder->isSortable());
    }

    public function testSetFilterable(): void
    {
        $filterable = false;
        $this->gridConfigBuilder->setFilterable($filterable);

        $this->assertEquals($filterable, $this->gridConfigBuilder->isFilterable());
    }

    public function testIsFilterable(): void
    {
        $filterable = true;
        $this->gridConfigBuilder->setFilterable($filterable);

        $this->assertTrue($this->gridConfigBuilder->isFilterable());
    }

    public function testSetOrder(): void
    {
        $order = 'asc';
        $this->gridConfigBuilder->setOrder($order);

        $this->assertEquals($order, $this->gridConfigBuilder->getOrder());
    }

    public function testGetOrder(): void
    {
        $order = 'desc';
        $this->gridConfigBuilder->setOrder($order);

        $this->assertEquals($order, $this->gridConfigBuilder->getOrder());
    }

    public function testSetSortBy(): void
    {
        $sortBy = 'foo';
        $this->gridConfigBuilder->setSortBy($sortBy);

        $this->assertEquals($sortBy, $this->gridConfigBuilder->getSortBy());
    }

    public function testGetSortBy(): void
    {
        $sortBy = 'bar';
        $this->gridConfigBuilder->setSortBy($sortBy);

        $this->assertEquals($sortBy, $this->gridConfigBuilder->getSortBy());
    }

    public function testSetGroupBy(): void
    {
        $groupBy = 'foo';
        $this->gridConfigBuilder->setGroupBy($groupBy);

        $this->assertEquals($groupBy, $this->gridConfigBuilder->getGroupBy());
    }

    public function testGetGroupBy(): void
    {
        $groupBy = ['foo', 'bar'];
        $this->gridConfigBuilder->setGroupBy($groupBy);

        $this->assertEquals($groupBy, $this->gridConfigBuilder->getGroupBy());
    }

    public function testAddAction(): void
    {
        $action1 = $this->createMock(RowActionInterface::class);
        $action1->method('getColumn')->willReturn('foo');

        $action2 = $this->createMock(RowActionInterface::class);
        $action2->method('getColumn')->willReturn('bar');

        $action3 = $this->createMock(RowActionInterface::class);
        $action3->method('getColumn')->willReturn('bar');

        $this->gridConfigBuilder
            ->addAction($action1)
            ->addAction($action2)
            ->addAction($action3);

        $this->assertEquals(['foo' => [$action1], 'bar' => [$action2, $action3]], $this->gridConfigBuilder->getActions());
    }

    public function testGetGridConfig(): void
    {
        $this->assertInstanceOf(GridConfigBuilder::class, $this->gridConfigBuilder->getGridConfig());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->gridConfigBuilder = new GridConfigBuilder($this->name, $this->options);
    }
}
