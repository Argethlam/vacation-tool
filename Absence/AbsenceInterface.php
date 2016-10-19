<?php
/**
 * Interface AbsenceInterface
 *
 * @author Dalibor Stankovic <dalibor.stankovic87@gmail.com>
 * @since 19.10.2016.
 * @version 1.0
 */
namespace Absence;

interface AbsenceInterface
{
	public function approve();

	public function reject();
}