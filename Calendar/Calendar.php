<?php
/**
 * Class Calendar
 *
 * This class should be used just like a container for displaying vacations
 *
 * @author Dalibor Stankovic <dalibor.stankovic87@gmail.com>
 * @since 15.10.2016.
 * @version 1.0
 */
namespace Calendar;

class Calendar
{
	private static $instance;

	private $user;

	private $vacation;

	public static function getInstance()
	{
		if (is_null(static::$instance)) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	protected function __construct() {}

	private function __clone() {}

	private function __wakeup() {}

	public function setUser(User $user)
	{
		$this->user = $user;
	}

	public function setAbsence(Absence $absence)
	{
		$this->vacation = $vacation;
	}

	public function loadAll() {}

	public function loadAbsencesForMonth($month) {}

	public function loadAbsencesForYear($year) {}

	public function display() {}
}
