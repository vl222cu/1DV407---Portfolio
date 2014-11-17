<?php
class MemberView {
	const MESSAGE_SUCCESS_REGISTRATION = 'Registreringen lyckades';
	const MESSAGE_ERROR_REGISTRATION = 'Personnummer finns redan registrerat';
	const MESSAGE_SUCESS_CHANGE_MEMBER = 'Ändring av medlemsuppgifter lyckades';
	const MESSAGE_SUCESS_CHANGE_BOAT = 'Ändring av båtuppgifter lyckades';
	const MESSAGE_USER_NOT_EXIST = 'Medlemmen finns inte i databasen';
	const MESSAGE_USER_DELETED = 'Medlemmen är borttagen';
	const MESSAGE_BOAT_DELETED = 'Båten är borttagen';
	private $body = "";
	private $message = "";
	private $boatModel;
	private $memberModel;
	private static $selectedMemberId = "memberId";
	private static $selectedBoatId = "boatId";
	private static $firstName = "firstname";
	private static $lastName = "lastname";
	private static $personalNumber = "personalnumber";
	private static $boatType = "boatType";
	private static $boatLength = "boatLength";
	private static $oldSocialSecurityNumber = "oldpersonalnumber";
	private static $postedBoatlistId = "postedboatlistid";
	public static $actionRegisterPage = "registerpage";
	public static $actionRegister = "register";
	public static $actionChangeDataPage = "changedatapage";
	public static $actionSaveEditMember = "saveEditMember";
	public static $actionEdit = "edit";
	public static $actionDeleteMember = "deleteMember";
	public static $actionMemberComfirmedDelete = "memberConfirmedDelete";
	public static $actionReturn = "return";
	public static $actionAddBoat = "addBoat";
	public static $actionEditBoat = "editBoat";
	public static $actionDeleteBoat = "deleteBoat";
	public static $actionBoatComfirmedDelete = "boatConfirmedDelete";
	public static $actionEditChosenBoat = "editChosenBoat";
	public static $actionSaveBoat = "saveBoat";
	public static $actionShowSpecificMember = "showSpecificMember";
	public static $actionShowChosenMember = "showMemberChosen";
	public static $actionShowSimpleList = "showSimpleList";
	public static $actionShowDetailedList = "showDetailedList";
	public static $actionSaveEditedBoat = "saveEditedBoat";

	public function __construct(BoatModel $boatModel, MemberModel $memberModel) {
		$this->boatModel = $boatModel;
		$this->memberModel = $memberModel;
	}

	public function getUserAction() {
		if (key($_GET) == self::$actionRegisterPage) {

			$userAction = self::$actionRegisterPage;

		} elseif (key($_GET) == self::$actionRegister) {
			
			$userAction = self::$actionRegister;

		} elseif (key($_GET) == self::$actionChangeDataPage) {
			
			$userAction = self::$actionChangeDataPage;

		} elseif(key($_GET) == self::$actionSaveEditMember) {

			$userAction = self::$actionSaveEditMember;

		} elseif (key($_GET) == self::$actionEdit) {
			
			$userAction = self::$actionEdit;

		} elseif (key($_GET) == self::$actionDeleteMember) {
			
			$userAction = self::$actionDeleteMember;

		} elseif(key($_GET) == self::$actionMemberComfirmedDelete) {

			$userAction = self::$actionMemberComfirmedDelete;

		} elseif (key($_GET) == self::$actionReturn) {
			
			$userAction = self::$actionReturn;
		} elseif (key($_GET) == self::$actionAddBoat) {
			
			$userAction = self::$actionAddBoat;

		} elseif (key($_GET) == self::$actionEditBoat) {
			
			$userAction = self::$actionEditBoat;

		} elseif (key($_GET) == self::$actionDeleteBoat) {
			
			$userAction = self::$actionDeleteBoat;

		} elseif (key($_GET) == self::$actionBoatComfirmedDelete) {
			
			$userAction = self::$actionBoatComfirmedDelete;

		} elseif (key($_GET) == self::$actionEditChosenBoat) {
			
			$userAction = self::$actionEditChosenBoat;
		
		} elseif (key($_GET) == self::$actionSaveBoat) {

			$userAction = self::$actionSaveBoat;

		} elseif (key($_GET) == self::$actionShowSpecificMember) {

			$userAction = self::$actionShowSpecificMember;

		} elseif(key($_GET) == self::$actionShowChosenMember) {

			$userAction = self::$actionShowChosenMember;

		} elseif(key($_GET) == self::$actionShowSimpleList) {

			$userAction = self::$actionShowSimpleList;

		} elseif(key($_GET) == self::$actionShowDetailedList) {

			$userAction = self::$actionShowDetailedList;

		} elseif(key($_GET) == self::$actionSaveEditedBoat) {

			$userAction = self::$actionSaveEditedBoat;

		} else {

			$userAction = "";
		}

		return $userAction;
	}

