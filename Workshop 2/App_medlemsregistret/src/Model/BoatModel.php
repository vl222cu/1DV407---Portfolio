<?php

class BoatModel {

	public function saveBoatToFile($memberId, $boatType, $boatLength) {

		$boatId = 1;

		$lines = @file("boatList.txt");
			
			if($lines === false) {
				//Do nothing
			} else {
				foreach ($lines as $line) {
					$line = trim($line);

					$lineParts = explode(":", $line);

					$boatId = $lineParts[3];

					$boatId++;
				}
			}

		$file = fopen('boatList.txt', 'a');
		fwrite($file, ($memberId . ":" . $boatType . ":" . $boatLength . ":" . $boatId . "\n"));
	}

	public function getBoatListArray() {

		$memberIds = array();
		$memberExists = false;

		//get existing member ids
		$lines = @file("members.txt");
				
		if($lines !== false) {

			foreach ($lines as $line) {
				$line = trim($line);
				$lineParts = explode(":", $line);

				if($lineParts[2] != "null") {
					array_push($memberIds, $lineParts[3]);
				}
			}
		}

		$file = fopen('members.txt', 'a');

		$boatListArray = array();
		$lines = @file("boatList.txt");
				
		if($lines !== false) {

			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				if($lineParts[2] != "null") {

					foreach ($memberIds as $key => $value) {
						if($value == $lineParts[0]) {
							$memberExists = true;
						}
					}

					if($memberExists == true) {
						array_push($boatListArray, $lineParts);
					}

					$memberExists = false;
				}
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
				} else {
					array_push($newArray, "null:null:null:" . $boatId);
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