<?php

require_once("MemberDAL.php");

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
//		$this->dbQuery = new MemberDAL();

	}

/*	public function authenticateMemberData ($firstName, $lastName, $personalNumber) {

		if(empty($firstName)) {

			return false;

		} elseif (empty($lastName)) {
			
			return false;

		} elseif (empty($personalNumber)) {
			
			return false;

		} elseif ($firstName && $lastName && $personalNumber !== null) {
			
			$ret = $this->dbQuery->setMemberCredentialsInDB($firstName, $lastName, $personalNumber);

			if($ret == true) {

				$this->memberIsRegistered = true;
				return true;

			} else {

				$this->memberIsRegistered = false;
				return false;
			}
		}
	} */

/*	public function checkIfCurrentMemberExists($personalNumber) {

		if ($personalNumber !== null) {

			$ret = $this->dbQuery->getMemberByPersonalNumberFromDB($personalNumber);

			if ($ret == true) {

				return true;

			} else {

				return false;

			}
		}

	} */

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

	public function getMemberListHTML() {
		//Ska returnera array med medlemmat - Format: "Förnamn", "Efternamn", "Personnummer", "Medlemmsnumemr"
		$memberListArray = array();

		$lineParts;

		$lines = @file("members.txt");
			
		if($lines === false) {
			//Do nothing
		} else {
			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				$lineParts[0];
				$lineParts[1];
				$lineParts[2];
				$lineParts[3];
				
				array_push($memberListArray, $lineParts);
			}
		}

		$memberListHTML = "<option selected>Välj medlem</option>\n";

		foreach($memberListArray as $key => $value) {

			$firstName = $value[0];
			$lastName = $value[1];
			$memberId = $value[3];
			$personalId = $value[2];

			$memberListHTML .= "<option value='$memberId'>$firstName $lastName - $personalId </option>\n";
		}

		return $memberListHTML;
	}

	public function getSimpleMembersList() {
		
		$memberListArray = array();
		$output = "<tr>";
		
		if (($handle = fopen("members.txt", "r")) !== false) {

			while (($data = fgetcsv($handle)) !== false) {

				$output .= "<tr>";
				foreach ($data as $value) {

					$value = trim($value);

					$lineParts = explode(":", $value);

					$lineParts[0];
					$lineParts[1];
					$lineParts[2];
					$lineParts[3];
				
					array_push($memberListArray, $lineParts);
				}

				$boatAmount = $this->getMemberAmountBoats($lineParts[3]);

				foreach($memberListArray as $key => $value) {

					$firstName = $value[0];
					$lastName = $value[1];
					$memberId = $value[3];
				}
				$output .= "<td>$memberId</td>";
				$output .= "<td>$firstName</td>";
				$output .= "<td>$lastName</td>";
				$output .= "<td>$boatAmount";
				$output .= "</tr>";

			}
			fclose($handle);
		}
		return $output;
	}

	public function getDetailedMembersList() {

		$memberListArray = array();

		//Iterera igenom boatList och får tillbaka array innehållandes array med alla båtar
		$boatListArray = $this->getBoatListArray();
		
		$output = "<tr>";

		if (($handle = fopen("members.txt", "r")) !== false) {

			while (($data = fgetcsv($handle)) !== false) {

				$output .= "<tr>";

				//Lägger till alla beståndsdelar av en medlem i ett array, som i sin tyr adderas till ett medlemsarray
				foreach ($data as $value) {

					$value = trim($value);

					$lineParts = explode(":", $value);

					$lineParts[0];
					$lineParts[1];
					$lineParts[2];
					$lineParts[3];
				
					array_push($memberListArray, $lineParts);
				}

				foreach($memberListArray as $key => $value) {

					$membersBoats = array();

					$firstName = $value[0];
					$lastName = $value[1];
					$personalId = $value[2];
					$memberId = $value[3];

					//Itererar igenom alla båtar för att hitta de båtar som tillhör den spsoecika medlemmern
					foreach ($boatListArray as $key2 => $value2) {

						$boat = $boatListArray[$key2];

						//Om båtradens värde är samma som aktuell medlems id, adderas ddata till array för denna medlems båtar
						//Detta helt enkelt sorterar de olika båtarna utifrån medlem.
						if($boat[0] == $memberId) {
							array_push($membersBoats, $boat);
						}
					}
				}

				$output .= "<td>$memberId</td>";
				$output .= "<td>$personalId</td>";
				$output .= "<td>$firstName</td>";
				$output .= "<td>$lastName</td>";

				//loopar ignom medlemsbåtar och skriver ut alla båtar om finns på de olika medlemmarna
				foreach ($membersBoats as $key => $value) {
					$boatType = $value[1];
					$boatLength = $value[2];
					$output.="<td>Typ: $boatType Längd: $boatLength cm</td>";
				}

				$output .= "</tr>";
			}
			fclose($handle);
		}
		return $output;
	}

	public function getBoatList() {
		
		//Ska returnera array med båtar - Format: "Medlem", "Typ", "Längd"
		$boatListArray = array();

		$lineParts;

		$lines = @file("boatList.txt");
			
		if($lines === false) {
			//Do nothing
		} else {
			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				$lineParts[0];
				$lineParts[1];
				$lineParts[2];
				$lineParts[3];
				
				array_push($boatListArray, $lineParts);
			}
		}

		$boatListHTML = "<option selected>Välj båt</option>\n";

		foreach($boatListArray as $key => $value) {

			$boatOwner = $value[0];
			$boatType = $value[1];
			$boatLength = $value[2];
			$boatId = $value[3];

			$boatListHTML .= "<option value='$boatId'>Medlem: $boatOwner - Båttyp: $boatType - Längd: $boatLength</option>\n";
		}

		return $boatListHTML;
	}

	public function getBoatListArray() {

		$boatListArray = array();
		$lines = @file("boatList.txt");
				
		if($lines !== false) {

			foreach ($lines as $line) {
				$line = trim($line);

				$lineParts = explode(":", $line);

				$lineParts[0];
				$lineParts[1];
				$lineParts[2];

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

				$lineParts[0];
				$lineParts[1];
				$lineParts[2];

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

				$lineParts[0];
				$lineParts[1];
				$lineParts[2];

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

				$lineParts[0];
				$lineParts[1];
				$lineParts[2];
				$lineParts[3];

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

				$lineParts[0];
				$lineParts[1];
				$lineParts[2];
				$lineParts[3];

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

				$lineParts[0];
				$lineParts[1];
				$lineParts[2];
				$lineParts[3];

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

				$lineParts[0];
				$lineParts[1];
				$lineParts[2];
				$lineParts[3];

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

				$lineParts[0];
				$lineParts[1];
				$lineParts[2];
				$lineParts[3];

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

	public function getBoatListHTML($maxBoatAmount) {

		$ret = "";

		for ($i=0; $i<$maxBoatAmount; $i++) {

			$boatNumber = $i + 1;

			$ret .="<th>Båt $boatNumber</th>\n";
		}

		return $ret;

	}

	public function getMemberBoatsListHTML($memberId) {
		$ret = "";

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

		foreach ($memberBoats as $key => $value) {

			$boatNumber = $key + 1;

			$ret .= "<p><strong>Båt $boatNumber:</strong> Typ: $value[1] - Längd: $value[2] cm</p>";
		}

		return $ret;
	}

	public function getSpecificBoatData($boatListId) {

		//Iterera igenom btlistan
		$lines = @file("boatList.txt");
				
		if($lines !== false) {

			foreach ($lines as $line) {

				$line = trim($line);
				$lineParts = explode(":", $line);

				if($lineParts[3] == $boatListId) {
					return $lineParts;
				}
			}
		}
	}
}