	public function choseMemberDataHTML() {
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Ändra medlemsuppgifter</h2>
            <form enctype='multipart/form-data' method='post' action='?edit'>
	            <fieldset>
	            <legend>Ändra medlemsuppgifter - Fyll i personnummer</legend>
	                <p><label>Personnummer: </label><input type='text' name='personalnumber' required/></p>
	                <p><input type='submit' value='Ändra'/>
	            </fieldset>
            </form>
		";
		return $ret;
	}

	public function changeMemberDataHTML($firstname, $lastname, $personalnumber, $memberId) {
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Ändra medlemsuppgifter</h2>
            <form enctype='multipart/form-data' method='post' action='?edit'>
	            <fieldset>
	            <legend>Välj ny medlem att ändra uppgifter på - Fyll i personnummer</legend>
	                <p><label>Personnummer: </label><input type='text' name='personalnumber' required/></p>
	                <p><input type='submit' value='Ändra'/>
	            </fieldset>
            </form>
            <form enctype='multipart/form-data' method='post' action='?saveEditMember'>
	            <fieldset>
	            <legend>Fyll i de fält som du vill ändra uppgifter på</legend>
	                <p><label>Förnamn: </label><input type='text' name='firstname' value='$firstname' required/></p>
	                <p><label>Efternamn: </label><input type='text' name='lastname' value='$lastname' required/></p>
	                <p><label>Personnummer: </label><input type='text' name='personalnumber' value='$personalnumber' required/></p>
	                <input type='text' name='memberId' value='$memberId' hidden>
	                <p><input type='submit' value='Registrera'/>
	            </fieldset>
	            </fieldset>
            </form>
		";
		return $ret;
	}

	public function mainMenuHTML() {
		$ret = "
			<h2>Huvudmeny</h2>
            <p>1. <a href='?registerpage'>Registrera ny medlem</a></p>
            <p>2. <a href='?changedatapage'>Ändra medlemsuppgifter</a></p>
            <p>3. <a href='?deleteMember'>Ta bort medlem</a></p>
            <p>4. <a href='?addBoat'>Lägg till båt</a></p>
            <p>5. <a href='?editBoat'>Ändra båt</a></p>
            <p>6. <a href='?deleteBoat'>Ta bort båt</a></p>
            <p>7. <a href='?showSpecificMember'>Visa medlem</a></p>
            <p>8. <a href='?showSimpleList'>Visa medlemslista</a></p>
            <p>9. <a href='?showDetailedList'>Visa detaljerad medlemslista</a></p>
        ";
        return $ret;
	}

	public function registerHTML() {
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Registrera medlem</h2>
            <form enctype='multipart/form-data' method='post' action='?register'>
	            <fieldset>
	            <legend>Registrera ny medlem - Fyll i samtliga fält</legend>
	                <p><label>Förnamn: </label><input type='text' name='firstname' required/></p>
	                <p><label>Efternamn: </label><input type='text' name='lastname' required/></p>
	                <p><label>Personnummer: </label><input type='text' name='personalnumber' required/></p>
	                <p><input type='submit' value='Registrera'/>
	            </fieldset>
            </form>
         ";
        return $ret;    
	}

	public function deleteMemberHTML($memberList) {
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Ta bort medlem</h2>
            <form enctype='multipart/form-data' method='post' action='?memberConfirmedDelete'>
	            <fieldset>
	            <legend>Välj medlem och bekräfta borttagning</legend>
	                <p><label>Välj medlem: </label></p>
	                <select name='memberId'>
		            	$memberList;
		            </select>
	                <p><input type='submit' value='Ja, jag vill ta bort denna medlem!'/>
	            </fieldset>
            </form>
		";
		return $ret;
	}

