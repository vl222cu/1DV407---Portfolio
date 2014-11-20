<?php

require_once("src/View/MemberView.php");
require_once("src/Model/BoatModel.php");
require_once("src/Model/MemberModel.php");
require_once("src/Model/FileHandler.php");


class MemberController {

	private $memberView;
	private $memberModel;
	private $boatModel;
	private $fileHandler;

	public function __construct() {

		$this->fileHandler = new FileHandler();
		$this->boatModel = new BoatModel();
		$this->memberModel = new MemberModel();
		$this->memberView = new MemberView($this->boatModel, $this->memberModel);
		
	}

	public function doCheckRegistration() {

		$userAction = $this->memberView->getUserAction();

		if($userAction === MemberView::$actionRegisterPage) {

			return $this->registerMemberPage();

		} elseif ($userAction === MemberView::$actionRegister) {

			$member = $this->memberModel->createMemberObject($this->memberView->getMemberRegisteredFirstName(), $this->memberView->getMemberRegisteredLastName(), $this->memberView->getMemberRegisteredPersonalNumber(), null);

			if($this->memberModel->validateNewMember($member->personal_number)) {

				$this->fileHandler->addMemberToFile($member);

				//$this->memberModel->saveMemberToFile($this->memberView->getMemberRegisteredFirstName(), 
				//$this->memberView->getMemberRegisteredLastName(), $this->memberView->getMemberRegisteredPersonalNumber());
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


			//if($this->memberModel->validateNewMember($this->memberView->getMemberRegisteredPersonalNumber()) === false && ($this->memberView->getMemberRegisteredPersonalNumber() != "null")) {
				
			$membersList = $this->fileHandler->getMembersListFromFile();

			//$this->memberModel->getMemberFromFilePersonalNumber($this->memberView->getMemberRegisteredPersonalNumber());

			$member = $this->memberModel->getSpecificMemberMembersList($this->memberView->getMemberRegisteredPersonalNumber(), $membersList);

			return $this->changeMemberDataPage($member);
			
		/*	} else {
				//Användaren fanns inte med i listan
				$this->memberView->setMessage(MemberView::MESSAGE_USER_NOT_EXIST);
				return $this->choseMemberDataPage();
			}
*/
		} elseif($userAction === MemberView::$actionSaveEditMember) {

			$member = $this->memberModel->createMemberObject($this->memberView->getMemberRegisteredFirstName(), $this->memberView->getMemberRegisteredLastName(), $this->memberView->getMemberRegisteredPersonalNumber(), $this->memberView->getMemberRegisteredMemberId());

			//if($this->memberModel->validateNewMember($member->personal_number)) {

			$this->fileHandler->updateMemberInFile($member);

			//$this->memberModel->saveMemberToFile($this->memberView->getMemberRegisteredFirstName(), 
			//$this->memberView->getMemberRegisteredLastName(), $this->memberView->getMemberRegisteredPersonalNumber());
			$this->memberView->setMessage(MemberView::MESSAGE_SUCCESS_REGISTRATION);
			return $this->mainMenuPage();
	/*		} else {
				$this->memberView->setMessage(MemberView::MESSAGE_ERROR_REGISTRATION);
				return $this->registerMemberPage();
			}
*/

			//Någon form av validering här?

			$this->memberView->setMessage(MemberView::MESSAGE_SUCESS_CHANGE_MEMBER);
			return $this->mainMenuPage();

		} elseif ($userAction === MemberView::$actionDeleteMember) {

			return $this->deleteMemberPage();

		} elseif ($userAction === MemberView::$actionMemberComfirmedDelete) {

			$this->fileHandler->deleteMemberFromFile($this->memberView->getPostedMemberId());
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

			$membersList = $this->fileHandler->getMembersListFromFile();

			$member = $this->memberModel->getSpecificMemberFromListMemberId($this->memberView->getMemberRegisteredMemberId(), $membersList);

			$boatObject = $this->boatModel->createBoatObject($this->memberView->getPostedBoatType(), $this->memberView->getPostedLength());

			$membersList = $this->boatModel->addBoatToMemberList($member, $membersList, $boatObject);

			$this->fileHandler->writeMembersListToFile($membersList);

			$this->memberView->setMessage(MemberView::MESSAGE_SUCCESS_REGISTRATION);
			return $this->mainMenuPage();

		} elseif($userAction === MemberView::$actionSaveEditedBoat) {

			//Hämta listan med båtar (som även har respektive medlemsId)
			$membersList = $this->fileHandler->getMembersListFromFile();
			$boatList = $this->boatModel->getBoatsListFromMembers($membersList);


			$boatId = $this->memberView->getSessionPostedBoatListId();
			$boatType = $this->memberView->getPostedBoatType();
			$boatLength = $this->memberView->getPostedLength();
			$memberId = $this->memberView->getPostedMemberId();

			$memberList = $this->boatModel->updateBoatInMembersList($membersList, $boatList, $boatId, $boatType, $boatLength, $memberId);
			
			$this->fileHandler->writeMembersListToFile($membersList);

			$this->memberView->setMessage(MemberView::MESSAGE_SUCESS_CHANGE_BOAT);

			return $this->mainMenuPage();

		} elseif ($userAction === MemberView::$actionBoatComfirmedDelete) {

			//Hämta listan med båtar (som även har respektive medlemsId)
			$membersList = $this->fileHandler->getMembersListFromFile();
			$boatList = $this->boatModel->getBoatsListFromMembers($membersList);

			$boatId = $this->memberView->getSessionPostedBoatListId();
			$memberId = $this->memberView->getPostedMemberId();

			$memberList = $this->boatModel->deleteBoatInMembersList($membersList, $boatList, $boatId, $memberId);
			
			$this->fileHandler->writeMembersListToFile($membersList);


			$this->memberView->setMessage(MemberView::MESSAGE_BOAT_DELETED);
			return $this->mainMenuPage();

		} elseif($userAction === MemberView::$actionShowSpecificMember) {

			return $this->showSpecificMemberPage();

		} elseif($userAction === MemberView::$actionShowChosenMember) {

			//Hämta alla medlemmar till medlemslista
			//Hämta ut medlem ur lista.

			//Presentera medlem med tillhörande båtar
		
			$membersList = $this->fileHandler->getMembersListFromFile();
			$member = $this->memberModel->getSpecificMemberFromListMemberId($this->memberView->getMemberRegisteredMemberId(), $membersList);



			//$memberArray = $this->memberModel->getMemberFromFile($this->memberView->getPostedMemberId());
			return $this->showSpecificMemberPageChosen($member, $membersList);

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

	private function changeMemberDataPage($member) {
		$this->memberView->setBody($this->memberView->changeMemberDataHTML($member));
		return $this->memberView->renderHTML();
	}

	private function deleteMemberPage() {
		$memberList = $this->memberView->getMemberListHTML($this->fileHandler->getMembersListFromFile());
		$this->memberView->setBody($this->memberView->deleteMemberHTML($memberList));
		return $this->memberView->renderHTML();
	}

	private function addBoatPage() {

		$memberList = $this->memberView->getMemberListHTML($this->fileHandler->getMembersListFromFile());

		$this->memberView->setBody($this->memberView->addBoatHTML($memberList));
		return $this->memberView->renderHTML();
	}

	private function editBoatPage() {

		//Hämta lista med medlemmar från fil
		//Hämta ut alla båtar från alla medlemmar
		//Visa alla båtar för medlemmar

		$membersList = $this->fileHandler->getMembersListFromFile();

		$boatsList = $this->boatModel->getBoatsListFromMembers($membersList);

		$boatsListHTML = $this->memberView->getBoatList($boatsList, $membersList);

		$boatListId = $this->memberView->getPostedBoatId();


		if($boatListId != null) {
			$this->memberView->setSessionPostedBoatListId($boatListId);
			//$boatDataArray = $this->boatModel->getSpecificBoatData($boatListId, $boatsList);
			$this->memberView->setBody($this->memberView->editBoatHTML($boatsList, $boatListId, $boatsListHTML));
		} else {
			//$boatDataArray = null;
			$this->memberView->setBody($this->memberView->editBoatHTML(null, $boatListId, $boatsListHTML));
		}

		return $this->memberView->renderHTML();
	}

	private function deleteBoatPage() {

		$membersList = $this->fileHandler->getMembersListFromFile();

		$boatsList = $this->boatModel->getBoatsListFromMembers($membersList);

		$boatsListHTML = $this->memberView->getBoatList($boatsList, $membersList);


		$this->memberView->setBody($this->memberView->deleteBoatHTML($boatsListHTML));
		return $this->memberView->renderHTML();
	}

	private function showSpecificMemberPage() {

		$memberList = $this->memberView->getMemberListHTML($this->fileHandler->getMembersListFromFile());
		$this->memberView->setBody($this->memberView->showSpecificMemberPageHTML($memberList));
		return $this->memberView->renderHTML();
	}

	private function showSpecificMemberPageChosen($member, $membersList) {

		$memberBoatsListHTML = $this->memberView->getMemberBoatsListHTML($member);
		
		$memberListHTML = $this->memberView->getMemberListHTML($membersList);

		$this->memberView->setBody($this->memberView->showSpecificMemberPageChosenHTML($member, $memberListHTML, $memberBoatsListHTML));
		return $this->memberView->renderHTML();
	}

	public function showSimpleMembersList() {

		$membersList = $this->fileHandler->getMembersListFromFile();

		$memberListing = $this->memberView->getSimpleMembersList($membersList);

		$this->memberView->setBody($this->memberView->SimpleMembersListHTML($memberListing));
		return $this->memberView->renderHTML();
	}


	public function showDetailedMembersList() {

		$membersList = $this->fileHandler->getMembersListFromFile();

		$maxBoatAmount = $this->boatModel->getMaxBoatAmount($membersList);
		$boatListHTML = $this->memberView->getBoatListHTML($maxBoatAmount);

		$memberListing = $this->memberView->getDetailedMembersList($membersList);
		$this->memberView->setBody($this->memberView->DetailedMembersListHTML($memberListing, $boatListHTML));
		return $this->memberView->renderHTML();
	}

}