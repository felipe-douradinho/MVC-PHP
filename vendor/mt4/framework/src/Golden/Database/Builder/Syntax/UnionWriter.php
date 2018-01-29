<?php

namespace Golden\Database\Builder\Syntax;

use Golden\Database\Manipulation\Union;

/**
 * Class UnionWriter.
 */
class UnionWriter extends AbstractSetWriter
{
    /**
     * @param Union $union
     *
     * @return string
     */
    public function write(Union $union)
    {
        return $this->abstractWrite($union, 'getUnions', Union::UNION);
    }
}
