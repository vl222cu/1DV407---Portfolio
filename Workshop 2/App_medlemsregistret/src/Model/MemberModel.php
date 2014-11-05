<?php

class MemberModel {

	private $member;
	private $firstName;
	private $lastName;
	private $personalNumber;
	private $memberIsRegistered;
	private $memberIsAuthenticated;
	//private $dbQuery;

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




	

	

	

	public function getBoatListArray() {

		$boatListArray = array();
		$lines = @file("boatList.txt");
				
		if($lines !== false) {

			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				array_push($boatListArray, $lineParts);
			}
		}

		return $boatListArray;
	}

	public function getMemberAmountBoats($memberId) {

		$boatCount = 0;
		$lines = @file("boatList.txt");
				
		if($lines !== false) {

			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[0] == $memberId) {
					$boatCount++;
				}

			}
		}

		return $boatCount;	
	}


	//Letar fram det antal båtar som den användare med flest båtar har
	public function getMaxBoatAmount() {

		$countArray = array();

		$lines = @file("boatList.txt");
				
		if($lines !== false) {

			foreach ($lines as $line) {

				$memberIdFound = false;

				$line = trim($line);

				$lineParts = explode(":", $line);

				if(sizeof($countArray)>0) {
					foreach ($countArray as $key => $value) {

						if($lineParts[0] == $key) {
							$value++;
							$memberIdFound = true;
							$countArray[$key] = $value;
							break;
						}

					}

					if($memberIdFound == false) {

						$countArray[$lineParts[0]] = 1;
					}
				} else {

					$countArray[$lineParts[0]] = 1;
				
				}

			}

			$lastKey = 0;
			$lastValue = 0;

			foreach ($countArray as $key => $value) {
				if($lastKey != 0 && $lastValue < $value) {
					$lastKey = $key;
					$lastValue = $value;
				} elseif ($lastKey == 0) {
					$lastKey = $key;
					$lastValue = $value;
				}
			}
		}

		return $lastValue;	
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

				if($lineParts[2] == $personalId){
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

				if($lineParts[3] == $memberId){
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


	public function editBoat($boatId, $boatType, $boatLength) {

		$lineParts;

		$newArray = array();

		$lines = @file("boatList.txt");
			
		if($lines !== false) {
			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[3] == $boatId){
					$line = $lineParts[0] . ":" . $boatType . ":" . $boatLength . ":" . $lineParts[3];
				}
				
				array_push($newArray, $line);
			}
		}

		$file2 = fopen('boatList.txt', 'w');

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
				}
			}
		}

		$file2 = fopen('members.txt', 'w');

		foreach ($newArray as $key => $value) {
			fwrite($file2, $value . "\n");
		}
	}

	public function deleteBoat($boatId) {

		$lineParts;

		$newArray = array();

		$lines = @file("boatList.txt");
			
		if($lines !== false) {
			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[3] != $boatId){
					array_push($newArray, $line);
				}
			}
		}

		$file2 = fopen('boatList.txt', 'w');

		foreach ($newArray as $key => $value) {
			fwrite($file2, $value . "\n");
		}
	}

	

	

	public function getSpecificBoatData($boatListId) {

		//Iterera igenom btlistan
		$lines = @file("boatList.txt");
				
		if($lines !== false) {

			//var_dump($lines);
			foreach ($lines as $line) {
				$line = trim($line);
				$lineParts = explode(":", $line);

				if($lineParts[3] == $boatListId) {
					$lineParts;
					return $lineParts;
				}
			}
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

				array_push($memberListArray, $lineParts);
			}
		}

		return $memberListArray;
	}

	public function getMemberBoatsListArray($memberId) {

		$memberBoats = array();

		$lines = @file("boatList.txt");
					
		if($lines !== false) {

			foreach ($lines as $line) {

				$memberIdFound = false;

				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[0] == $memberId) {
					array_push($memberBoats, $lineParts);
				}
			}
		}

		return $memberBoats;
	}
}