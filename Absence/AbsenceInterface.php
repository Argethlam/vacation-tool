<?php
/**
 * Interface AbsenceInterface
 * contain a few methods characteristic for absence
 *
 * @author Dalibor Stankovic <dalibor.stankovic87@gmail.com>
 * @since 19.10.2016.
 * @version 1.0
 */
namespace Absence;

interface AbsenceInterface
{
	/**
	 * Approve particular absence using absence id
	 */
	public function approve();

	/**
	 * Reject particular absence using absence id
	 */
	public function reject();
}