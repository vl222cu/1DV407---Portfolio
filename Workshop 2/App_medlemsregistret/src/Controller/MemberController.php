<?php

require_once("src/View/MemberView.php");
require_once("src/Model/BoatModel.php");
require_once("src/Model/MemberModel.php");


class MemberController {

	private $memberView;
	private $memberModel;
	private $boatModel;

	public function __construct() {

		$this->boatModel = new BoatModel();
		$this->memberModel = new MemberModel();
		$this->memberView = new MemberView($this->boatModel, $this->memberModel);
		
	}

	public function doCheckRegistration() {

		$userAction = $this->memberView->getUserAction();

		if($userAction === MemberView::$actionRegisterPage) {

			return $this->registerMemberPage();

		} elseif ($userAction === MemberView::$actionRegister) {

			if($this->memberModel->validateNewMember($this->memberView->getMemberRegisteredPersonalNumber())) {
				$this->memberModel->saveMemberToFile($this->memberView->getMemberRegisteredFirstName(), 
				$this->memberView->getMemberRegisteredLastName(), $this->memberView->getMemberRegisteredPersonalNumber());
				$this->memberView->setMessage(MemberView::MESSAGE_SUCCESS_REGISTRATION);
				return $this->mainMenuPage();
			} else {
				$this->memberView->setMessage(MemberView::MESSAGE_ERROR_REGISTRATION);
				return $this->registerMemberPage();
			}

		} elseif ($userAction === MemberView::$actionReturn) {
			
			return $this->mainMenuPage();

		} elseif ($userAction === MemberView::$actionChangeDataPage) {
			
			return $this->choseMemberDataPage();

		} elseif ($userAction === MemberView::$actionEdit) {
			
			if($this->memberModel->validateNewMember($this->memberView->getMemberRegisteredPersonalNumber()) === false && ($this->memberView->getMemberRegisteredPersonalNumber() != "null")) {
				$memberArray = $this->memberModel->getSpecificMember($this->memberView->getMemberRegisteredPersonalNumber());
				return $this->changeMemberDataPage($memberArray[MemberModel::$jsonFirstName], $memberArray[MemberModel::$jsonLastName], $memberArray[MemberModel::$jsonPersonalId], $memberArray[MemberModel::$jsonMemberId]);
			} else {
				//Användaren fanns inte med i listan
				$this->memberView->setMessage(MemberView::MESSAGE_USER_NOT_EXIST);
				return $this->choseMemberDataPage();
			}

		} elseif($userAction === MemberView::$actionSaveEditMember) {

			//Någon form av validering här?

			$this->memberModel->editMemberData($this->memberView->getMemberRegisteredFirstName(), 
				$this->memberView->getMemberRegisteredLastName(), $this->memberView->getMemberRegisteredPersonalNumber(), $this->memberView->getPostedMemberId());

			$this->memberView->setMessage(MemberView::MESSAGE_SUCESS_CHANGE_MEMBER);
			return $this->mainMenuPage();

		} elseif ($userAction === MemberView::$actionDeleteMember) {

			return $this->deleteMemberPage();

		} elseif ($userAction === MemberView::$actionMemberComfirmedDelete) {

			$this->memberModel->deleteMember($this->memberView->getPostedMemberId());
			$this->memberView->setMessage(MemberView::MESSAGE_USER_DELETED);
			return $this->mainMenuPage();

		} elseif ($userAction === MemberView::$actionAddBoat) {

			return $this->addBoatPage();

		} elseif ($userAction === MemberView::$actionEditBoat) {

			return $this->editBoatPage();

		} elseif ($userAction === MemberView::$actionDeleteBoat) {

			return $this->deleteBoatPage();

		} elseif($userAction === MemberView::$actionEditChosenBoat) {

			return $this->editBoatPage();

		} elseif ($userAction === MemberView::$actionSaveBoat) {

			$this->boatModel->saveBoatToFile($this->memberView->getPostedMemberId(), $this->memberView->getPostedBoatType(), $this->memberView->getPostedLength());
			$this->memberView->setMessage(MemberView::MESSAGE_SUCCESS_REGISTRATION);
			return $this->mainMenuPage();

		} elseif($userAction === MemberView::$actionSaveEditedBoat) {

			$boatId = $this->memberView->getSessionPostedBoatListId();
			$boatType = $this->memberView->getPostedBoatType();
			$boatLength = $this->memberView->getPostedLength();

			$this->boatModel->editBoat($boatId, $boatType, $boatLength);
			$this->memberView->setMessage(MemberView::MESSAGE_SUCESS_CHANGE_BOAT);

			return $this->mainMenuPage();

		} elseif ($userAction === MemberView::$actionBoatComfirmedDelete) {

			$this->boatModel->deleteBoat($this->memberView->getPostedBoatId());
			$this->memberView->setMessage(MemberView::MESSAGE_BOAT_DELETED);
			return $this->mainMenuPage();

		} elseif($userAction === MemberView::$actionShowSpecificMember) {

			return $this->showSpecificMemberPage();

		} elseif($userAction === MemberView::$actionShowChosenMember) {

			$memberArray = $this->memberModel->getMemberFromFile($this->memberView->getPostedMemberId());
			return $this->showSpecificMemberPageChosen($memberArray[MemberModel::$jsonFirstName], $memberArray[MemberModel::$jsonLastName], $memberArray[MemberModel::$jsonPersonalId], $memberArray[MemberModel::$jsonMemberId]);

		} elseif($userAction === MemberView::$actionShowSimpleList) {

			return $this->showSimpleMembersList();

		} elseif($userAction === MemberView::$actionShowDetailedList) {

			return $this->showDetailedMembersList();

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

	private function choseMemberDataPage() {
		$this->memberView->setBody($this->memberView->choseMemberDataHTML());
		return $this->memberView->renderHTML();
	}

	private function changeMemberDataPage($firstname, $lastname, $personalnumber, $memberId) {
		$this->memberView->setOldPersonalNumber($personalnumber);
		$this->memberView->setBody($this->memberView->changeMemberDataHTML($firstname, $lastname, $personalnumber, $memberId));
		return $this->memberView->renderHTML();
	}

	private function deleteMemberPage() {
		$memberList = $this->memberView->getMemberListHTML();
		$this->memberView->setBody($this->memberView->deleteMemberHTML($memberList));
		return $this->memberView->renderHTML();
	}

	private function addBoatPage() {

		$memberList = $this->memberView->getMemberListHTML();

		$this->memberView->setBody($this->memberView->addBoatHTML($memberList));
		return $this->memberView->renderHTML();
	}

	private function editBoatPage() {

		$boatListId = $this->memberView->getPostedBoatId();

		if($boatListId != null) {
			$this->memberView->setSessionPostedBoatListId($boatListId);
			$boatDataArray = $this->boatModel->getSpecificBoatData($boatListId);
		} else {
			$boatDataArray = null;
		}

		$boatList = $this->memberView->getBoatList();
		$this->memberView->setBody($this->memberView->editBoatHTML($boatList, $boatDataArray));
		return $this->memberView->renderHTML();
	}

	private function deleteBoatPage() {

		$boatList = $this->memberView->getBoatList();
		$this->memberView->setBody($this->memberView->deleteBoatHTML($boatList));
		return $this->memberView->renderHTML();
	}

	private function showSpecificMemberPage() {

		$memberList = $this->memberView->getMemberListHTML();
		$this->memberView->setBody($this->memberView->showSpecificMemberPageHTML($memberList));
		return $this->memberView->renderHTML();
	}

	private function showSpecificMemberPageChosen($firstname, $lastname, $personalnumber, $memberId) {

		$memberBoatsListHTML = $this->memberView->getMemberBoatsListHTML($memberId);
		$memberList = $this->memberView->getMemberListHTML();
		$this->memberView->setBody($this->memberView->showSpecificMemberPageChosenHTML($firstname, $lastname, $personalnumber, $memberId, $memberList, $memberBoatsListHTML));
		return $this->memberView->renderHTML();
	}

	public function showSimpleMembersList() {

		$memberListing = $this->memberView->getSimpleMembersList();
		$this->memberView->setBody($this->memberView->SimpleMembersListHTML($memberListing));
		return $this->memberView->renderHTML();
	}


	public function showDetailedMembersList() {

		$maxBoatAmount = $this->boatModel->getMaxBoatAmount();
		$boatListHTML = $this->memberView->getBoatListHTML($maxBoatAmount);

		$memberListing = $this->memberView->getDetailedMembersList();
		$this->memberView->setBody($this->memberView->DetailedMembersListHTML($memberListing, $boatListHTML));
		return $this->memberView->renderHTML();
	}

}