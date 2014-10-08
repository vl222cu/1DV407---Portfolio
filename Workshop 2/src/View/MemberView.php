<?php

class MemberView {

	const MESSAGE_SUCCESS_REGISTRATION = 'Registreringen lyckades.';
	const MESSAGE_ERROR_REGISTRATION = 'Registreringen misslyckades.';
	private $memberModel;
	private $body = "";
	private $message = "";

	public function __construct(MemberModel $memberModel) {

		$this->memberModel = $memberModel;
	}

	public function getUserAction() {

		if (key($_GET) == "registerpage") {

			$userAction = "registerpage";

		} elseif (key($_GET) == "register") {
			
			$userAction = "register";

		} elseif (key($_GET) == "changedatapage") {
			
			$userAction = "changedatapage";

		} elseif (key($_GET) == "change") {
			
			$userAction = "change";

		} elseif (key($_GET) == "delete") {
			
			$userAction = "delete";

		} elseif (key($_GET) == "return") {
			
			$userAction = "return";

		} else {

			$userAction = "";

		}
		return $userAction;
	}

	public function changeMemberDataHTML() {

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

		";

		return $ret;
	}

	public function mainMenuHTML() {

		$ret = "
			<h2>Huvudmeny</h2>
            <p>1. <a href='?registerpage'>Registrera ny medlem</a></p>
            <p>2. <a href='?changedatapage'>Ändra medlemsuppgifter</a></p>
            <p>3. <a href='?delete'>Ta bort medlem</a></p>
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

	public function setMessage($msg) {

		$this->message = '<p>' . $msg . '</p>';

	}

	public function setBody($body) {

		$this->body = $body;
	}

	public function renderHTML() {

		return $this->message . $this->body;
	}
}