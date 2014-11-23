<?php

class Member {

	public $first_name;
	public $last_name;
	public $personal_number;
	public $member_id;
	public $boatList = array();
	private static $memberId = "member_id";
	private static $firstName = "first_name";
	private static $lastName = "last_name";
	private static $personalNumber = "personal_number";

	public function __construct() {

	}

	public function toArray() {

		$retArr = array(self::$memberId => $this->member_id, self::$firstName => $this->first_name, self::$lastName => $this->last_name, self::$personalNumber => $this->personal_number, BoatModel::$boatList => null );

		return $retArr;

	}

}