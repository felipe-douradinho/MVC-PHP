<?php

namespace Golden\Database\Builder;

use Golden\Database\Manipulation\QueryInterface;

/**
 * Interface BuilderInterface.
 */
interface BuilderInterface
{
    /**
     * @param QueryInterface $query
     *
     * @return string
     */
    public function write(QueryInterface $query);

    /**
     * @param QueryInterface $query
     *
     * @return string
     */
    public function writeFormatted(QueryInterface $query);
}
