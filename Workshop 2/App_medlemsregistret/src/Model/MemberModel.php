<?php

class MemberModel {

	private $member;
	private $firstName;
	private $lastName;
	private $personalNumber;
	private $memberIsRegistered;
	private $memberIsAuthenticated;

	public function __construct() {

		$this->member = null;
		$this->firstName = null;
		$this->lastName = null;
		$this->personalNumber = null;
		$this->memberIsRegistered = false;
		$this->memberIsAuthenticated = false;
	}

	public function saveMemberToFile($firstName, $lastName, $personalId) {

		//Fixa räknare för medlemsId också

		if(empty($firstName)) {

			return false;

		} elseif (empty($lastName)) {
			
			return false;

		} elseif ($firstName && $lastName && $personalId !== null) {


			//Räknare för memberId
			$memberId = 1;

			$lines = @file("members.txt");
				
				if($lines === false) {
					//Do nothing
				} else {
					foreach ($lines as $line) {
						$line = trim($line);

						$lineParts = explode(":", $line);

						$memberId = $lineParts[3];
						$memberId++;
					}
				}

			$file = fopen('members.txt', 'a');
			fwrite($file, ($firstName . ":" . $lastName . ":" . $personalId . ":" . $memberId . "\n"));

		}
	}
	
	public function getSpecificMember($personalId) {

		$lineParts;

		$lines = @file("members.txt");
			
		if($lines === false) {
			//Do nothing
		} else {
			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[2] == $personalId && $lineParts[2] != "null"){
					return $lineParts;
				}
			}
		}
	}

	public function getSpecificMemberMemberId($memberId) {

		$lineParts;

		$lines = @file("members.txt");
			
		if($lines === false) {
			//Do nothing
		} else {
			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[3] == $memberId && $lineParts[2] != "null"){
					return $lineParts;
				}
			}
		}
	}


	public function changeMemberData($firstName, $lastName, $personalNumber, $oldPersonalNumber) {

		$lineParts;

		$newArray = array();

		$lines = @file("members.txt");
			
		if($lines !== false) {
			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[2] == $oldPersonalNumber){
					$line = $firstName . ":" . $lastName . ":" . $personalNumber . ":" . $lineParts[3];
				}
				
				array_push($newArray, $line);
			}
		}

		$file2 = fopen('members.txt', 'w');

		foreach ($newArray as $key => $value) {
			fwrite($file2, $value . "\n");
		}
	}

	public function validateNewMember($personalNumber) {
		$lineParts;

		$lines = @file("members.txt");
				
		if($lines !== false) {
			
			foreach ($lines as $line) {
				
				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[2] == $personalNumber) {
					return false;
				}
			}
		}

		return true;
	}


	public function deleteMember($memberId) {

		$lineParts;

		$newArray = array();

		$lines = @file("members.txt");
			
		if($lines !== false) {
			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[3] != $memberId){
					array_push($newArray, $line);
				} else {
					array_push($newArray, "null:null:null:" . $memberId);
				}
			}
		}

		$file2 = fopen('members.txt', 'w');

		foreach ($newArray as $key => $value) {
			fwrite($file2, $value . "\n");
		}
	}

	public function getMemberListArray() {

		$memberListArray = array();

		$lineParts;

		$lines = @file("members.txt");
			
		if($lines === false) {
			//Do nothing
		} else {
			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[2] != "null") {
					array_push($memberListArray, $lineParts);
				}
			}
		}

		return $memberListArray;
	}
}