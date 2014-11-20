<?php

class Member {

	public $first_name;
	public $last_name;
	public $personal_number;
	public $member_id;
	public $boatList = array();

	public function __construct() {

	}

	public function toArray() {

		$retArr = array("member_id" => $this->member_id, "first_name" => $this->first_name, "last_name" => $this->last_name, "personal_number" => $this->personal_number, "boatList" => null );

		return $retArr;

	}

}