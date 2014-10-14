<?php

class BoatModel {

	public function saveBoatToFile($memberId, $boatType, $boatLength) {

		$boatId = 1;

		$lines = @file("boatList.txt");
			
			if($lines === false) {
				//Do nothing
			} else {
				foreach ($lines as $line) {
					$line = trim($line);

					$lineParts = explode(":", $line);

					$boatId = $lineParts[3];

					$boatId++;
				}
			}

		$file = fopen('boatList.txt', 'a');
		fwrite($file, ($memberId . ":" . $boatType . ":" . $boatLength . ":" . $boatId . "\n"));
	}

}