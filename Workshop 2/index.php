<?php

require_once("src/Controller/MemberController.php");
require_once("viewHTML.php");

$controller = new MemberController();
$htmlBody = $controller->doCheckRegistration();

$view = new viewHTML();
$view->showHTML($htmlBody);

//testar lite här