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
	private $id;

	private $firstName;

	private $lastName;

	private $calendar;


	public function __construct() {}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

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

	public function requestAbsence($form, $to, $absenceType) 
	{
		// crate Vacation object
		$this->createAbsence($form, $to, $absenceType);

		// add Vacation to calendar
		$this->addVacationInCalendar();
	}

	private function createAbsence($dateFrom, $dateTo, $absenceType)
	{
		$absence = new Absence();

		$absence->setDateFrom($dateFrom);
		$absence->setDateTo($dateTo);
		$absence->setType($absenceType);
		$absence->setUserId($this->getId());

		$absence->create();
	}

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

	public function approveAbsence($absenceId) 
	{
		$absence = new Absence();
		
		$absence->setId($absenceId);
		$absence->load();
		$absence->approve();

		$this->updateNumberOfDays($absence);
	}

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

	public function deleteAbsence($absenceId) 
	{
		$absence = new Absence();

		$absence->delete($absenceId);
	}

	public function getRemainingDaysOff($type) 
	{
		// return left days from db for user and absence type
		$sqlSelect = '
			SELECT days_left
			FROM user_absence
			WHERE year = :year
			AND absence_type_id = :absenceType
		';

		$params = array(
			'year' 			=> date('Y'),
			'absenceType' 	=> $type
		);

		return DB::query($sqlSelect, $params);
	}
}