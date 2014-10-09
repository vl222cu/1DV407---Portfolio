<?php

require_once("MemberDAL.php");

class MemberModel {

	private $member;
	private $firstName;
	private $lastName;
	private $personalNumber;
	private $memberIsRegistered;
	private $memberIsAuthenticated;
	private $dbQuery;

	public function __construct() {

		$this->member = null;
		$this->firstName = null;
		$this->lastName = null;
		$this->personalNumber = null;
		$this->memberIsRegistered = false;
		$this->memberIsAuthenticated = false;
		$this->dbQuery = new MemberDAL();

	}

	public function authenticateMemberData ($firstName, $lastName, $personalNumber) {

		if(empty($firstName)) {

			return false;

		} elseif (empty($lastName)) {
			
			return false;

		} elseif (empty($personalNumber)) {
			
			return false;

		} elseif ($firstName && $lastName && $personalNumber !== null) {
			
			$ret = $this->dbQuery->setMemberCredentialsInDB($firstName, $lastName, $personalNumber);

			if($ret == true) {

				$this->memberIsRegistered = true;
				return true;

			} else {

				$this->memberIsRegistered = false;
				return false;
			}
		}
	}

	public function checkIfCurrentMemberExists($personalNumber) {

		if ($personalNumber !== null) {

			$ret = $this->dbQuery->getMemberByPersonalNumberFromDB($personalNumber);

			if ($ret == true) {

				return true;

			} else {

				return false;

			}
		}

	}
}