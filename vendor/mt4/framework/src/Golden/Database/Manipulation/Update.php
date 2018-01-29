<?php

namespace Golden\Database\Manipulation;

/**
 * Class Update.
 */
class Update extends AbstractCreationalQuery
{
    /**
     * @var int
     */
    protected $limitStart;

    /**
     * @var array
     */
    protected $orderBy = [];

    /**
     * @return string
     */
    public function partName()
    {
        return 'UPDATE';
    }

    /**
     * @return int
     */
    public function getLimitStart()
    {
        return $this->limitStart;
    }

    /**
     * @param int $start
     *
     * @return $this
     */
    public function limit($start)
    {
        $this->limitStart = $start;

        return $this;
    }
}
