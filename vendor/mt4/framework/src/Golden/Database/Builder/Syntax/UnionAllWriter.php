<?php

namespace Golden\Database\Builder\Syntax;

use Golden\Database\Manipulation\UnionAll;

/**
 * Class UnionAllWriter.
 */
class UnionAllWriter extends AbstractSetWriter
{
    /**
     * @param UnionAll $unionAll
     *
     * @return string
     */
    public function write(UnionAll $unionAll)
    {
        return $this->abstractWrite($unionAll, 'getUnions', UnionAll::UNION_ALL);
    }
}
