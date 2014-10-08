<?php

require_once("src/Model/MemberModel.php");
require_once("src/View/MemberView.php");

class MemberController {

	private $memberModel;
	private $memberView;

	public function __construct() {

		$this->memberModel = new MemberModel();
		$this->memberView = new MemberView($this->memberModel);

	}

	public function doCheckRegistration() {

		$userAction = $this->memberView->getUserAction();

		if($userAction === "registerpage") {

			return $this->memberView->registerHTML();

		} elseif ($userAction === "return") {
			
			return $this->memberView->mainMenuHTML();

		} elseif ($userAction === "changedatapage") {
			
			return $this->memberView->changeMemberDataHTML();

		} else {

			return $this->memberView->mainMenuHTML();
		}

	}
}