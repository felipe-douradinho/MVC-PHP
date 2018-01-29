<?php

namespace Golden\Database\Builder;

use Golden\Database\Builder\Syntax\WriterFactory;
use Golden\Database\Manipulation\AbstractBaseQuery;
use Golden\Database\Manipulation\QueryInterface;
use Golden\Database\Manipulation\QueryFactory;
use Golden\Database\Manipulation\Select;
use Golden\Database\Syntax\Column;
use Golden\Database\Syntax\Table;

/**
 * Class Generic.
 */
class GenericBuilder implements BuilderInterface
{
    /**
     * The placeholder parameter bag.
     *
     * @var \Golden\Database\Builder\Syntax\PlaceholderWriter
     */
    protected $placeholderWriter;

    /**
     * The Where writer.
     *
     * @var \Golden\Database\Builder\Syntax\WhereWriter
     */
    protected $whereWriter;

    /**
     * The SQL formatter.
     */
    protected $sqlFormatter;

    /**
     * Class namespace for the query pretty output formatter.
     * Required to create the instance only if required.
     *
     * @var string
     */
    protected $sqlFormatterClass = 'Golden\Database\QueryFormatter\Formatter';

    /**
     * Array holding the writers for each query part. Methods are called upon request and stored in
     * the $queryWriterInstances array.
     *
     * @var array
     */
    protected $queryWriterArray = [
        'SELECT' => '\Golden\Database\Builder\Syntax\WriterFactory::createSelectWriter',
        'INSERT' => '\Golden\Database\Builder\Syntax\WriterFactory::createInsertWriter',
        'UPDATE' => '\Golden\Database\Builder\Syntax\WriterFactory::createUpdateWriter',
        'DELETE' => '\Golden\Database\Builder\Syntax\WriterFactory::createDeleteWriter',
        'INTERSECT' => '\Golden\Database\Builder\Syntax\WriterFactory::createIntersectWriter',
        'MINUS' => '\Golden\Database\Builder\Syntax\WriterFactory::createMinusWriter',
        'UNION' => '\Golden\Database\Builder\Syntax\WriterFactory::createUnionWriter',
        'UNION ALL' => '\Golden\Database\Builder\Syntax\WriterFactory::createUnionAllWriter',
    ];

    /**
     * Array that stores instances of query writers.
     *
     * @var array
     */
    protected $queryWriterInstances = [
        'SELECT' => null,
        'INSERT' => null,
        'UPDATE' => null,
        'DELETE' => null,
        'INTERSECT' => null,
        'MINUS' => null,
        'UNION' => null,
        'UNION ALL' => null,
    ];

    /**
     * Creates writers.
     */
    public function __construct()
    {
        $this->placeholderWriter = WriterFactory::createPlaceholderWriter();
    }

    /**
     * @param string $table
     * @param array  $columns
     *
     * @return \Golden\Database\Manipulation\Select
     */
    public function select($table = null, array $columns = null)
    {
        return $this->injectBuilder(QueryFactory::createSelect($table, $columns));
    }

    /**
     * @param \Golden\Database\Manipulation\AbstractBaseQuery
     *
     * @return \Golden\Database\Manipulation\AbstractBaseQuery
     */
    protected function injectBuilder(AbstractBaseQuery $query)
    {
        return $query->setBuilder($this);
    }

    /**
     * @param string $table
     * @param array  $values
     *
     *@return AbstractBaseQuery
     */
    public function insert($table = null, array $values = null)
    {
        return $this->injectBuilder(QueryFactory::createInsert($table, $values));
    }

    /**
     * @param string $table
     * @param array  $values
     *
     *@return AbstractBaseQuery
     */
    public function update($table = null, array $values = null)
    {
        return $this->injectBuilder(QueryFactory::createUpdate($table, $values));
    }

    /**
     * @param string $table
     *
     * @return \Golden\Database\Manipulation\Delete
     */
    public function delete($table = null)
    {
        return $this->injectBuilder(QueryFactory::createDelete($table));
    }

    /**
     * @return \Golden\Database\Manipulation\Intersect
     */
    public function intersect()
    {
        return QueryFactory::createIntersect();
    }

    /**
     * @return \Golden\Database\Manipulation\Union
     */
    public function union()
    {
        return QueryFactory::createUnion();
    }

    /**
     * @return \Golden\Database\Manipulation\UnionAll
     */
    public function unionAll()
    {
        return QueryFactory::createUnionAll();
    }

