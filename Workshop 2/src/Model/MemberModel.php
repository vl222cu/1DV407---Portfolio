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

			$this->saveMemberToFile($firstName, $lastName, $personalNumber);

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

	public function saveMemberToFile($firstName, $lastName, $personalId) {

		//Fixa räknare för medlemsId också

		$memberId = 1;

		$lines = @file("members.txt");
			
			if($lines === false) {
				//Do nothing
			} else {
				foreach ($lines as $line) {
					$line = trim($line);

					$lineParts = explode(":", $line);

					$memberId = $lineParts[3] + 1;
				}
			}



		$file = fopen('members.txt', 'a');
		fwrite($file, ($firstName . ":" . $lastName . ":" . $personalId . ":" . $memberId . "\n"));
	}

	public function getMemberListHTML() {
		//Ska returnera array med båtar - Format: "Ägare", "Typ", "Längd"
		$memberListArray = array();

		$lineParts;

		$lines = @file("members.txt");
			
			if($lines === false) {
				//Do nothing
			} else {
				foreach ($lines as $line) {
					$line = trim($line);

					$lineParts = explode(":", $line);

					$lineParts[0];
					$lineParts[1];
					$lineParts[2];
					$lineParts[3];
					
					array_push($memberListArray, $lineParts);
				}
			}



		$memberListHTML = "<option selected>Välj medlem</option>\n";

		foreach($memberListArray as $key => $value) {

			$firstName = $value[0];
			$lastName = $value[1];
			$memberId = $value[3];
			$personalId = $value[2];

			$memberListHTML .= "<option value='$memberId'>$firstName $lastName - $personalId </option>\n";
		}

		return $memberListHTML;
	}
}