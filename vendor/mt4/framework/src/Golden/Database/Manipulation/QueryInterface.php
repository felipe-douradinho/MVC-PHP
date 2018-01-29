<?php

namespace Golden\Database\Manipulation;

/**
 * Interface QueryInterface.
 */
interface QueryInterface
{
    /**
     * @return string
     */
    public function partName();

    /**
     * @return \Golden\Database\Syntax\Table
     */
    public function getTable();

    /**
     * @return \Golden\Database\Syntax\Where
     */
    public function getWhere();

    /**
     * @return \Golden\Database\Syntax\Where
     */
    public function where();
}
