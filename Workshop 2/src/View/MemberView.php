<?php

class MemberView {

	const MESSAGE_SUCCESS_REGISTRATION = 'Registreringen lyckades';
	const MESSAGE_ERROR_REGISTRATION = 'Personnummer finns redan registrerat';
	const MESSAGE_SUCESS_CHANGE_MEMBER = 'Ändring av medlemsuppgifter lyckades';
	const MESSAGE_USER_NOT_EXIST = 'Medlemmen finns inte i databasen';
	const MESSAGE_USER_DELETED = 'Medlemmen är borttagen';
	private $memberModel;
	private $body = "";
	private $message = "";
	private $boatModel;

	public function __construct(MemberModel $memberModel, BoatModel $boatModel) {

		$this->memberModel = $memberModel;
		$this->boatModel = $boatModel;
	}

	public function getUserAction() {

		if (key($_GET) == "registerpage") {

			$userAction = "registerpage";

		} elseif (key($_GET) == "register") {
			
			$userAction = "register";

		} elseif (key($_GET) == "changedatapage") {
			
			$userAction = "changedatapage";

		} elseif(key($_GET) == "saveMemberChange") {

			$userAction = "saveMemberChange";

		} elseif (key($_GET) == "change") {
			
			$userAction = "change";

		} elseif (key($_GET) == "deleteMember") {
			
			$userAction = "deleteMember";

		} elseif(key($_GET) == "memberConfirmedDelete") {

			$userAction = "memberConfirmedDelete";

		} elseif (key($_GET) == "return") {
			
			$userAction = "return";

		} elseif (key($_GET) == "addBoat") {
			
			$userAction = "addBoat";

		} elseif (key($_GET) == "editBoat") {
			
			$userAction = "editBoat";

		} elseif (key($_GET) == "deleteBoat") {
			
			$userAction = "deleteBoat";

		} elseif (key($_GET) == "boatChosen") {
			
			$userAction = "editChosenBoat";
		
		} elseif (key($_GET) == "saveBoat") {

			$userAction = "saveBoat";

		} elseif (key($_GET) == "showSpecificMember") {

			$userAction = "showSpecificMember";

		} elseif(key($_GET) =="showMemberChosen") {

			$userAction = "showMemberChosen";

		} elseif(key($_GET) =="showSimpleList") {

			$userAction = "showSimpleList";

		} elseif(key($_GET) =="showDetailedList") {

			$userAction = "showDetailedList";

		} else {

			$userAction = "";

		}
		return $userAction;
	}

	public function choseMemberDataHTML() {

		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Ändra medlemsuppgifter</h2>
            <form enctype='multipart/form-data' method='post' action='?change'>
	            <fieldset>
	            <legend>Ändra medlemsuppgifter - Fyll i personnummer</legend>
	                <p><label>Personnummer: </label><input type='text' name='personalnumber' required/></p>
	                <p><input type='submit' value='Ändra'/>
	            </fieldset>
            </form>

            <form enctype='multipart/form-data' method='post' action='?saveMemberChange'>
	            <fieldset>
	            <legend>Ändra medlemsuppgifter - Fyll i personnummer</legend>
	                <p><label>Förnamn: </label><input type='text' name='firstname' value='' disabled/></p>
	                <p><label>Efternamn: </label><input type='text' name='lastname' value='' disabled/></p>
	                <p><label>Personnummer: </label><input type='text' name='personalnumber' value='' disabled/></p>
	                <p><input type='submit' value='Registrera' disabled/>
	            </fieldset>
	            </fieldset>
            </form>

		";

		return $ret;
	}

