<?php

namespace Golden\Database\Manipulation;

use Golden\Database\Syntax\QueryPartInterface;

/**
 * Class AbstractSetQuery.
 */
abstract class AbstractSetQuery implements QueryInterface, QueryPartInterface
{
    /**
     * @var array
     */
    protected $union = [];

    /**
     * @param Select $select
     *
     * @return $this
     */
    public function add(Select $select)
    {
        $this->union[] = $select;

        return $this;
    }

    /**
     * @return array
     */
    public function getUnions()
    {
        return $this->union;
    }

    /**
     * @throws QueryException
     *
     * @return \Golden\Database\Syntax\Table
     */
    public function getTable()
    {
        throw new QueryException(
            \sprintf('%s does not support tables', $this->partName())
        );
    }

    /**
     * @throws QueryException
     *
     * @return \Golden\Database\Syntax\Where
     */
    public function getWhere()
    {
        throw new QueryException(
            \sprintf('%s does not support WHERE.', $this->partName())
        );
    }

    /**
     * @throws QueryException
     *
     * @return \Golden\Database\Syntax\Where
     */
    public function where()
    {
        throw new QueryException(
            \sprintf('%s does not support the WHERE statement.', $this->partName())
        );
    }
}
