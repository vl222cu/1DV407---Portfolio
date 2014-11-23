<?php

require_once("Member.php");
require_once("FileHandler.php");

class MemberModel {

	private $fileHandler;
	public $fileName = "membersRegister.json";
	public static $jsonHighestBoatId = "highestBoatId";

	public function __construct() {

		$this->fileHandler = new FileHandler();
	}

	function validateNewMember($personalId) {
		//Hämta jsonfilen
		$json_data = $this->fileHandler->getRegisterJson();

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
}