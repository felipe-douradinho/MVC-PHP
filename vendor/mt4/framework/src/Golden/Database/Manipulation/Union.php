<?php

namespace Golden\Database\Manipulation;

/**
 * Class Union.
 */
class Union extends AbstractSetQuery
{
    const UNION = 'UNION';

    /**
     * @return string
     */
    public function partName()
    {
        return 'UNION';
    }
}
