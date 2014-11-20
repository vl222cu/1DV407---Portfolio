<?php

class BoatObject {
	
	public $boatLength;
	public $boatType;
	public $member_id;

	public function __construct($boatType, $boatLength) {
		$this->boatType = $boatType;
		$this->boatLength = $boatLength;
	}
}