	public function changeMemberDataHTML($firstname, $lastname, $personalnumber, $memberId) {

		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Ändra medlemsuppgifter</h2>
            <form enctype='multipart/form-data' method='post' action='?change'>
	            <fieldset>
	            <legend>Ändra medlemsuppgifter - Fyll i personnummer</legend>
	                <p><label>Personnummer: </label><input type='text' name='personalnumber' required/></p>
	                <p><input type='submit' value='Ändra'/>
	            </fieldset>
            </form>

            <form enctype='multipart/form-data' method='post' action='?saveMemberChange'>
	            <fieldset>
	            <legend>Ändra medlemsuppgifter - Fyll i personnummer</legend>
	                <p><label>Förnamn: </label><input type='text' name='firstname' value='$firstname' required/></p>
	                <p><label>Efternamn: </label><input type='text' name='lastname' value='$lastname' required/></p>
	                <p><label>Personnummer: </label><input type='text' name='personalnumber' value='$personalnumber' required/></p>
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

	public function MemberDataToBeChangedHTML() {

		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Ändra medlemsuppgifter</h2>
            <form enctype='multipart/form-data' method='post' action='?changesaved'>
	            <fieldset>
	            <legend>Ändra medlemsuppgifter - Fyll i de fält som du vill ändra</legend>
	                <p><label>Förnamn: </label><input type='text' name='firstname'/></p>
	                <p><label>Efternamn: </label><input type='text' name='lastname'/></p>
	                <p><label>Personnummer: </label><input type='text' name='personalnumber'/></p>
	                <p><input type='submit' value='Spara'/>
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
	                <p><label>Tillhör medlem: </label></p>
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

	public function editBoatHTML($boatList) {

		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Ändra båt</h2>

			<fieldset>
				<form enctype='multipart/form-data' method='post' action='?boatChosen'>
	            	<legend>Välj båt för att sen ändra uppgifter</legend>
		           	<p><label>Välj båt: </label></p>
		            <select name='allBoats'>
		            	$boatList;
		            </select>
		            <p><input type='submit' value='Välj båt att ändra'/></p>
		        </form>
	            <form enctype='multipart/form-data' method='post' action='?saveBoatChanges'>	            
	                <p><label>Tillhör medlem: </label><input type='text' name='memberId' value='$boatOwner'/></p>
	                <p><label>Båttyp: </label><input type='text' name='boatType' value='$boatType'/></p>
	                <p><label>Längd (cm): </label><input type='text' name='boatLength' value='$boatLength/></p>
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
            <form enctype='multipart/form-data' method='post' action='?boatConfirmDelete'>
	            <fieldset>
	            <legend>Välj båt att ta bort</legend>
		            <p><label>Välj båt: </label></p>
		            <select name='allBoats'>
		            	$boatList;
		            </select>
	                <p><label>Tillhör medlem: </label><input type='text' name='memberId'/></p>
	                <p><label>Båttyp: </label><input type='text' name='boatType'/></p>
	                <p><label>Längd (cm): </label><input type='text' name='boatLength'/></p>
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
	            <legend>Ändra medlemsuppgifter - Fyll i personnummer</legend>
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
                <p>Förnamn: </p>
                <p>Efternamn: </p>
                <p>Personnummer: </p>
                
            </fieldset>
            </fieldset>
		";

		return $ret;
	}

	public function showSpecificMemberPageChosenHTML($firstname, $lastname, $personalnumber, $memberId, $memberList) {

		//Ska även visa upp båtar kopplade till medlem

		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Visa medlemsuppgifter</h2>
            <form enctype='multipart/form-data' method='post' action='?showMemberChosen'>
	            <fieldset>
	            <legend>Ändra medlemsuppgifter - Fyll i personnummer</legend>
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
                <p>Förnamn: $firstname </p>
                <p>Efternamn: $lastname </p>
                <p>Personnummer $personalnumber </p>
                
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
                		<p>$memberlisting</p>
                	</tr>
                </table>
            </fieldset>
		";

		return $ret;
	}

	public function DetailedMembersListHTML($memberlisting) {

		$ret = "
			<p><a href='?return'>Tillbaka</a></p>
			<h2>Fullständig lista på samtliga medlemmar</h2>
            <fieldset>
            <legend>Medlemsuppgifter</legend>
            	<table>
            		<tr>
            			<th>Medlemsnummer</th>
            			<th>Personnummer</th>
            			<th>Förnamn</th>
            			<th>Efternamn</th>
                		<p>$memberlisting</p>
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

		return $_POST["firstname"];

	}

	public function getMemberRegisteredLastName() {

		return $_POST["lastname"];

	}

	public function getMemberRegisteredPersonalNumber() {

		return $_POST["personalnumber"];
	}

	public function getChosenBoatToEdit() {

		$boatId = $_POST['allBoats'];

		//Returnerar array med uppgifter
		return $this->boatModel->getBoatArray();
	}

	public function getPostedMemberId() {
		return $_POST['memberId'];
	}

	public function getPostedBoatType() {
		return $_POST['boatType'];
	}

	public function getPostedLength(){
		return $_POST['boatLength'];
	}
}