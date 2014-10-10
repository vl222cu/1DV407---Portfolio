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
		$this->boatModel = new BoatModel();
		$this->memberView = new MemberView($this->memberModel, $this->boatModel);

	}

	public function doCheckRegistration() {

		$userAction = $this->memberView->getUserAction();

		if($userAction === "registerpage") {

			return $this->registerMemberPage();

		} elseif ($userAction === "register") {

			if($this->memberModel->validateNewMember($this->memberView->getMemberRegisteredPersonalNumber())) {
				$this->memberModel->saveMemberToFile($this->memberView->getMemberRegisteredFirstName(), 
				$this->memberView->getMemberRegisteredLastName(), $this->memberView->getMemberRegisteredPersonalNumber());
				$this->memberView->setMessage(MemberView::MESSAGE_SUCCESS_REGISTRATION);
				return $this->mainMenuPage();
			} else {
				$this->memberView->setMessage(MemberView::MESSAGE_ERROR_REGISTRATION);
				return $this->registerMemberPage();
			}

		} elseif ($userAction === "return") {
			
			return $this->mainMenuPage();

		} elseif ($userAction === "changedatapage") {
			
			return $this->choseMemberDataPage();

		} elseif ($userAction === "change") {
			
			if($this->memberModel->validateNewMember($this->memberView->getMemberRegisteredPersonalNumber()) === false) {
				$memberArray = $this->memberModel->getSpecificMember($this->memberView->getMemberRegisteredPersonalNumber());
				return $this->changeMemberDataPage($memberArray[0], $memberArray[1], $memberArray[2], $memberArray[3]);
			} else {
				//Användaren fanns inte med i listan
				$this->memberView->setMessage(MemberView::MESSAGE_USER_NOT_EXIST);
				return $this->choseMemberDataPage();
			}

		} elseif($userAction === "saveMemberChange") {

			//Någon form av validering här?

			$this->memberModel->changeMemberData($this->memberView->getMemberRegisteredFirstName(), 
				$this->memberView->getMemberRegisteredLastName(), $this->memberView->getMemberRegisteredPersonalNumber());

			$this->memberView->setMessage(MemberView::MESSAGE_SUCESS_CHANGE_MEMBER);
			return $this->mainMenuPage();

		} elseif ($userAction === "deleteMember") {

			return $this->deleteMemberPage();

		} elseif ($userAction === "memberConfirmedDelete") {

			$this->memberModel->deleteMember($this->memberView->getPostedMemberId());
			$this->memberView->setMessage(MemberView::MESSAGE_USER_DELETED);
			return $this->mainMenuPage();

		} elseif ($userAction === "addBoat") {

			return $this->addBoatPage();

		} elseif ($userAction === "editBoat") {

			return $this->editBoatPage();

		} elseif ($userAction === "deleteBoat") {

			return $this->deleteBoatPage();

		} elseif($userAction === "editChosenBoat") {

			return $this->editBoatPage();

		} elseif ($userAction === "saveBoat") {

			$this->boatModel->saveBoatToFile($this->memberView->getPostedMemberId(), $this->memberView->getPostedBoatType(), $this->memberView->getPostedLength());
			$this->memberView->setMessage(MemberView::MESSAGE_SUCCESS_REGISTRATION);
			return $this->mainMenuPage();

		} elseif($userAction === "showSpecificMember") {


			return $this->showSpecificMemberPage();

		} elseif($userAction ==="showMemberChosen") {

			$memberArray = $this->memberModel->getSpecificMemberMemberId($this->memberView->getPostedMemberId());
			return $this->showSpecificMemberPageChosen($memberArray[0], $memberArray[1], $memberArray[2], $memberArray[3]);

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

	private function changeMemberDataPage($firstname, $lastname, $personalnumber, $memberId) {

		$this->memberView->setBody($this->memberView->changeMemberDataHTML($firstname, $lastname, $personalnumber, $memberId));
		return $this->memberView->renderHTML();

	}

	private function choseMemberDataPage() {
		$this->memberView->setBody($this->memberView->choseMemberDataHTML());
		return $this->memberView->renderHTML();
	}

	private function MemberDataToBeChangedPage() {

		$this->memberView->setBody($this->memberView->MemberDataToBeChangedHTML());
		return $this->memberView->renderHTML();

	}

	private function deleteMemberPage() {
		$memberList = $this->memberModel->getMemberListHTML();
		$this->memberView->setBody($this->memberView->deleteMemberHTML($memberList));
		return $this->memberView->renderHTML();
	}




	private function addBoatPage() {

		$memberList = $this->memberModel->getMemberListHTML();

		$this->memberView->setBody($this->memberView->addBoatHTML($memberList));
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

	private function showSpecificMemberPage() {
		$memberList = $this->memberModel->getMemberListHTML();
		$this->memberView->setBody($this->memberView->showSpecificMemberPageHTML($memberList));
		return $this->memberView->renderHTML();
	}

	private function showSpecificMemberPageChosen($firstname, $lastname, $personalnumber, $memberId) {
		$memberList = $this->memberModel->getMemberListHTML();
		$this->memberView->setBody($this->memberView->showSpecificMemberPageChosenHTML($firstname, $lastname, $personalnumber, $memberId, $memberList));
		return $this->memberView->renderHTML();
	}
}