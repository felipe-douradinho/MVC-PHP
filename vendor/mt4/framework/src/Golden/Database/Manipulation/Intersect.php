<?php

namespace Golden\Database\Manipulation;

use Golden\Database\Syntax\QueryPartInterface;

/**
 * Class Intersect.
 */
class Intersect implements QueryInterface, QueryPartInterface
{
    const INTERSECT = 'INTERSECT';

    /**
     * @var array
     */
    protected $intersect = [];

    /**
     * @return string
     */
    public function partName()
    {
        return 'INTERSECT';
    }

    /**
     * @param Select $select
     *
     * @return $this
     */
    public function add(Select $select)
    {
        $this->intersect[] = $select;

        return $this;
    }

    /**
     * @return array
     */
    public function getIntersects()
    {
        return $this->intersect;
    }

    /**
     * @throws QueryException
     *
     * @return \Golden\Database\Syntax\Table
     */
    public function getTable()
    {
        throw new QueryException('INTERSECT does not support tables');
    }

    /**
     * @throws QueryException
     *
     * @return \Golden\Database\Syntax\Where
     */
    public function getWhere()
    {
        throw new QueryException('INTERSECT does not support WHERE.');
    }

    /**
     * @throws QueryException
     *
     * @return \Golden\Database\Syntax\Where
     */
    public function where()
    {
        throw new QueryException('INTERSECT does not support the WHERE statement.');
    }
}
