<?php
/**
 * Class Absence
 *
 * @author Dalibor Stankovic <dalibor.stankovic87@gmail.com>
 * @since 15.10.2016.
 * @version 1.0
 */
// namespace Absence;

use Base\BaseInterface;
use Absence\AbsenceInterface;

class Absence implements BaseInterface, AbsenceInterface
{
	// Define some constants about types and statuses
	const VACATION			= 1;
	const SICK_LEAVE		= 2;
	const UNPAID_LEAVE 		= 3;

	const STATUS_PENDING	= 1;
	const STATUS_APPROVED	= 2;
	const STATUS_REJECTED	= 3;


	private $id;

	private $dateFrom;

	private $dateTo;

	private $type;

	private $userId;

	private $status;


	public function __construct() {}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function setDateFrom($dateFrom)
	{
		$this->dateFrom = $dateFrom;
	}

	public function setDateTo($dateTo)
	{
		$this->dateTo = $dateTo;
	}

	public function setType($type)
	{
		$this->type = in_array($tyoe, $this->getAbsenceTypes())? $ype : self::VACATION;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	private function setStatus($status)
	{
		$this->status = $status;
	}

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

	public function read($absenceId) 
	{
		// get Absence from db by ID
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

	public function load() 
	{
		// get Absence from db by ID
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

	public function update() 
	{
		// update ubsence - update dateFrom, dateTo, absenceType and absenceStatus
		// using ID of a absence
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

	public function delete($absenceId) 
	{
		// delete absence from database using given ID
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

	public function approve() 
	{
		// approve and reject can hold some functionality e.g. (to send email to user with notification)
		$this->updateStatus(self::STATUS_APPROVED);
	}

	public function reject()
	{
		$this->updateStatus(self::STATUS_REJECTED);
	}

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

	public function getAbsenceTypes()
	{
		return array(
			self::VACATION,
			self::SICK_LEAVE,
			self::UNPAID_LEAVE
		);
	}

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