	public function addBoatHTML($memberList) {
//		<input type='text' name='memberId'/>
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Lägg till båt</h2>
            <form enctype='multipart/form-data' method='post' action='?saveBoat'>
	            <fieldset>
	            <legend>Fyll i uppgifter för ny båt</legend>
	                <p><label>Tillhör medlem: </label></p>
	                <select name='memberId'>
		            	$memberList;
		            </select>
		            <p><label>Båttyp: </label></p>
	                <select name='boatType'>
		            	<option value='Segelbåt'>Segelbåt</option>
						<option value='Motorseglare'>Motorseglare</option>
						<option value='Motorbåt'>Motorbåt</option>
						<option value='Kajak/Kanot'>Kajak/Kanot</option>
						<option value='Övrigt'>Övrigt</option>
		            </select>
	                <p><label>Längd (cm): </label><input type='text' name='boatLength'/></p>
	                <p><input type='submit' value='Lägg till båt'/>
	            </fieldset>
            </form>
		";
		return $ret;
	}

	public function editBoatHTML($boatList, $boatDataArray) {
		if($boatDataArray == null) {
			$boatType = "";
			$boatLength = "";
		} else {
			$boatType = $boatDataArray[BoatModel::$jsonBoatType];
			$boatLength = $boatDataArray[BoatModel::$jsonBoatLength];
		}
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Ändra båt</h2>
			<fieldset>
				<form enctype='multipart/form-data' method='post' action='?editChosenBoat'>
	            	<legend>Välj båt för att sen ändra uppgifter</legend>
		           	<p><label>Välj båt: </label></p>
		            <select name='boatId'>
		            	$boatList;
		            </select>
		            <p><input type='submit' value='Välj båt att ändra'/></p>
		        </form>
	            <form enctype='multipart/form-data' method='post' action='?saveEditedBoat'>	            
	                <p><label>Nuvarande båttyp: </label><input type='text' name='boatType' value='$boatType' disabled/></p>
	                <p><label>Välj ny båttyp: </label>
	                <select name='boatType'>
		            	<option value='Segelbåt'>Segelbåt</option>
						<option value='Motorseglare'>Motorseglare</option>
						<option value='Motorbåt'>Motorbåt</option>
						<option value='Kajak/Kanot'>Kajak/Kanot</option>
						<option value='Övrigt'>Övrigt</option>
		            </select></p>
	                <p><label>Längd (cm): </label><input type='text' name='boatLength' value='$boatLength'/></p>
	                <p><input type='submit' value='Spara ändringar'/></p>
	            </form>
	        </fieldset>
		";
		return $ret;
	}

	public function deleteBoatHTML($boatList) {
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Ta bort båt</h2>
            <form enctype='multipart/form-data' method='post' action='?boatConfirmedDelete'>
	            <fieldset>
	            <legend>Välj båt att ta bort</legend>
		            <p><label>Välj båt: </label></p>
		            <select name='boatId'>
		            	$boatList;
		            </select>
	                <p><input type='submit' value='Ta bort båt'/>
	            </fieldset>
            </form>
		";
		return $ret;
	}

	public function showSpecificMemberPageHTML($memberList) {
		//Ska även visa upp båtar kopplade till medlem
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Visa medlemsuppgifter</h2>
            <form enctype='multipart/form-data' method='post' action='?showMemberChosen'>
	            <fieldset>
	            <legend>Välj medlem för mer detaljerade uppgifter</legend>
	                <p><label>Välj medlem: </label></p>
		            <select name='memberId'>
		            	$memberList;
		            </select>
		            <p><input type='submit' value='Visa medlem'/>
	            </fieldset>
            </form>
		";
		return $ret;
	}

