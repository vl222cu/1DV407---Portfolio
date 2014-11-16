<?php

class BoatModel {


	public $fileName = "membersRegister.json";

	

	function saveBoatToFile($memberId, $boatType, $boatLength) {

		$memberId = (int) $memberId;

		$memberIdStr = "memberId" . $memberId;
		$boatArray = array("BoatType" => $boatType, "BoatLength" => $boatLength);

		//Hämta jsonfilen
		$json_data = $this->getRegisterJson();

		if($json_data != null) {

			$decodedJson = json_decode($json_data);

			$decodedJsonAssoc = json_decode($json_data, true);


			//gör check på max antal båtar.
			$highestBoatId = $decodedJson->highestBoatId;

			if($highestBoatId == null) {
				$decodedJson->highestBoatId = 0;			
			}

			$highestBoatId++;

			$thisBoatId = $highestBoatId;

			$decodedJson->highestBoatId = $thisBoatId;


			if($decodedJson->$memberIdStr->MemberBoats == null) {

				$decodedJson->$memberIdStr->MemberBoats = array($thisBoatId => $boatArray);

			} else {

				$array = array();
				foreach ($decodedJson->$memberIdStr->MemberBoats as $key => $value) {
					$array[$key] = $value;
				}

				$array[$thisBoatId] = $boatArray;
				$decodedJson->$memberIdStr->MemberBoats = $array;
			}

			$newMemberJsonStr = (string) json_encode($decodedJson, JSON_PRETTY_PRINT);

			//Skriv till fil
			$myfile = fopen($this->fileName, "w");
			fwrite($myfile, $newMemberJsonStr);
		}
	}


	function getBoatListArray() {

		//Hämta jsonfilen
		$json_data = $this->getRegisterJson();

		if($json_data != null) {

			$decodedJson = json_decode($json_data, true);

			$boatArray = array();

			foreach ($decodedJson as $value) {
				if($value['MemberBoats'] != NULL) {

					foreach ($value['MemberBoats'] as $key => $boat) {

						if($boat != null) {
							$arrayToReturn = array(); 

							$arrayToReturn['Member_Id'] = $value['Member_Id'];
							$arrayToReturn['Owners_name'] = $value['First_name'] . " " . $value['Last_name'];
							$arrayToReturn['Boat_Type'] = $boat['BoatType'];
							$arrayToReturn['Boat_Length'] = $boat['BoatLength'];
							$arrayToReturn['Boat_Id'] = $key;

							array_push($boatArray, $arrayToReturn);
						}
					}
				}
			}

			return $boatArray;
		}

		return false;
	}


	function getMemberAmountBoats($memberId) {

		//Hämta jsonfilen
		$json_data = $this->getRegisterJson();

		if($json_data != null) {

			$decodedJson = json_decode($json_data, true);

			foreach ($decodedJson as $key => $value) {
				if($value['Member_Id'] == $memberId) {
					if($value['MemberBoats'] != null) {
						return sizeof($value['MemberBoats']);
					} else {
						if($key != "highestBoatId") {
							return 0;
						} else {
							return null;
						}
					}
				}
			}
		} 
	}

	function getMaxBoatAmount() {

		//Hämta jsonfilen
		$json_data = $this->getRegisterJson();

		$highestAmount = 0;


		if($json_data != null) {

			$decodedJson = json_decode($json_data, true);

			$boatCount = 0;

			foreach ($decodedJson as $value) {
				if($value['MemberBoats'] != null) {

					foreach ($value['MemberBoats'] as $key => $boat) {
						if($boat != null) {
							$boatCount++;
						}
					}

					if($boatCount > $highestAmount) {
						$highestAmount = $boatCount;
					}
				}

				$boatCount = 0;
			}

			return $highestAmount;
		}
	}


	function editBoat($boatId, $boatType, $boatLength) {

		$json_data = $this->getRegisterJson();

		if($json_data != null) {
	
			$decodedJson = json_decode($json_data, true);

			foreach ($decodedJson as $key => $member) {

				$newMember = array();

				if($member['MemberBoats'] != null) {
					foreach ($member['MemberBoats'] as $key2 => $property) {
						if($key2 == $boatId) {

							$property['BoatType'] = $boatType;
							$property['BoatLength'] = $boatLength;
						}

						$member['MemberBoats'][$key2] = $property;
					}
				}

				$decodedJson[$key] = $member;
			}
		}

		$newMemberJsonStr = (string) json_encode($decodedJson, JSON_PRETTY_PRINT);

		$myfile = fopen($this->fileName, "w");
		fwrite($myfile, $newMemberJsonStr);

	}


	function deleteBoat($boatId) {

		$json_data = $this->getRegisterJson();

		if($json_data != null) {
	
			$decodedJson = json_decode($json_data, true);

			foreach ($decodedJson as $key => $member) {

				$newMember = array();

				if($member['MemberBoats'] != null) {
					foreach ($member['MemberBoats'] as $key2 => $property) {
						if($key2 == $boatId) {
							$property = null;
						}

						$member['MemberBoats'][$key2] = $property;
					}
				}

				$decodedJson[$key] = $member;
			}
		}

		$newMemberJsonStr = (string) json_encode($decodedJson, JSON_PRETTY_PRINT);

		$myfile = fopen($this->fileName, "w");
		fwrite($myfile, $newMemberJsonStr);
	}

	function getSpecificBoatData($boatId) {

		$json_data = $this->getRegisterJson();

		$boatIdStr = "$boatId";

		$boatArray = array();

		if($json_data != null) {
			$decodedJson = json_decode($json_data, true);

			foreach ($decodedJson as $key => $member) {
				if($member['MemberBoats'] != null) {
					foreach ($member['MemberBoats'] as $key2 => $property) {
						if($key2 == $boatId) {
							$boatArray["BoatType"] = $property['BoatType'];
							$boatArray["BoatLength"] = $property['BoatLength'];
							return $boatArray;
						}
					}
				}
			}
		}
	}


	function getMemberBoatsListArray($memberId) {

		$json_data = $this->getRegisterJson();

		$memberIdStr = "memberId" . $memberId;

		if($json_data != null) {
			$decodedJson = json_decode($json_data, true);

			$boatsArray = array();

			if($decodedJson[$memberIdStr]['MemberBoats'] != null) {
				foreach ($decodedJson[$memberIdStr]['MemberBoats'] as $key => $value) {
					//var_dump($value);
					if($value != null) {
						$boatsArray[$key] = $value;
					}
				}
			}
			if($boatsArray != null) {
				return $boatsArray;	
			}
		}
	}	


	function getRegisterJson() {
		if(file_exists($this->fileName)){
			return file_get_contents($this->fileName);
		}
	}


}