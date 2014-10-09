<?php

class BoatDAL {

    private $dbConnection;

	public function __construct() {

        $this->dbConnection = mysqli_connect("localhost", "root", "", "members");

        if(!$this->dbConnection) {

            die('Connectionfailure: ' . mysql_error());
        }
    }

    public function addBoatToDB($boatOwner, $boatType, $boatLength) {

        $sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO boatList
                                                        (BoatOwner, BoatType, BoatLength)
                                                        VALUES ('$boatOwner', '$boatType', '$boatLength')");

        $this->dbConnection->close();

       if($sqlInsert) {

            return true;

        } else {

            return false;
        } 
    }

    public function deleteBoat($boatId) {
        $sql = mysqli_query($this->dbConnection, "DELETE FROM boatList
                                                        WHERE boatId=$boatId");

    }

    public function getSpecificBoatData($boatId) {
        $boatData = mysqli_query($this->dbConnection, "SELECT *
                                                        FROM boatList
                                                        WHERE boatId=$boatId");

        return mysqli_fetch_array($boatData);
    }

    public function getListOfBoats() {

        $boatOwnerResult = mysqli_query($this->dbConnection, "SELECT boatOwner
                                                        FROM boatList");
        $boatTypeResult = mysqli_query($this->dbConnection, "SELECT boatType
                                                        FROM boatList");
        $boatLengthResult = mysqli_query($this->dbConnection, "SELECT boatLength
                                                        FROM boatList");
        $boatIdResult = mysqli_query($this->dbConnection, "SELECT boatId
                                                        FROM boatList");

        if($boatOwnerResult > 0) {

            $ownerArray = mysqli_fetch_array($boatOwnerResult);
            $typeArray = mysqli_fetch_array($boatTypeResult);
            $lengthArray = mysqli_fetch_array($boatLengthResult);
            $boatIdArray = mysqli_fetch_array($boatIdResult);

            array ($boatList);

            foreach ($ownerArray as $key => $value) {

                array ($boatRow);

            $boatList;

            foreach ($ownerArray as $key => $value) {

                $boatRow;

                array_push($boatRow, $ownerArray[$key]);
                array_push($boatRow, $typeArray[$key]);
                array_push($boatRow, $lenghtArray[$key]);
                array_push($boatRow, $boatIdArray[$key]);

                //$rowStr = "Ägare: " . ": " . $ownerArray[$key] . " - Båttyp: " . $typeArray[$key] . " - Längd: " . $lengthArray[$key] . " cm";

                array_push($boatList, $boatRow);
            }

            $this->dbConnection->close();

            return $boatList;

        } else {
            
            $this->dbConnection->close();
            return false;
        
        }
    }
}