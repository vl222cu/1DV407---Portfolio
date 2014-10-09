<?php

class MemberDAL {

	private $dbConnection;

	public function __construct() {

        $this->dbConnection = mysqli_connect("localhost", "vivi", "mastimasti1", "members");

        if(!$this->dbConnection) {

            die('Connectionfailure: ' . mysql_error());
        }
    }

    public function setMemberCredentialsInDB($firstname, $lastname, $personalnumber) {

        $sqlQuery = mysqli_query($this->dbConnection, "SELECT SocialSecurityNO
                                                        FROM register
                                                        WHERE SocialSecurityNO = '$personalnumber'");

        if (mysqli_num_rows($sqlQuery) > 0) {

            return false;

        } else {

            $sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO register
                                                            (firstName, lastName, socialSecurityNO)
                                                            VALUES ('$firstname', '$lastname', '$personalnumber')");

            $this->dbConnection->close();

           if($sqlInsert) {

                return true;

            } else {

                return false;
            } 
        }
    }

    public function getMemberByPersonalNumberFromDB($personalnumber) {

        $result = mysqli_query($this->dbConnection, "SELECT *
                                                      FROM register
                                                      WHERE socialSecurityNumber = '$personalnumber'");

        $this->dbConnection->close();

        if(mysqli_num_rows($result) == 1) {
 
            return true;

        } else {

            return false;

        }
    }

    public function getMemberCredentialsFromDB($firstName, $lastName, $personalnumber) {

        $sqlQuery = mysqli_query($this->dbConnection, "SELECT memberID, firstName
                                                      , lastName, socialSecurityNumber
                                                      FROM register
                                                      WHERE socialSecurityNumber = '$personalnumber'");

        $this->dbConnection->close();

        if(mysqli_num_rows($sqlQuery) == 1) {
 
            return true;

        } else {

            return false;

        }

    }
}