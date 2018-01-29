<?php

namespace Golden\Database\Manipulation;

use Golden\Database\Syntax\SyntaxFactory;

/**
 * Class Insert.
 */
class Insert extends AbstractCreationalQuery
{
    /**
     * @return string
     */
    public function partName()
    {
        return 'INSERT';
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        $columns = \array_keys($this->values);

        return SyntaxFactory::createColumns($columns, $this->getTable());
    }
}
