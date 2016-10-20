<?php
/**
 * Class Absence contain simple structure and functionality which can help us
 * to try to build complex vacation system where we can handle absences of all 
 * users inside one company.
 *
 * @author Dalibor Stankovic <dalibor.stankovic87@gmail.com>
 * @since 15.10.2016.
 * @version 1.0
 */
namespace Absence;

use Base\BaseInterface;

class Absence implements BaseInterface, AbsenceInterface
{
	/**
	 * List of constants which represents absence types and statuses
	 */
	const TYPE_VACATION			= 1;
	const TYPE_SICK_LEAVE		= 2;
	const TYPE_UNPAID_LEAVE 	= 3;

	const STATUS_PENDING		= 1;
	const STATUS_APPROVED		= 2;
	const STATUS_REJECTED		= 3;

	/**
	 * Absence id
	 *
	 * @var int
	 * @access private
	 */
	private $id;

	/**
	 * Absence date from
	 *
	 * @var string
	 * @access private
	 */
	private $dateFrom;

	/**
	 * Absence date to
	 *
	 * @var string
	 * @access private
	 */
	private $dateTo;

	/**
	 * Absence type - one of constants
	 *
	 * @var int
	 * @access private
	 */
	private $type;

	/**
	 * Absence user id - id from User object
	 *
	 * @var int
	 * @access private
	 */
	private $userId;

	/**
	 * Absence status id - one of constants
	 *
	 * @var int
	 * @access private
	 */
	private $status;


	/**
	 * Class construct
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Method setId is used to set id for Absence object
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
	 * Set date from for absence
	 *
	 * @param string $dateFrom
	 * @access public
	 * @return void
	 */
	public function setDateFrom($dateFrom)
	{
		$this->dateFrom = $dateFrom;
	}

	/**
	 * Set date to for absence
	 *
	 * @param string $dateTo
	 * @access public
	 * @return void
	 */
	public function setDateTo($dateTo)
	{
		$this->dateTo = $dateTo;
	}

	/**
	 * Set one of given types - one of class constants
	 *
	 * @param int $type - if type is not defined in class, TYPE_VACATION will be used
	 * @access public
	 * @return void
	 */
	public function setType($type)
	{
		$this->type = in_array($tyoe, $this->getAbsenceTypes())? $ype : self::TYPE_VACATION;
	}

	/**
	 * Return absence type
	 *
	 * @access public
	 * @return int
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set id of user for which one absence is
	 *
	 * @param int $userId
	 * @access public
	 * @return void
	 */
	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	/**
	 * Return absence user id
	 *
	 * @access public
	 * @return int
	 */
	public function getUserId()
	{
		return $this->userId;
	}

	/**
	 * Set one of available status - one of constants defined in class
	 *
	 * @param int $status
	 * @access private
	 * @return void
	 */
	private function setStatus($status)
	{
		$this->status = $status;
	}

	/**
	 * Return absence status id
	 *
	 * @access public
	 * @return int
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Method create is used to create new absence using already set object data
	 *
	 * @access public
	 * @return boolean - true if absence is created otherwise false
	 */
	public function create() 
	{
		// save absence in database
		$sqlInsert = '
			INSERT INTO absence 
				(user_id, absence_type_id, absence_status_id, from_date, to_date)
			VALUES 
				(:userId, :absenceType, :absenceStatus, :dateFrom, :dateTo)
		';

		$params = array(
			'userId' 		=> $this->userId,
			'absenceType' 	=> $this->type,
			'absenceStatus' => self::STATUS_PENDING,
			'dateFrom' 		=> $this->dateFrom,
			'dateTo' 		=> $this->dateTo
		);

		return DB::query($sqlInsert, $params);
	}

	/**
	 * Method read is used to get all data from database for given absence id
	 *
	 * @param int $absenceId
	 * @access public
	 * @return array - data from database
	 */
	public function read($absenceId) 
	{
		$sqlSelect = '
			SELECT *
			FROM absence 
			WHERE id = :id
		';

		$params = array(
			'id'	=> $absenceId
		);

		return DB::query($sqlSelect, $params);
	}

	/**
	 * Method load is used to load all absence data using given absence id
	 *
	 * @access public
	 * @return Absence
	 */
	public function load() 
	{
		$sqlSelect = '
			SELECT *
			FROM absence 
			WHERE id = :id
		';

		$params = array(
			'id'	=> $this->id
		);

		$data = DB::query($sqlSelect, $params);

		$this->setDateFrom($data['from_date']);
		$this->setDateTo($data['to_date']);
		$this->setType($data['absence_type_id']);
		$this->setUserId($data['user_id']);
		$this->setStatus($data['absence_status_id']);

		return $this;
	}

	/**
	 * Method update - update data for particular absence
	 *
	 * @access public
	 * @return boolean - true if absence is deleted or false if it's not
	 */
	public function update() 
	{
		$sqlUpdate = '
			UPDATE absence
			SET absence_type_id = :absenceType,
				absence_status_id = :absenceStatus,
				from_date = :dateFrom,
				to_date = :dateTo
			WHERE id = :id
		';

		$params = array(
			'absenceType' 	=> $this->absence_type_id,
			'absenceStatus' => $this->absence_status_id,
			'dateFrom'		=> $this->dateFrom,
			'dateTo'		=> $this->dateTo,
			'id'			=> $this->id
		);

		return DB::query($sqlUpdate, $params);
	}

	/**
	 * Method delete remove absence from database
	 *
	 * @param int $absenceId
	 * @access public
	 * @return boolean - true if absence is deleted or false if it's not
	 */
	public function delete($absenceId) 
	{
		$sqlDelete = '
			DELETE 
			FROM absence 
			WHERE id = :id
		';

		$params = array(
			'id'	=> $absenceId
		);

		return DB::query($sqlDelete, $params);
	}

	/**
	 * Method approve set absence status on APPROVED
	 *
	 * @access public
	 * @return void
	 */
	public function approve() 
	{
		$this->updateStatus(self::STATUS_APPROVED);
	}

	/**
	 * Method reject set absence staus on REJECTED
	 *
	 * @access public
	 * @return void
	 */
	public function reject()
	{
		$this->updateStatus(self::STATUS_REJECTED);
	}

	/**
	 * Method updateStatus update absence status
	 *
	 * @param int $status
	 * @access private
	 * @return boolean - true or false, depends if update successful or not
	 */
	private function updateStatus($status)
	{
		$sqlUpdate = '
			UPDATE absence
			SET absence_status_id = :absenceStatus
			WHERE id = :id
		';

		$params = array(
			':absenceStatus' 	=> $status,
			'id'				=> $this->id
		);

		return DB::query($sqlUpdate, $params);
	}

	/**
	 * Method getAbsenceTypes return list of all available types of absence
	 *
	 * @access public
	 * @return array
	 */
	public function getAbsenceTypes()
	{
		return array(
			self::TYPE_VACATION,
			self::TYPE_SICK_LEAVE,
			self::TYPE_UNPAID_LEAVE
		);
	}

	/**
	 * Method getAbsenceDays return number of days which user requested inside 
	 * this absence
	 *
	 * @access public
	 * @return int
	 */
	public function getAbsenceDays()
	{
		$fromParts = explode('-', $this->dateFrom);
		$toParts = explode('-', $this->dateTo);

		$dateFrom = new DateTime();
		$dateFrom->setDate($fromParts[0], $fromParts[1], $fromParts[2]);

		$dateTo = new DateTime();
		$dateTo->setDate($toParts[0], $toParts[1], $toParts[2]);

		$interval = $dateFrom->diff($dateTo);
		
		return $interval->format('d');
	}
}