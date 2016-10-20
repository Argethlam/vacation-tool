<?php
/**
 * Class Calendar
 *
 * This class should be used just for displaying vacations on GUI
 *
 * @author Dalibor Stankovic <dalibor.stankovic87@gmail.com>
 * @since 15.10.2016.
 * @version 1.0
 */
namespace Calendar;

class Calendar
{
	/**
	 * Instance of Calendar class
	 *
	 * @var Calendar
	 * @access private
	 */
	private static $instance;

	/**
	 * Instance of User class
	 *
	 * @var User
	 * @access private
	 */
	private $user;

	/**
	 * Instance of a Absence class
	 *
	 * @var Absence
	 * @access private
	 */
	private $absence;


	/**
	 * This method should (create if there is no instance of a class and) return instance
	 * of Calendar class
	 *
	 * @access public
	 * @return Calendar
	 */
	public static function getInstance()
	{
		if (is_null(static::$instance)) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Protectet construct to avoid creating instance of a class
	 */
	protected function __construct() {}

	/**
	 * Private method __clone to forbid cloning of a object 
	 */
	private function __clone() {}

	/**
	 * Private method __wakeup to forbid object serialization
	 */
	private function __wakeup() {}

	/**
	 * Method setUser is used to set User object
	 *
	 * @param User $user
	 * @access public
	 * @return void
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Method setAbsence is used to set Absence object
	 *
	 * @param Absence $absence
	 * @access public
	 * @return void
	 */
	public function setAbsence(Absence $absence)
	{
		$this->absence = $absence;
	}

	/**
	 * This method should be used to load all absences into calendar
	 */
	public function loadAll() {}

	/**
	 * It should be used to load all absences for particular (given) month for current year
	 */
	public function loadAbsencesForMonth($month) {}

	/**
	 * It should be used to load all absences for given year
	 */
	public function loadAbsencesForYear($year) {}

	/**
	 * This method should be used to render all data and to show them into GUI
	 */
	public function display() {}
}
