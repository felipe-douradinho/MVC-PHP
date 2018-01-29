<?php

namespace Golden\Database\Manipulation;

/**
 * Class AbstractCreationalQuery.
 */
abstract class AbstractCreationalQuery extends AbstractBaseQuery
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * @param string $table
     * @param array  $values
     */
    public function __construct($table = null, array $values = null)
    {
        if (isset($table)) {
            $this->setTable($table);
        }

        if (!empty($values)) {
            $this->setValues($values);
        }
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function setValues(array $values)
    {
//        $this->values = \array_filter($values, function($value) {
//            if (is_int($value) ) {
//                return true;
//            }
//            return $value;
//        });

	    $this->values = $values;

        return $this;
    }
}
