<?php

namespace APY\DataGridBundle\Tests\Grid\Mapping;

use APY\DataGridBundle\Grid\Mapping\Source;
use PHPUnit\Framework\TestCase;

class SourceTest extends TestCase
{
    public function setUp(): void
    {
        $this->source = new Source([]);
    }

    public function testColumnsHasDefaultValue(): void
    {
        $this->assertEquals([], $this->source->getColumns());
    }

    public function testFilterableHasDefaultValue(): void
    {
        $this->assertEquals(true, $this->source->isFilterable());
    }

    public function testSortableHasDefaultValue(): void
    {
        $this->assertEquals(true, $this->source->isSortable());
    }

    public function testGroupsHasDefaultValue(): void
    {
        $expectedGroups = ['0' => 'default'];

        $this->assertEquals($expectedGroups, $this->source->getGroups());
    }

    public function testGroupByHasDefaultValue(): void
    {
        $this->assertEquals([], $this->source->getGroupBy());
    }

    public function testSetterColumns(): void
    {
        $columns = 'columns';
        $expectedColumns = [$columns];

        $this->source = new Source(['columns' => $columns]);

        $this->assertEquals($expectedColumns, $this->source->getColumns());
    }

    public function testGetterColumns(): void
    {
        $columns = 'columns';
        $expectedColumns = [$columns];

        $this->source = new Source(['columns' => $columns]);

        $this->assertEquals($expectedColumns, $this->source->getColumns());
    }

    public function testGetterHasColumns(): void
    {
        $columns = 'columns';

        $this->source = new Source(['columns' => $columns]);

        $this->assertTrue($this->source->hasColumns());
    }

    public function testSetterFilterable(): void
    {
        $filterable = false;

        $this->source = new Source(['filterable' => $filterable]);

        $this->assertEquals($filterable, $this->source->isFilterable());
    }

    public function testGetterFilterable(): void
    {
        $filterable = false;

        $this->source = new Source(['filterable' => $filterable]);

        $this->assertEquals($filterable, $this->source->isFilterable());
    }

    public function testSetterSortable(): void
    {
        $sortable = false;

        $this->source = new Source(['sortable' => $sortable]);

        $this->assertEquals($sortable, $this->source->isSortable());
    }

    public function testGetterSortable(): void
    {
        $sortable = false;

        $this->source = new Source(['sortable' => $sortable]);

        $this->assertEquals($sortable, $this->source->isSortable());
    }

    public function testSetterGroups(): void
    {
        $groups = 'groups';
        $expectedGroups = [$groups];

        $this->source = new Source(['groups' => $groups]);

        $this->assertEquals($expectedGroups, $this->source->getGroups());
    }

    public function testGetterGroups(): void
    {
        $groups = 'groups';
        $expectedGroups = [$groups];

        $this->source = new Source(['groups' => $groups]);

        $this->assertEquals($expectedGroups, $this->source->getGroups());
    }

    public function testSetterGroupBy(): void
    {
        $groupsBy = 'groupBy';
        $expectedGroupsBy = [$groupsBy];

        $this->source = new Source(['groupBy' => $groupsBy]);

        $this->assertEquals($expectedGroupsBy, $this->source->getGroupBy());
    }

    public function testGetterGroupBy(): void
    {
        $groupsBy = 'groupBy';
        $expectedGroupsBy = [$groupsBy];

        $this->source = new Source(['groupBy' => $groupsBy]);

        $this->assertEquals($expectedGroupsBy, $this->source->getGroupBy());
    }
}
