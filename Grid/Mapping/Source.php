<?php

/*
 * This file is part of the DataGridBundle.
 *
 * (c) Abhoryo <abhoryo@free.fr>
 * (c) Stanislav Turza
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace APY\DataGridBundle\Grid\Mapping;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]

class Source
{
    protected $columns;
    protected $filterable;
    protected $sortable;
    protected $groups;
    protected $groupBy;

    public function __construct(?string ...$metadata)
    {
        $this->columns = (isset($metadata['columns']) && $metadata['columns'] != '') ? array_map('trim', explode(',', $metadata['columns'])) : [];
        $this->filterable = $metadata['filterable'] ?? true;
        $this->sortable = $metadata['sortable'] ?? true;
        $this->groups = (isset($metadata['groups']) && $metadata['groups'] != '') ? array_map('trim', explode(',', $metadata['groups'])) : ['default'];
        $this->groupBy = (isset($metadata['groupBy']) && $metadata['groupBy'] != '') ? array_map('trim', explode(',', $metadata['groupBy'])) : [];
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function hasColumns()
    {
        return !empty($this->columns);
    }

    public function isFilterable()
    {
        return $this->filterable;
    }

    public function isSortable()
    {
        return $this->sortable;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function getGroupBy()
    {
        return $this->groupBy;
    }

    /**
     * Get the value of sortable
     */
    public function getSortable()
    {
        return $this->sortable;
    }
}
