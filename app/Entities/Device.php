<?php

namespace App\Entities;

use Golden\Entity\Model;

/**
 * Class Device
 *
 * @package App\Entities
 */
class Device extends Model
{

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'hostname', 'ip_address', 'type', 'manufacturer', 'model', 'active', ];

}