	public function showSpecificMemberPageChosenHTML($firstname, $lastname, $personalnumber, $memberId, $memberList, $memberBoatsListHTML) {
		//Ska även visa upp båtar kopplade till medlem
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Visa medlemsuppgifter</h2>
            <form enctype='multipart/form-data' method='post' action='?showMemberChosen'>
	            <fieldset>
	            <legend>Välj ny medlem för mer detaljerade uppgifter</legend>
	                <p><label>Välj medlem: </label></p>
		            <select name='memberId'>
		            	$memberList;
		            </select>
		            <p><input type='submit' value='Visa medlem'/>
	            </fieldset>
            </form>
            <br>
            <fieldset>
            <legend>Medlemsuppgifter</legend>
                <p><strong>Förnamn:</strong> $firstname </p>
                <p><strong>Efternamn:</strong> $lastname </p>
                <p><strong>Personnummer:</strong> $personalnumber </p>
                $memberBoatsListHTML
            </fieldset>
            </fieldset>
		";
		return $ret;
	}

	public function SimpleMembersListHTML($memberlisting) {
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Kompakt lista på samtliga medlemmar</h2>
            <fieldset>
            <legend>Medlemsuppgifter</legend>
            	<table>
            		<tr>
            			<th>Medlemsnummer</th>
            			<th>Förnamn</th>
            			<th>Efternamn</th>
            			<th>Antal båtar</th>
                	</tr>
                	<p>$memberlisting</p>
                </table>
            </fieldset>
		";
		return $ret;
	}

	public function DetailedMembersListHTML($memberlisting, $boatListHTML) {
		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Fullständig lista på samtliga medlemmar</h2>
            <fieldset>
            <legend>Medlemsuppgifter</legend>
            	<table border='1'>
            		<tr>
            			<th>Medlemsnummer</th>
            			<th>Personnummer</th>
            			<th>Förnamn</th>
            			<th>Efternamn</th>
            			<p>$boatListHTML
                		$memberlisting</p>
                	</tr>
                </table>
            </fieldset>
		";
		return $ret;
	}

	public function setMessage($msg) {
		$this->message = '<p>' . $msg . '</p>';
	}

	public function setBody($body) {
		$this->body = $body;
	}

	public function renderHTML() {
		return $this->message . $this->body;
	}

	public function getMemberRegisteredFirstName() {
		return $_POST[self::$firstName];
	}

	public function getMemberRegisteredLastName() {
		return $_POST[self::$lastName];
	}

	public function getMemberRegisteredPersonalNumber() {
		return $_POST[self::$personalNumber];
	}

	public function getPostedMemberId() {
		if(isset($_POST[self::$selectedMemberId])) {
			return $_POST[self::$selectedMemberId];
		}
	}

	public function getPostedBoatId() {
		if(isset($_POST[self::$selectedBoatId])) {
			return $_POST[self::$selectedBoatId];
		}
	}

	public function getPostedBoatType() {
		return $_POST[self::$boatType];
	}

	public function getPostedLength(){
		return $_POST[self::$boatLength];
	}

	public function setOldPersonalNumber($oldPersonalNumber) {
		$_SESSION[self::$oldSocialSecurityNumber] = $oldPersonalNumber;	
	}

	public function getOldPersonalNumber() {
		return $_SESSION[self::$oldSocialSecurityNumber];
	}

	public function setSessionPostedBoatListId($boatListId) {
		$_SESSION[self::$postedBoatlistId] = $boatListId;
	}

	public function getSessionPostedBoatListId() {
		if(isset($_SESSION[self::$postedBoatlistId])) {
			return $_SESSION[self::$postedBoatlistId];
		}
	}

	public function getMemberListHTML() {
		//Ska returnera array med medlemmat - Format: "Förnamn", "Efternamn", "Personnummer", "Medlemmsnumemr"
		$memberListArray = $this->memberModel->getMemberListArray();		
		$memberListHTML = "<option selected>Välj medlem</option>\n";
		foreach($memberListArray as $key => $value) {
			if($value != null) {
				$firstName = $value[MemberModel::$jsonFirstName];
				$lastName = $value[MemberModel::$jsonLastName];
				$memberId = $value[MemberModel::$jsonMemberId];
				$personalId = $value[MemberModel::$jsonPersonalId];
				$memberListHTML .= "<option value='$memberId'>$firstName $lastName - $personalId </option>\n";
			}
		}
		//var_dump($memberListHTML);
		return $memberListHTML;
	}

