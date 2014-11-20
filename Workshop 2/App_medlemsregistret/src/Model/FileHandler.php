<?php

require_once("Member.php");

class FileHandler {

	private $fileName = "membersRegister.json";

	private $membersList = array();

	public function __construct() {

		
	}

	public function getMembersListFromFile() {

		$json_data = $this->getRegisterJson();

		$membersJson = json_decode($json_data);

		$count = 0;

		foreach ($membersJson as $key => $member) {

			$count++;

			$memberToList = new Member();
			$memberToList->first_name = $member->first_name;
			$memberToList->last_name = $member->last_name;
			$memberToList->personal_number = $member->personal_number;
			$memberToList->boatList = $member->boatList;
			$memberToList->member_id = $count;

			$memberToList->boatList = $member->boatList;

			array_push($this->membersList, $memberToList);
		}


		return $this->membersList;
	}

	function getRegisterJson() {
		if(file_exists($this->fileName)){
			return file_get_contents($this->fileName);
		}
	}

	function addMemberToFile($member) {

		$first_name = $member->first_name;
		$last_name = $member->last_name;
		$personal_number = (string) $member->personal_number;

		if(empty($first_name)) {
			return false;
		} elseif (empty($last_name)) {
			return false;
		} elseif ($first_name && $last_name && $personal_number !== null) {


			$memberArray = $member->toArray();

			$arrayToSave = array();

			$json_data = $this->getRegisterJson();

			if($json_data != null) {
				$arrayToSave = json_decode($json_data, true);
				array_push($arrayToSave, $memberArray);
			} else {
				array_push($arrayToSave, $memberArray);
			}


			$membersList = $this->getMembersListFromFile();

			$newMembersList = array();
			foreach ($membersList as $member_id => $memberInList) {
				array_push($newMembersList, $memberInList);
			}

			array_push($newMembersList, $member);

			$this->writeMembersListToFile($newMembersList);
		}
	}

	function updateMemberInFile($member) {
		$membersList = $this->getMembersListFromFile();

		$newMembersList = array();
		foreach ($membersList as $memberInList) {
			if($memberInList->member_id == $member->member_id ) {
				$memberInList->first_name = $member->first_name;
				$memberInList->last_name = $member->last_name;
				$memberInList->personal_number = $member->personal_number;
			}

			array_push($newMembersList, $memberInList);
		}

		$this->writeMembersListToFile($newMembersList);
	}

	function deleteMemberFromFile($member_id) {
		
		$membersList = $this->getMembersListFromFile();

		$newList = array();

		foreach ($membersList as $member) {
			if($member->member_id != $member_id) {
				array_push($newList, $member);
			}
		}

		$this->writeMembersListToFile($newList);
	}

	function writeMembersListToFile($membersList) {
		//var_dump($membersList);

		foreach ($membersList as $key => $member) {
			if($member != null) {
				$member->member_id = null;
			}
		}

		$newMemberJsonStr = (string) json_encode($membersList, JSON_PRETTY_PRINT);
		$myfile = fopen($this->fileName, "w");
		fwrite($myfile, $newMemberJsonStr);
	}
}



