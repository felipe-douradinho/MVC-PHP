<?php

namespace Golden\Database\Manipulation;

/**
 * Class UnionAll.
 */
class UnionAll extends AbstractSetQuery
{
    const UNION_ALL = 'UNION ALL';

    /**
     * @return string
     */
    public function partName()
    {
        return 'UNION ALL';
    }
}
