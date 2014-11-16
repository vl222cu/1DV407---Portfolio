<?php

class MemberModel {

	public $fileName = "membersRegister.json";

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

				$newMemberIdStr = "memberId" . $newMemberId;;

				//Skapa ny medelem och lägg till
				$newMemberArray = array("Member_Id" => $newMemberId, "First_name" => $firstName, "Last_name" => $lastName, "Personal_Id" => $personalId, "MemberBoats" => null);
				$decodedJson[$newMemberIdStr] = $newMemberArray;
				//$decodedJson['highestBoatId'] = 0;
				$newMemberJsonStr = (string) json_encode($decodedJson, JSON_PRETTY_PRINT);

				//Skriv till fil
				$myfile = fopen($this->fileName, "w");
				fwrite($myfile, $newMemberJsonStr);

			} else {

				//Skapa array för medlemm
				$memeberProperties = array("Member_Id" => 1, "First_name" => $firstName, "Last_name" => $lastName, "Personal_Id" => $personalId, "MemberBoats" => null);

				$member = array("highestBoatId" => 0, "memberId1" => $memeberProperties);

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

			if($key != "highestBoatId" && $value != null && $value->Member_Id > $lastNumber) {
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

		$decodedJson["memberId" . $memberId] = null;

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

		$memberIdStr = "memberId" . $memberId;

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
			if($member != null && $key != "highestBoatId") {
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
				if($key != "highestBoatId" && $object != null && $object->Personal_Id == $personalId) {
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

					$memberIdStr = "memberId" . $memberId;

					if($key == $memberIdStr){
						$memberArray['First_name'] = $member['First_name'];
						$memberArray['Last_name'] = $member['Last_name'];
						$memberArray['Personal_Id'] = $member['Personal_Id'];
						$memberArray['Member_Id'] = $member['Member_Id'];
					}
				}

				return $memberArray;
			}
		}
	}



	function getSpecificMember($personal_Id) {

		if($personal_Id != null) {

			$json_data = $this->getRegisterJson();

			$memberArray = array();

			if($json_data != null) {

				$decodedJson = json_decode($json_data, true);

				foreach ($decodedJson as $key => $member) {

					if($member['Personal_Id'] == $personal_Id) {
						$memberArray['First_name'] = $member['First_name'];
						$memberArray['Last_name'] = $member['Last_name'];
						$memberArray['Personal_Id'] = $member['Personal_Id'];
						$memberArray['Member_Id'] = $member['Member_Id'];
					}
				}

				return $memberArray;

			}
		}

	}	


	function getRegisterJson() {
		if(file_exists($this->fileName)){
			return file_get_contents($this->fileName);
		}
	}

}