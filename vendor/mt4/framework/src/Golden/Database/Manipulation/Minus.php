<?php

namespace Golden\Database\Manipulation;

use Golden\Database\Syntax\QueryPartInterface;

/**
 * Class Minus.
 */
class Minus implements QueryInterface, QueryPartInterface
{
    const MINUS = 'MINUS';

    /**
     * @var Select
     */
    protected $first;

    /**
     * @var Select
     */
    protected $second;

    /**
     * @return string
     */
    public function partName()
    {
        return 'MINUS';
    }

    /***
     * @param Select $first
     * @param Select $second
     */
    public function __construct(Select $first, Select $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    /**
     * @return \Golden\Database\Manipulation\Select
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @return \Golden\Database\Manipulation\Select
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * @throws QueryException
     *
     * @return \Golden\Database\Syntax\Table
     */
    public function getTable()
    {
        throw new QueryException('MINUS does not support tables');
    }

    /**
     * @throws QueryException
     *
     * @return \Golden\Database\Syntax\Where
     */
    public function getWhere()
    {
        throw new QueryException('MINUS does not support WHERE.');
    }

    /**
     * @throws QueryException
     *
     * @return \Golden\Database\Syntax\Where
     */
    public function where()
    {
        throw new QueryException('MINUS does not support the WHERE statement.');
    }
}
