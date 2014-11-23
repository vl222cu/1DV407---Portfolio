<?php

class BoatObject {
	
	public $boatLength;
	public $boatType;

	public function __construct($boatType, $boatLength) {
		
		$this->boatType = $boatType;
		$this->boatLength = $boatLength;
	}
}