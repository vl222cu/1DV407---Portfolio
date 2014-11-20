<?php

require_once("Member.php");

class MemberModel {

	public $fileName = "membersRegister.json";
	public static $jsonFirstName = "first_name";
	public static $jsonLastName = "last_name";
	public static $jsonPersonalId = "personal_id";
	public static $jsonMemberId = "member_id";
	public static $jsonHighestBoatId = "highestBoatId";
	public static $jsonMemberIdTracker = "memberId";

	function saveMemberToFile($firstName, $lastName, $personalId) {
		$personalId = (string) $personalId;
		if(empty($firstName)) {
			return false;
		} elseif (empty($lastName)) {
			
			return false;
		} elseif ($firstName && $lastName && $personalId !== null) {
			//Hämta jsonfilen
			$json_data = $this->getRegisterJson();
			if($json_data != null) {
				$decodedJson = json_decode($json_data, true);
				$newMemberId = $this->newIdGenerator($json_data);
				$newMemberIdStr = self::$jsonMemberIdTracker . $newMemberId;
				//Skapa ny medlem och lägg till
				$newMemberArray = array(self::$jsonMemberId => $newMemberId, self::$jsonFirstName => $firstName, self::$jsonLastName => $lastName, self::$jsonPersonalId => $personalId, "MemberBoats" => null);
				$decodedJson[$newMemberIdStr] = $newMemberArray;
				//$decodedJson['highestBoatId'] = 0;
				$newMemberJsonStr = (string) json_encode($decodedJson, JSON_PRETTY_PRINT);
				//Skriv till fil
				$myfile = fopen($this->fileName, "w");
				fwrite($myfile, $newMemberJsonStr);
			} else {
				//Skapa array för medlem
				$memeberProperties = array(self::$jsonMemberId => 1, self::$jsonFirstName => $firstName, self::$jsonLastName => $lastName, self::$jsonPersonalId => $personalId, "MemberBoats" => null);
				$member = array(self::$jsonHighestBoatId => 0, "memberId1" => $memeberProperties);
				$memberJsonStr = (string) json_encode($member, JSON_PRETTY_PRINT);
				$myfile = fopen($this->fileName, "w");
				fwrite($myfile, $memberJsonStr);
			}
		}
	}

	function newIdGenerator($json_data) {
		$decodedJson = json_decode($json_data);
		$lastNumber = 0;
		$highestNumber = 0;
		foreach ($decodedJson as $key => $value) {
			if($key != self::$jsonHighestBoatId && $value != null && $value->Member_Id > $lastNumber) {
				$highestNumber = $value->Member_Id;
			} elseif ($value == null) {
				$highestNumber++;
			}
		}
		
		$highestNumber++;
		return $highestNumber;
	}	

	function deleteMember($memberId) {
		//Hämta jsonfilen
		$json_data = $this->getRegisterJson();
		$decodedJson = json_decode($json_data, true);
		$decodedJson[self::$jsonMemberIdTracker . $memberId] = null;
		$newMemberJsonStr = (string) json_encode($decodedJson, JSON_PRETTY_PRINT);
		//Skriv till fil
		$myfile = fopen($this->fileName, "w");
		fwrite($myfile, $newMemberJsonStr);
	}

	function editMemberData($newFirstName, $newLastName, $newPrsonalId, $memberId) {
		$newPrsonalId = (string) $newPrsonalId;
		$memberId = (int) $memberId;
		//Hämta jsonfilen
		$json_data = $this->getRegisterJson();
		$decodedJson = json_decode($json_data);
		$memberIdStr = self::$jsonMemberIdTracker . $memberId;
		$decodedJson->$memberIdStr->Member_Id = $memberId;
		$decodedJson->$memberIdStr->First_name = $newFirstName;
		$decodedJson->$memberIdStr->Last_name = $newLastName;
		$decodedJson->$memberIdStr->Personal_Id = $newPrsonalId;
		$newMemberJsonStr = (string) json_encode($decodedJson, JSON_PRETTY_PRINT);
		//Skriv till fil
		$myfile = fopen($this->fileName, "w");
		fwrite($myfile, $newMemberJsonStr);	
	}

