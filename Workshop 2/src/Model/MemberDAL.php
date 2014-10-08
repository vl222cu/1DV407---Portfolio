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
                                                            (FirstName, LastName, SocialSecurityNO)
                                                            VALUES ('$firstname', '$lastname', '$personalnumber')");

            $this->dbConnection->close();

           if($sqlInsert) {

                return true;

            } else {

                return false;
            } 
        }
    }

}