<?php
/**
 * Class User
 *
 * @author Dalibor Stankovic <dalibor.stankovic87@gmail.com>
 * @since 15.10.2016.
 * @version 1.0
 */
namespace User;

use Base\BaseInterface;
use Absence\Absence;

class User implements BaseInterface
{
	/**
	 * User id
	 *
	 * @var int
	 * @access private
	 */
	private $id;

	/**
	 * User first name
	 *
	 * @var string
	 * @access private
	 */
	private $firstName;

	/**
	 * User last name
	 *
	 * @var string
	 * @access private
	 */
	private $lastName;


	/**
	 * Class construct
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Set user id
	 *
	 * @param int $id
	 * @access public
	 * @return void
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Get user id
	 *
	 * @access public
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set user first name
	 *
	 * @param string $firstName
	 * @access public
	 * @return void
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	/**
	 * Get user first name
	 *
	 * @access public
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * Set user last name
	 *
	 * @param string $lastName
	 * @access public
	 * @return void
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}

	/**
	 * Get user last name
	 *
	 * @access public
	 * @return string
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * Method create is used to create user - insert data into database
	 *
	 * @access public
	 * @return boolean - true if user is created or false if is not
	 */
	public function create() 
	{
		$sqlInsert = '
			INSERT INTO user 
				(first_name, last_name)
			VALUES 
				(:firstName, :lastName)
		';

		$params = array(
			'firstName' => $this->firstName,
			'lastName' 	=> $this->lastName
		);

		return DB::query($sqlInsert, $params);
	}

	/**
	 * Method read is used to get users data from database by given user id
	 *
	 * @param  int $userId
	 * @access public
	 * @return array from database
	 */
	public function read($userId) 
	{
		$sqlSelect = '
			SELECT *
			FROM user 
			WHERE id = :id
		';

		$params = array(
			'id'	=> $userId
		);

		return DB::query($sqlSelect, $params);
	}

	/**
	 * Method update is used to update users data for particular user id
	 *
	 * @access public
	 * @return boolean - true if users data are updated or false
	 */
	public function update() 
	{
		$sqlUpdate = '
			UPDATE user
			SET first_name = :firstName,
				last_name = :lastName
			WHERE id = :id
		';

		$params = array(
			'firstName' => $this->firstName,
			'lastName' 	=> $this->lastName,
			'id'		=> $this->id
		);

		return DB::query($sqlUpdate, $params);
	}

	/**
	 * Method delete is used to remove users data from database for given user id
	 *
	 * @param int $userId
	 * @return boolean - true if user is removed from database, otherwise false
	 */
	public function delete($userId)
	{
		$sqlDelete = '
			DELETE 
			FROM user 
			WHERE id = :id
		';

		$params = array(
			'id'	=> $userId
		);

		return DB::query($sqlDelete, $params);
	}

	/**
	 * Method load is used to load data about user (using user id) from database and to 
	 * populate them inside User object
	 *
	 * @access public
	 * @return User object
	 */
	public function load()
	{
		$sqlSelect = '
			SELECT *
			FROM user 
			WHERE id = :id
		';

		$params = array(
			'id'	=> $this->id
		);

		$userData = DB::query($sqlSelect, $params);

		$this->setFirstName($data['first_name']);
		$this->setLastName($data['last_name']);

		return $this;
	}

	/**
	 * Method requestAbsence - used to request users absence using given parameters
	 *
	 * @param string $from - format (Y-m-d)
	 * @param string $to - format (Y-m-d)
	 * @param int $absenceType -  one of Absence types defined inside Absence class
	 *         available options: - 1 - VACATION
	 *                  			2 - SICK_LEAVE
	 *                     			3 - UNPAID_LEAVE
	 * @access public
	 * @return void
	 */
	public function requestAbsence($from, $to, $absenceType) 
	{
		$this->createAbsence($from, $to, $absenceType);
	}

