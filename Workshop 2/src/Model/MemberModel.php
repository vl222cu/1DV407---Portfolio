<?php

require_once("MemberDAL.php");

class MemberModel {

	private $member;
	private $firstName;
	private $lastName;
	private $memberIsRegistered;
	private $memberIsAuthenticated;
	private $dbQuery;

	public function __construct() {

		$this->member = null;
		$this->firstName = null;
		$this->lastName = null;
		$this->memberIsRegistered = false;
		$this->memberIsAuthenticated = false;
		$this->dbQuery = new MemberDAL();

	}

	public function authenticateMemberData ($firstName, $lastName, $personalNumber) {



	}

}