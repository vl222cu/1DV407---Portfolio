<?php

class BoatModel {

	public $fileName = "membersRegister.json";
	public static $jsonBoatType = "BoatType";
	public static $jsonBoatLength = "BoatLength";
	public static $jsonMemberBoats = "MemberBoats";
	public static $ownersName = "Owners_name";
	public static $ownersBoatType = "Boat_Type";
	public static $ownersBoatLength = "Boat_Length";
	public static $ownersBoatId = "Boat_Id";

	function saveBoatToFile($memberId, $boatType, $boatLength) {
		$memberId = (int) $memberId;
		$memberIdStr = MemberModel::$jsonMemberIdTracker . $memberId;
		$boatArray = array(self::$jsonBoatType => $boatType, self::$jsonBoatLength => $boatLength);
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
				if($value[self::$jsonMemberBoats] != NULL) {
					foreach ($value[self::$jsonMemberBoats] as $key => $boat) {
						if($boat != null) {
							$arrayToReturn = array(); 
							$arrayToReturn[MemberModel::$jsonMemberId] = $value[MemberModel::$jsonMemberId];
							$arrayToReturn[self::$ownersName] = $value[MemberModel::$jsonFirstName] . " " . $value[MemberModel::$jsonLastName];
							$arrayToReturn[self::$ownersBoatType] = $boat[self::$jsonBoatType];
							$arrayToReturn[self::$ownersBoatLength] = $boat[self::$jsonBoatLength];
							$arrayToReturn[self::$ownersBoatId] = $key;
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
				if($value[MemberModel::$jsonMemberId] == $memberId) {
					if($value[self::$jsonMemberBoats] != null) {
						$boatCount = 0;
						foreach ($value[self::$jsonMemberBoats] as $key => $value) {
							if($value != null) {
								$boatCount++;
							}
						}
						return $boatCount;

						//return sizeof($value[self::$jsonMemberBoats]);
					} else {
						if($key != MemberModel::$jsonHighestBoatId) {
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
				if($value[self::$jsonMemberBoats] != null) {
					foreach ($value[self::$jsonMemberBoats] as $key => $boat) {
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
				if($member[self::$jsonMemberBoats] != null) {
					foreach ($member[self::$jsonMemberBoats] as $key2 => $property) {
						if($key2 == $boatId) {
							$property[self::$jsonBoatType] = $boatType;
							$property[self::$jsonBoatLength] = $boatLength;
						}
						$member[self::$jsonMemberBoats][$key2] = $property;
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
				if($member[self::$jsonMemberBoats] != null) {
					foreach ($member[self::$jsonMemberBoats] as $key2 => $property) {
						if($key2 == $boatId) {
							$property = null;
						}
						$member[self::$jsonMemberBoats][$key2] = $property;
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
				if($member[self::$jsonMemberBoats] != null) {
					foreach ($member[self::$jsonMemberBoats] as $key2 => $property) {
						if($key2 == $boatId) {
							$boatArray[self::$jsonBoatType] = $property[self::$jsonBoatType];
							$boatArray[self::$jsonBoatLength] = $property[self::$jsonBoatLength];
							return $boatArray;
						}
					}
				}
			}
		}
	}

	function getMemberBoatsListArray($memberId) {
		$json_data = $this->getRegisterJson();
		$memberIdStr = MemberModel::$jsonMemberIdTracker . $memberId;
		if($json_data != null) {
			$decodedJson = json_decode($json_data, true);
			$boatsArray = array();
			if($decodedJson[$memberIdStr][self::$jsonMemberBoats] != null) {
				foreach ($decodedJson[$memberIdStr][self::$jsonMemberBoats] as $key => $value) {
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