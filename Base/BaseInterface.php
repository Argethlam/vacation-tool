<?php
/**
 * Interface BaseInterface
 * contain basic CRUD methods and it will be implemented by all clasess
 *
 * @author Dalibor Stankovic <dalibor.stankovic87@gmail.com>
 * @since 19.10.2016.
 * @version 1.0
 */
namespace Base;

interface BaseInterface
{
	/**
	 * Create new instance of a class
	 */
	public function create();

	/**
	 * Read data for given id
	 */
	public function read($id);

	/**
	 * Update instance of a class
	 */
	public function update();

	/**
	 * Delete data for given id
	 */
	public function delete($id);

	/**
	 * Load object data using object id
	 */
	public function load();
}