    /**
     * @param \Golden\Database\Manipulation\Select $first
     * @param \Golden\Database\Manipulation\Select $second
     *
     * @return \Golden\Database\Manipulation\Minus
     */
    public function minus(Select $first, Select $second)
    {
        return QueryFactory::createMinus($first, $second);
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->placeholderWriter->get();
    }

    /**
     * Returns a SQL string in a readable human-friendly format.
     *
     * @param QueryInterface $query
     *
     * @return string
     */
    public function writeFormatted(QueryInterface $query)
    {
        if (null === $this->sqlFormatter) {
            $this->sqlFormatter = (new \ReflectionClass($this->sqlFormatterClass))->newInstance();
        }

        return $this->sqlFormatter->format($this->write($query));
    }

    /**
     * @param QueryInterface $query
     * @param bool           $resetPlaceholders
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function write(QueryInterface $query, $resetPlaceholders = true)
    {
        if ($resetPlaceholders) {
            $this->placeholderWriter->reset();
        }

        $queryPart = $query->partName();

        if (false === empty($this->queryWriterArray[$queryPart])) {
            $this->createQueryObject($queryPart);

            return $this->queryWriterInstances[$queryPart]->write($query);
        }

        throw new \RuntimeException('Query builder part not defined.');
    }

    /**
     * @param Select $select
     *
     * @return string
     */
    public function writeJoin(Select $select)
    {
        if (null === $this->whereWriter) {
            $this->whereWriter = WriterFactory::createWhereWriter($this, $this->placeholderWriter);
        }

        $sql = ($select->getJoinType()) ? "{$select->getJoinType()} " : '';
        $sql .= 'JOIN ';
        $sql .= $this->writeTableWithAlias($select->getTable());
        $sql .= ' ON ';
        $sql .= $this->whereWriter->writeWhere($select->getJoinCondition());

        return $sql;
    }

    /**
     * @param Table $table
     *
     * @return string
     */
    public function writeTableWithAlias(Table $table)
    {
        $alias = ($table->getAlias()) ? " AS {$this->writeTableAlias($table->getAlias())}" : '';
        $schema = ($table->getSchema()) ? "{$table->getSchema()}." : '';

        return $schema.$this->writeTableName($table).$alias;
    }

    /**
     * @param $alias
     *
     * @return mixed
     */
    public function writeTableAlias($alias)
    {
        return $alias;
    }

    /**
     * Returns the table name.
     *
     * @param Table $table
     *
     * @return string
     */
    public function writeTableName(Table $table)
    {
        return $table->getName();
    }

    /**
     * @param string $alias
     *
     * @return string
     */
    public function writeColumnAlias($alias)
    {
        return sprintf('"%s"', $alias);
    }

    /**
     * @param Table $table
     *
     * @return string
     */
    public function writeTable(Table $table)
    {
        $schema = ($table->getSchema()) ? "{$table->getSchema()}." : '';

        return $schema.$this->writeTableName($table);
    }

    /**
     * @param array $values
     *
     * @return array
     */
    public function writeValues(array &$values)
    {
        \array_walk(
            $values,
            function (&$value) {
                $value = $this->writePlaceholderValue($value);
            }
        );

        return $values;
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function writePlaceholderValue($value)
    {
        return $this->placeholderWriter->add($value);
    }

    /**
     * @param $operator
     *
     * @return string
     */
    public function writeConjunction($operator)
    {
        return ' '.$operator.' ';
    }

    /**
     * @return string
     */
    public function writeIsNull()
    {
        return ' IS NULL';
    }

    /**
     * @return string
     */
    public function writeIsNotNull()
    {
        return ' IS NOT NULL';
    }

    /**
     * Returns the column name.
     *
     * @param Column $column
     *
     * @return string
     */
    public function writeColumnName(Column $column)
    {
        $name = $column->getName();

        if ($name === Column::ALL) {
            return $this->writeColumnAll();
        }

        return $name;
    }

    /**
     * @return string
     */
    protected function writeColumnAll()
    {
        return '*';
    }

    /**
     * @param string $queryPart
     */
    protected function createQueryObject($queryPart)
    {
        if (null === $this->queryWriterInstances[$queryPart]) {
            $this->queryWriterInstances[$queryPart] = \call_user_func_array(
                \explode('::', $this->queryWriterArray[$queryPart]),
                [$this, $this->placeholderWriter]
            );
        }
    }
}
