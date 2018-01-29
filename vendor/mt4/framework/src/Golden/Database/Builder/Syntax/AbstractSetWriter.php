<?php

namespace Golden\Database\Builder\Syntax;

use Golden\Database\Builder\GenericBuilder;
use Golden\Database\Syntax\QueryPartInterface;

/**
 * Class AbstractSetWriter.
 */
abstract class AbstractSetWriter
{
    /**
     * @var GenericBuilder
     */
    protected $writer;

    /**
     * @param GenericBuilder $writer
     */
    public function __construct(GenericBuilder $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param QueryPartInterface $setClass
     * @param string             $setOperation
     * @param $glue
     *
     * @return string
     */
    protected function abstractWrite(QueryPartInterface $setClass, $setOperation, $glue)
    {
        $selects = [];

        foreach ($setClass->$setOperation() as $select) {
            $selects[] = $this->writer->write($select);
        }

        return \implode("\n".$glue."\n", $selects);
    }
}
