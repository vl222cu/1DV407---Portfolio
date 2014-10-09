<?php

require_once("BoatDAL.php");

class BoatModel {

	private $dbQuery;

	public function __construct() {

		$this->dbQuery = new BoatDAL();
	}

	public function getBoatListHTML() {
		//Ska returnera array med båtar - Format: "Ägare", "Typ", "Längd"
		$boatListArray = $this->dbQuery->getListOfBoats();

		$boatListHTML = "<option selected>Välj båt</option>\n";

		foreach($boatListArray as $key => $value) {

			$boatOwner = $value[0];
			$boatType = $value[1];
			$boatLength = $value[2];
			$boatId = $value[3];

			$boatListHTML .= "<option value='$boatId'>Ägare: $boatOwner - Båttyp: $boatType - Längd: $boatLength</option>\n";
		}

		return $boatListHTML;
	}

	public function getBoatArray() {
		return $this->dbQuery->getSpecificBoatData();
	}

	public function addBoat($boatOwner, $boatType, $boatLength) {
		$this->dbQuery->addBoatToDB($boatOwner, $boatType, $boatLength);
	}

}