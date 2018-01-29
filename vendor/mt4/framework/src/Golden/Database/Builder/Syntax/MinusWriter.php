<?php

namespace Golden\Database\Builder\Syntax;

use Golden\Database\Builder\GenericBuilder;
use Golden\Database\Manipulation\Minus;

/**
 * Class MinusWriter.
 */
class MinusWriter
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
     * @param Minus $minus
     *
     * @return string
     */
    public function write(Minus $minus)
    {
        $first = $this->writer->write($minus->getFirst());
        $second = $this->writer->write($minus->getSecond());

        return $first."\n".Minus::MINUS."\n".$second;
    }
}
