<?php

namespace Golden\Database\Builder\Syntax;

use Golden\Database\Manipulation\Intersect;

/**
 * Class IntersectWriter.
 */
class IntersectWriter extends AbstractSetWriter
{
    /**
     * @param Intersect $intersect
     *
     * @return string
     */
    public function write(Intersect $intersect)
    {
        return $this->abstractWrite($intersect, 'getIntersects', Intersect::INTERSECT);
    }
}
