<?php

require_once("BoatObject.php");

class BoatModel {

	public $fileName = "membersRegister.json";
	public static $boatList = "boatList";

	function getBoatsListFromMembers($membersList) {

		$boatsArray = array();
		$count = 0;

		foreach ($membersList as $member_id => $member) {

			foreach ($member as $key => $property) {

				if($key == self::$boatList && $property != null){
					
					foreach ($property as $key2 => $value) {

						$boatObject = new BoatObject($value->boatType, $value->boatLength);
						$boatObject->member_id = $member_id;
						$boatsArray[$count] = $boatObject;
						
						$count++;
					}
				}
			}
		}

		return $boatsArray;
	}


	function createBoatObject($boatType, $boatLength) {

		return new BoatObject($boatType, $boatLength);
	}


	function addBoatToMemberList($member, $membersList, $boatObject) {

		if(!is_array($member->boatList)) {

			$member->boatList = array();
		}

		array_push($member->boatList, $boatObject);

		$newList = array();

		foreach ($membersList as $memberInList) {

			if($memberInList->member_id == $member->member_id) {

				array_push($newList, $member);

			} else {

				array_push($newList, $memberInList);
			}
		}

		return $newList;
	}

	function updateBoatInMembersList($membersList, $boatList, $boatId, $boatType, $boatLength, $memberId) {
		$updatedBoatList = array();

			foreach ($boatList as $id => $boat) {

				$updatedBoat;

				if($id == $boatId) {

					$updatedBoat = new boatObject($boatType, $boatLength);
					$updatedBoat->member_id = $boat->member_id;

				} else {

					$updatedBoat = $boat;
				}

				array_push($updatedBoatList, $updatedBoat);
			}


			$updatedMembersList = array();

			foreach ($membersList as $key => $member) {

				$member->boatList = array();
			}


			//Sätt in var och en av båtarna på rätt medlemsobjekt
			foreach ($updatedBoatList as $id => $boat) {

				foreach ($membersList as $member_id => $member) {
					
					if($boat->member_id == $member_id) {

						$newBoat = new BoatObject($boat->boatType, $boat->boatLength);
						array_push($member->boatList, $newBoat);
					}
				}
			}

			return $membersList;
	}

	function deleteBoatInMembersList($membersList, $boatList, $boatId, $memberId) {
		$updatedBoatList = array();

			foreach ($boatList as $id => $boat) {

				if($id != $boatId) {

					array_push($updatedBoatList, $boat);
				}
			}


			$updatedMembersList = array();

			foreach ($membersList as $key => $member) {

				$member->boatList = array();
			}


			//Sätt in var och en av båtarna på rätt medlemsobjekt
			foreach ($updatedBoatList as $id => $boat) {

				foreach ($membersList as $member_id => $member) {
					
					if($boat->member_id == $member_id) {

						array_push($member->boatList, $boat);
					}
				}
			}

			return $membersList;
	}

	function getMaxBoatAmount($membersList) {

		$highestAmount = 0;

		foreach ($membersList as $key => $member) {

			if(sizeof($member->boatList) > $highestAmount) {

				$highestAmount = sizeof($member->boatList);
			}
		}

		return $highestAmount;
	}
}