	public function getSimpleMembersList() {
		$memberListArray = $this->memberModel->getMemberListArray();		
		$memberListHTML = "";
		foreach($memberListArray as $key => $value) {
			$firstName = $value[MemberModel::$jsonFirstName];
			$lastName = $value[MemberModel::$jsonLastName];
			$memberId = $value[MemberModel::$jsonMemberId];
			$personalId = $value[MemberModel::$jsonPersonalId];
			$boatAmount = $this->boatModel->getMemberAmountBoats($memberId);
			$memberListHTML .= "
								<tr>
									<td>$memberId</td>;
									<td>$firstName</td>
									<td>$lastName</td>
									<td>$boatAmount</td>
								</tr>";
		}
		return $memberListHTML;
	}

	public function getDetailedMembersList() {
		$memberListArray = $this->memberModel->getMemberListArray();		
		//Iterera igenom boatList och får tillbaka array innehållandes array med alla båtar
		$boatListArray = $this->boatModel->getBoatListArray();
		$memberListHTML = "";
		$lastMemberKey = sizeof($memberListArray) - 1;
		foreach($memberListArray as $key => $value) {
			$membersBoats = array();
			$firstName = $value[MemberModel::$jsonFirstName];
			$lastName = $value[MemberModel::$jsonLastName];
			$memberId = $value[MemberModel::$jsonMemberId];
			$personalId = $value[MemberModel::$jsonPersonalId];
			//Itererar igenom alla båtar för att hitta de båtar som tillhör den spsoecika medlemmern
			foreach ($boatListArray as $key2 => $value2) {
				$boat = $boatListArray[$key2];
				//Om båtradens värde är samma som aktuell medlems id, adderas ddata till array för denna medlems båtar
				//Detta helt enkelt sorterar de olika båtarna utifrån medlem.
				if($boat[MemberModel::$jsonMemberId] == $memberId) {
					array_push($membersBoats, $boat);
				}
			}
		
			$memberListHTML .= "
							<tr>
								<td>$memberId</td>
								<td>$personalId</td>
								<td>$firstName</td>
								<td>$lastName</td>
							";
		//loopar ignom medlemsbåtar och skriver ut alla båtar om finns på de olika medlemmarna
			foreach ($membersBoats as $key => $value) {
				$boatType = $value[BoatModel::$ownersBoatType];
				$boatLength = $value[BoatModel::$ownersBoatLength];
				$memberListHTML .="<td>Typ: $boatType Längd: $boatLength cm</td>";
			}
			$memberListHTML .= "</tr>";
		}
		return $memberListHTML;
	}

	public function getBoatList() {
		
		//Ska returnera array med båtar - Format: "Medlem", "Typ", "Längd"
		$boatListArray = $this->boatModel->getBoatListArray();
		$boatListHTML = "<option selected>Välj båt</option>\n";
		foreach($boatListArray as $key => $value) {
			$boatOwner = $value[BoatModel::$ownersName];
			$boatType = $value[BoatModel::$ownersBoatType];
			$boatLength = $value[BoatModel::$ownersBoatLength];
			$boatId = $value[BoatModel::$ownersBoatId];
			$memberId = $value[MemberModel::$jsonMemberId];
			$boatListHTML .= "<option value='$boatId'>Medlem: $boatOwner - Båttyp: $boatType - Längd: $boatLength</option>\n";
			
		}
		return $boatListHTML;
	}

	public function getBoatListHTML($maxBoatAmount) {
		$ret = "";
		for ($i=0; $i<$maxBoatAmount; $i++) {
			$boatNumber = $i + 1;
			$ret .="<th>Båt $boatNumber</th>\n";
		}
		return $ret;
	}

	public function getMemberBoatsListHTML($memberId) {
		$ret = "";
		$memberBoats = $this->boatModel->getMemberBoatsListArray($memberId);
		$count = 0;
		if($memberBoats != null) {
			foreach ($memberBoats as $key => $value) {
				if($value != null) {
					$count++;
					$boatNumber = $count;
					$boatType = $value[BoatModel::$jsonBoatType];
					$boatLength = $value[BoatModel::$jsonBoatLength];
					$ret .= "<p><strong>Båt $boatNumber:</strong> Typ: $boatType - Längd: $boatLength cm</p>";
				}
			}
		}
		return $ret;
	}
}