	function getMemberListArray() {
		//Hämta jsonfilen
		$json_data = $this->getRegisterJson();
		$decodedJson = json_decode($json_data, true);
		$memberArray = array();
		foreach ($decodedJson as $key => $member) {
			if($member != null && $key != self::$jsonHighestBoatId) {
				array_push($memberArray, $member);
			}
		}
		return $memberArray;
	}

	function validateNewMember($personalId) {
		//Hämta jsonfilen
		$json_data = $this->getRegisterJson();
		if($json_data != null) {
			$decodedJson = json_decode($json_data);
			foreach ($decodedJson as $key => $object) {
				if($key != self::$jsonHighestBoatId && $object != null && $object->personal_number == $personalId) {
					return false;
				}
			}
		}
		return true;
	}

	function getMemberFromFile($memberId) {
		if($memberId != null) {
			$json_data = $this->getRegisterJson();
			$memberArray = array();
			if($json_data != null) {
				$decodedJson = json_decode($json_data, true);
				foreach ($decodedJson as $key => $member) {
					$memberIdStr = self::$jsonMemberIdTracker . $memberId;
					if($key == $memberIdStr){
						$memberArray[self::$jsonFirstName] = $member[self::$jsonFirstName];
						$memberArray[self::$jsonLastName] = $member[self::$jsonLastName];
						$memberArray[self::$jsonPersonalId] = $member[self::$jsonPersonalId];
						$memberArray[self::$jsonMemberId] = $member[self::$jsonMemberId];
					}
				}
				return $memberArray;
			}
		}
	}


	function getMemberFromFilePersonalNumber($personal_number) {




		if($personal_number != null) {
			$json_data = $this->getRegisterJson();
			$memberArray = array();
			if($json_data != null) {
				$decodedJson = json_decode($json_data, true);
				foreach ($decodedJson as $key => $member) {
					if($key == $memberIdStr){
						$memberArray[self::$jsonFirstName] = $member[self::$jsonFirstName];
						$memberArray[self::$jsonLastName] = $member[self::$jsonLastName];
						$memberArray[self::$jsonPersonalId] = $member[self::$jsonPersonalId];
						$memberArray[self::$jsonMemberId] = $member[self::$jsonMemberId];
					}
				}
				return $memberArray;
			}
		}
	}

	function createMemberObject($first_name, $last_name, $personal_number, $member_id) {
		$member = new Member();

		$member->first_name = $first_name;
		$member->last_name = $last_name;
		$member->personal_number = $personal_number;
		if($member_id == null) {
			$member->member_id = null;
		} else {
			$member->member_id = (int) $member_id;
		}

		return $member;
	}

	function getSpecificMember($personal_Id) {
		if($personal_Id != null) {
			$json_data = $this->getRegisterJson();
			$memberArray = array();
			if($json_data != null) {
				$decodedJson = json_decode($json_data, true);
				foreach ($decodedJson as $key => $member) {
					if($member[self::$jsonPersonalId] == $personal_Id) {
						$memberArray[self::$jsonFirstName] = $member[self::$jsonFirstName];
						$memberArray[self::$jsonLastName] = $member[self::$jsonLastName];
						$memberArray[self::$jsonPersonalId] = $member[self::$jsonPersonalId];
						$memberArray[self::$jsonMemberId] = $member[self::$jsonMemberId];
					}
				}
				return $memberArray;
			}
		}
	}	


	function getSpecificMemberMembersList($personal_number, $membersList) {
		foreach ($membersList as $member) {
			if($member->personal_number == $personal_number) {
				return $member;
			}
		}
	}

	function getSpecificMemberFromListMemberId($memberId, $membersList) {
		foreach ($membersList as $member) {
			if($member->member_id == $memberId) {
				return $member;
			}
		}
	}


	function getRegisterJson() {
		if(file_exists($this->fileName)){
			return file_get_contents($this->fileName);
		}
	}
}