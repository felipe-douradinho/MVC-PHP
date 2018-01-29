<?php

namespace Golden\Entity;

use Golden\Database\Builder\GenericBuilder;
use Golden\Database\Builder\MySqlBuilder;
use Golden\Database\Drivers\MySql;
use Golden\Paginator\Paginator;
use Golden\Support\Collection;

/**
 * Class Model
 *
 * @package Golden\Entity
 */
class Model
{
	/**
	 * @var $primary_key string
	 */
	private $primary_key = 'id';

	/**
	 * @var $table string
	 */
	private $table = null;

	/**
	 * @var Collection $items
	 */
	protected $items = [ ];

	/**
	 * @var Paginator $paginator
	 */
	protected $paginator;

	/**
	 * @var array $modified_data
	 */
	protected $modified_data = [ ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [];

	/**
	 * @var array $criterias
	 */
	protected static $criterias = [ ];


	/**
	 * Model constructor.
	 *
	 * * @param null|string $table
	 */
	public function __construct($table = null)
	{
		$this->setTable($table);
	}

	/**
	 * @param mixed $key
	 *
	 * @return mixed
	 */
	public function __get($key)
	{
		if(!property_exists($this, $key) && $this->getItems()->has($key))
			return $this->getItems()->get($key);
		else if(property_exists($this, $key))
			return $this->$key;
	}

	/**
	 * @param $name
	 * @param $value
	 */
	public function __set($name, $value)
	{
		$this->modified_data[$name] = $value;
	}

	/**
	 * Get all
	 *
	 * @param array $columns
	 *
	 * @return Model
	 */
	public static function get(array $columns = [ ])
	{
		if(empty(self::getCriterias()) && empty($columns))
			return self::all();

		$builder = new MySqlBuilder();

		if(!empty(self::getCriterias()))
		{
			$query = $builder->select()->setTable(self::getTable());
			$query->setColumns( (empty($columns) ? [ '*' ] : $columns) );

			foreach (self::getCriterias() as $column => $value)
				$query->where()->eq($column, $value);

			$criterias = self::getCriterias();
			self::setCriterias([ ]);

			if($result = MySql::get($builder->write($query), array_values($criterias)))
				return self::encapsulate( $result );

			return null;
		}
		else
		{
			$query = $builder->select(self::getTable(), $columns);

			if($result = MySql::get($builder->write($query)))
				return self::encapsulate( $result );

			return null;
		}
	}

	/**
	 * Get all
	 *
	 * @return Model
	 */
	public static function all()
	{
		$builder = new MySqlBuilder();
		$query = $builder->select(self::getTable(), [ '*' ]);

		if($result = MySql::get($builder->write($query)))
			return self::encapsulate( $result );

		return null;
	}

	/**
	 * @param int $per_page
	 * @param int $current_page
	 * @param string $link_pattern
	 *
	 * @return Model
	 * @throws \Exception
	 */
	public static function paginate($per_page = 10, $current_page = 1, $link_pattern = '/link/to/page/(:num)' )
	{
		$builder = new MySqlBuilder();

		// -- count
		$total_items = ($builder->select()->setTable( self::getTable() ));
		$total_items->count();
		$total_items = MySql::first($builder->write( $total_items ));
		$total_items = is_array($total_items) && !empty($total_items) ? array_first($total_items) : null;

		if(!$total_items)
			$total_items = 0;

		// -- lets paginate
		$query = $builder->select(self::getTable(), [ '*' ]);
		$query->orderBy('created_at', 'DESC');

		$from_offset = ($per_page * $current_page) - $per_page;
		$limit = " LIMIT {$from_offset}, {$per_page}";

		if($result = MySql::get( $builder->write($query) . $limit, [ $current_page, $per_page ] ))
		{
			$model = self::encapsulate( $result );
			$model->setPaginator( new Paginator( $total_items, $per_page, $current_page, $link_pattern ) );
			return $model;
		}

		return null;
	}

	/**
	 * Get specific resource
	 *
	 * @param int $id
	 *
	 * @return mixed|Model
	 */
	public static function find($id)
	{
		$builder = new MySqlBuilder();

		$query = $builder->select()
		                 ->setTable( self::getTable() )
		                 ->setColumns([ '*' ])
		                 ->where()
		                 ->eq(self::getPrimaryKey(), $id);

		if($result = MySql::first($builder->write($query), [ $id ]))
			return self::encapsulate( $result );

		return null;
	}

	/**
	 * Create specific resource
	 *
	 * @param array $data
	 *
	 * @return mixed|null|Model
	 */
	public static function create(array $data = [ ])
	{
		$data = $data + [
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$builder = new GenericBuilder();

		$query = $builder->insert()
		                 ->setTable( self::getTable() )
		                 ->setValues($data);

		// -- insert
		if($last_id = MySql::insert($builder->write($query), array_values($data)))
			return self::find($last_id);

		return null;
	}

	/**
	 * An alias to save()
	 *
	 * @param array $new_values
	 * @param array $criterias
	 *
	 * @return bool|Collection
	 */
	public static function update(array $new_values = [ ], array $criterias = [ ])
	{
		$builder = new GenericBuilder();

		$query = $builder->update()
		                 ->setTable(self::getTable())
		                 ->setValues($new_values);

		if(!empty( $criterias ))
		{
			foreach ($criterias as $column => $value)
				$query->where()->equals($column, $value);
		}

		// -- insert
		if(MySql::update($builder->write($query), array_merge( array_values($new_values), array_values($criterias)) ))
		{
			return true;
		}

		return true;
	}

	/**
	 * Save the data
	 *
	 * @param array $criterias
	 *
	 * @return null|Model
	 */
	public function save(array $criterias = [ ])
	{
		if(!empty($this->getModifiedData()))
		{
			$builder = new GenericBuilder();

			$query = $builder->update()
			                 ->setTable(self::getTable())
			                 ->setValues($this->getModifiedData());

			if(!empty( $criterias ))
			{
				foreach ($criterias as $column => $value)
					$query->where()->equals($column, $value);
			}
			else if(empty($criterias) && $this->hasId())
			{
				$query->where()->equals(self::getPrimaryKey(), $this->getId());
			}

			// -- insert
			if(MySql::update($builder->write($query), array_merge( array_values($this->getModifiedData()), [$this->getId()]) ))
			{
				if($this->hasId())
				{
					// -- replace old data with new data
					$new_resource = self::find( $this->getId() );
					$this->setItems($new_resource->getItems());
					return $this;
				}
				else
				{
					return null;
				}
			}
		}

		return $this;
	}

	/**
	 * @param array $criterias
	 *
	 * @return Model
	 */
	public static function where(array $criterias = [ ])
	{
		self::setCriterias( $criterias );

		$caller = self::invokeCaller();
		return $caller;
	}

	/**
	 * Delete
	 */
	public function delete()
	{
		$builder = new GenericBuilder();
		$query = $builder->delete()->setTable(self::getTable());

		foreach ( self::getCriterias() as $column => $value)
			$query->where()->equals($column, $value);

		$criterias = self::getCriterias();
		self::setCriterias([ ]);

		MySql::delete($builder->write($query), array_values( $criterias ));

		return true;
	}

	/**
	 * Delete without criterias
	 */
	public static function destroy()
	{
		$builder = new GenericBuilder();

		$query = $builder->delete()->setTable(self::getTable());
		MySql::delete($builder->write($query));

		return true;
	}

	/**
	 * @return mixed|Model
	 */
	private static function invokeCaller()
	{
		$caller = get_called_class();
		$caller = (new $caller);
		return $caller;
	}

	/**
	 * @return string
	 */
	public static function getTable()
	{
		return (new static())->_getTable();
	}

	/**
	 * @return mixed
	 */
	private function _getTable()
	{
		return $this->table;
	}

	/**
	 * @param string $table
	 */
	public function setTable( $table )
	{
		if(is_null($table))
			$table = pluralize(2, underscore(basename(str_replace('\\', '/', get_called_class()))));

		$this->table = $table;
	}

	/**
	 * @return string
	 */
	public static function getPrimaryKey() {
		return (new static())->_getPrimaryKey();
	}

	/**
	 * @return mixed
	 */
	private function _getPrimaryKey()
	{
		return $this->primary_key;

	}

	/**
	 * @param string $primary_key
	 */
	public function setPrimaryKey( $primary_key ) {
		$this->primary_key = $primary_key;
	}

	/**
	 * Encapsulates to the model
	 *
	 * @param array $data
	 *
	 * @return Model
	 */
	private static function encapsulate($data = [ ])
	{
		/** @var $caller $this */
		$caller = self::invokeCaller();
		$caller->setItems( collect($data) );
		return $caller;
	}

	/**
	 * @return Collection
	 */
	public function getItems() {
		if ( ! empty( $this->items ) ) {
			return $this->items;
		}
		return collect([]);
	}

	/**
	 * @param Collection $items
	 */
	public function setItems( $items ) {
		if ( ! empty( $items ) ) {
			$this->items = $items;
		} else {
			$this->items = collect([]);
		}
	}

	/**
	 * @return array
	 */
	public function getModifiedData() {
		return $this->modified_data;
	}

	/**
	 * @param array $modified_data
	 */
	public function setModifiedData( $modified_data ) {
		$this->modified_data = $modified_data;
	}

	/**
	 * Check for primary key
	 *
	 * @return bool
	 */
	private function hasId()
	{
		return $this->getItems()->has(self::getPrimaryKey());
	}

	/**
	 * Get resource primary key if exists
	 *
	 * @return int|null
	 */
	private function getId()
	{
		return $this->hasId() ? $this->getItems()->get(self::getPrimaryKey()) : null;
	}

	/**
	 * @return array
	 */
	public static function getCriterias() {
		return self::$criterias;
	}

	/**
	 * @param array $criterias
	 */
	public static function setCriterias( $criterias ) {
		self::$criterias = $criterias;
	}

	/**
	 * @return Paginator
	 */
	public function getPaginator() {
		return $this->paginator;
	}

	/**
	 * @param Paginator $paginator
	 */
	public function setPaginator( $paginator ) {
		$this->paginator = $paginator;
	}

	/**
	 * @return array
	 */
	public function getFillable() {
		return $this->fillable;
	}

	/**
	 * @param array $fillable
	 */
	public function setFillable( $fillable ) {
		$this->fillable = $fillable;
	}
}