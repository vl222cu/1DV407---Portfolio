<?php

require_once("src/Model/MemberModel.php");
require_once("src/View/MemberView.php");
require_once("src/Model/BoatModel.php");

class MemberController {

	private $memberModel;
	private $memberView;
	private $boatModel;

	public function __construct() {

		$this->memberModel = new MemberModel();
		$this->memberView = new MemberView($this->memberModel);
		$this->boeatModel = new BoatModel();

	}

	public function doCheckRegistration() {

		$userAction = $this->memberView->getUserAction();

		if($userAction === "registerpage") {

			return $this->registerMemberPage();

		} elseif ($userAction === "register") {
			
			$validateMemberRegistration = $this->memberModel->authenticateMemberData($this->memberView->getMemberRegisteredFirstName(), 
				$this->memberView->getMemberRegisteredLastName(), $this->memberView->getMemberRegisteredPersonalNumber());

			if ($validateMemberRegistration == true) {

				$this->memberView->setMessage(MemberView::MESSAGE_SUCCESS_REGISTRATION);
				return $this->mainMenuPage();

			} else {
				$this->memberView->setMessage(MemberView::MESSAGE_ERROR_REGISTRATION);
				return $this->registerMemberPage();
			
			}
			
		} elseif ($userAction === "return") {
			
			return $this->mainMenuPage();

		} elseif ($userAction === "changedatapage") {
			
			return $this->changeMemberDataPage();

		} elseif ($userAction === "change") {
			
			$validateMember = $this->memberModel->checkIfCurrentMemberExists($this->memberView->getMemberRegisteredPersonalNumber());

			if($validateMember == true) {

				return $this->MemberDataToBeChangedPage();

			}

		} elseif ($userAction === "addBoat") {

			return $this->addBoatPage();

		} elseif ($userAction === "editBoat") {

			return $this->editBoatPage();

		} elseif ($userAction === "deleteBoat") {

			return $this->deleteBoatPage();

		} elseif($userAction === "editChosenBoat") {


			$chosenBoatId =$this->memberView->getChosenBoatToEdit();

			return $this->editBoatPage($chosenBoatId);

		} else {

			return $this->mainMenuPage();
		}
	}

	private function mainMenuPage() {

		$this->memberView->setBody($this->memberView->mainMenuHTML());
		return $this->memberView->renderHTML();

	}

	private function registerMemberPage() {

		$this->memberView->setBody($this->memberView->registerHTML());
		return $this->memberView->renderHTML();

	}

	private function changeMemberDataPage() {

		$this->memberView->setBody($this->memberView->changeMemberDataHTML());
		return $this->memberView->renderHTML();

	}

	private function MemberDataToBeChangedPage() {

		$this->memberView->setBody($this->memberView->MemberDataToBeChangedHTML());
		return $this->memberView->renderHTML();

	}




	private function addBoatPage() {

		$this->memberView->setBody($this->memberView->addBoatHTML());
		return $this->memberView->renderHTML();
	}

	private function editBoatPage() {

		$boatList = $this->boatModel->getBoatListHTML();
		$this->memberView->setBody($this->memberView->editBoatHTML($boatList));
		return $this->memberView->renderHTML();
	}

	private function deleteBoatPage() {

		$boatList = $this->boatModel->getBoatList();
		$this->memberView->setBody($this->memberView->deleteBoatHTML($boatList));
		return $this->memberView->renderHTML();
	}
}