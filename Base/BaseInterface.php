<?php
/**
 * Interface BaseInterface
 *
 * @author Dalibor Stankovic <dalibor.stankovic87@gmail.com>
 * @since 19.10.2016.
 * @version 1.0
 */
namespace Base;

interface BaseInterface
{
	public function create();

	public function read($id);

	public function update();

	public function delete($id);

	public function load();
}
