<?php

namespace Wine\Support;

use \Wine\Support\Arr;


/**
* The collection repository.
* A re-usable class to hold a collection of items.
*
*/
class Collection
{

	/**
	* All of the items in collection
	*
	* @var array
	*/
	protected $items = [];


	/**
	* Create a new collection repository.
	*
	* @param  array  $items
	* @return void
	*/
	public function __construct(array $items = [])
	{
		$this->items = $items;
	}


	/**
	* Get all items in collection
	*
	* @return array
	*/
	public function all()
	{
		return $this->items;
	}


	/**
	* Returns true if the parameter is defined.
	*
	* @param string $key The key
	*
	* @return bool true if the parameter exists, false otherwise
	*/
	public function has($key)
	{
		return array_key_exists($key, $this->items);
	}


	/**
	* Get the specified configuration value.
	*
	* @param  array|string  $key
	* @param  mixed   $default
	* @return mixed
	*/
	public function get($key, $default = null)
	{
		if (is_array($key)) {
			return $this->getMany($key);
		}

		return Arr::get($this->items, $key, $default);
	}


	/**
	* Get many configuration values.
	*
	* @param  array  $keys
	* @return array
	*/
	public function getMany($keys)
	{
		$config = [];

		foreach ($keys as $key => $default)
		{
			if (is_numeric($key)) {
				list($key, $default) = [$default, null];
			}

			$config[$key] = Arr::get($this->items, $key, $default);
		}

		return $config;
	}


	/**
	* Set a given value into the collection
	*
	* @param  array|string  $key
	* @param  mixed   $value
	* @return void
	*/
	public function set($key, $value = null)
	{
		$keys = is_array($key) ? $key : [$key => $value];

		foreach ($keys as $key => $value)
		{
			$this->items[$key] = $value;
		}
	}


	/**
	* Removes a item.
	*
	* @param string $key The key
	*/
	public function remove($key)
	{
		unset($this->items[$key]);
	}


	/**
	* Returns the number of items.
	*
	* @return int The number of items
	*/
	public function count()
	{
		return count($this->items);
	}


    /**
	* If the collection is seen as a string, send it as json
	*
	* @return string JSON formatted
	*/
	public function __toString()
	{
		return json_encode($this->items,1);
	}


}