	/**
	 * Method createAbsence - this method is used to create users absence
	 *
	 * @param string $dateFrom - format (Y-m-d)
	 * @param string $dateTo - format (Y-m-d)
	 * @param int $absenceType -  one of Absence types defined inside Absence class
	 *         available options: - 1 - VACATION
	 *                  			2 - SICK_LEAVE
	 *                     			3 - UNPAID_LEAVE
	 * @access private
	 * @return void
	 */
	private function createAbsence($dateFrom, $dateTo, $absenceType)
	{
		$absence = new Absence();

		$absence->setDateFrom($dateFrom);
		$absence->setDateTo($dateTo);
		$absence->setType($absenceType);
		$absence->setUserId($this->getId());

		$absence->create();
	}

	/**
	 * Method getMyAbsences returns list of all users requested absences
	 *
	 * @access public
	 * @return array - data from database
	 */
	public function getMyAbsences()
	{
		$sqlSelect = '
			SELECT 
				a.id AS absenceId,
				at.name AS absenceType,
				as.name AS absenceStatus,
				a.from_date AS dateFrom,
				a.to_date AS dateTo
			FROM absence AS a
			INNER JOIN absence_type AS at
				ON a.absence_type_id = at.id
			INNER JOIN absence_status AS `as`
				ON a.absence_status_id = `as`.id
			WHERE user_id = :userId
		';

		$params = array(
			'userId'	=>	$this->id
		);

		return DB::query($sqlSelect, $params);
	}

	/**
	 * Method approveAbsence is used to approve requested absence using given absence id
	 *
	 * @param int $absenceId
	 * @access public
	 * @return void
	 */
	public function approveAbsence($absenceId) 
	{
		$absence = new Absence();
		
		$absence->setId($absenceId);
		$absence->load();
		$absence->approve();

		$this->updateNumberOfDays($absence);
	}

	/**
	 * Method updateNumberOfDays - after absence is approved, we need to update remaining
	 * days off numbers for user
	 *
	 * @param Absence $absence
	 * @access private
	 * @return void
	 */
	private function updateNumberOfDays(Absence $absence)
	{
		$absenceDays = $absence->getAbsenceDays();

		$sqlUpdate = '
			UPDATE user_absence
			SET days_left = days_left - :daysNumber
			WHERE user_id = :userId
				AND absence_type_id = :absenceType
				AND year = :year
		';

		$params = array(
			'daysNumber' 	=> $absenceDays,
			'userId' 		=> $absence->getUserId(),
			'year' 			=> date('Y'),
			'absenceType'	=> $absence->getType()
		);

		DB::query($sqlUpdate, $params);
	}

	/**
	 * Method deleteAbsence is used to remove users requested absence using
	 * given absence id but just before absence is approved or rejected
	 *
	 * @param int $absenceId
	 * @access public
	 * @return void
	 */
	public function deleteAbsence($absenceId) 
	{
		$absence = new Absence();
		$absence->setId($absenceId);
		$absence->load();

		if ($absence->getStatus() == Absence::STATUS_PENDING)
			$absence->delete($absenceId);
	}

	/**
	 * Method rejectAbsence is used to reject requested absence using absence id
	 *
	 * @param int $absenceId
	 * @access public
	 * @return void
	 */
	public function rejectAbsence($absenceId)
	{
		$absence = new Absence();
		$absence->setId($absenceId);

		$absence->reject();
	}

	/**
	 * Method getRemainingDaysOff return a number of remaining days off for a user and
	 * for particular type of absence in current year
	 *
	 * @param int $type - one of Absence types defined inside Absence class
	 *                  available options: - 1 - VACATION
	 *                  					 2 - SICK_LEAVE
	 *                  					 3 - UNPAID_LEAVE
	 * @access public
	 * @return array from database
	 */
	public function getRemainingDaysOff($type) 
	{
		$sqlSelect = '
			SELECT days_left
			FROM user_absence
			WHERE user_id = :userId
				AND year = :year
				AND absence_type_id = :absenceType
		';

		$params = array(
			'userId'		=> $this->id,
			'year' 			=> date('Y'),
			'absenceType' 	=> $type
		);

		return DB::query($sqlSelect, $params);
	}
}