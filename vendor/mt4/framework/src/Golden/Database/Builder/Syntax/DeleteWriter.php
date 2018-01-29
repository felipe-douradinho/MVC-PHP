<?php

namespace Golden\Database\Builder\Syntax;

use Golden\Database\Builder\GenericBuilder;
use Golden\Database\Manipulation\Delete;

/**
 * Class DeleteWriter.
 */
class DeleteWriter
{
    /**
     * @var GenericBuilder
     */
    protected $writer;

    /**
     * @var PlaceholderWriter
     */
    protected $placeholderWriter;

    /**
     * @param GenericBuilder    $writer
     * @param PlaceholderWriter $placeholder
     */
    public function __construct(GenericBuilder $writer, PlaceholderWriter $placeholder)
    {
        $this->writer = $writer;
        $this->placeholderWriter = $placeholder;
    }

    /**
     * @param Delete $delete
     *
     * @return string
     */
    public function write(Delete $delete)
    {
        $table = $this->writer->writeTable($delete->getTable());
        $parts = array("DELETE FROM {$table}");

        AbstractBaseWriter::writeWhereCondition($delete, $this->writer, $this->placeholderWriter, $parts);
        AbstractBaseWriter::writeLimitCondition($delete, $this->placeholderWriter, $parts);
        $comment = AbstractBaseWriter::writeQueryComment($delete);

        return $comment.implode(' ', $parts);
    }
}
