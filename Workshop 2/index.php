<?php

require_once("src/Controller/MemberController.php");
require_once("viewHTML.php");

session_start();

$controller = new MemberController();
$htmlBody = $controller->doCheckRegistration();

$view = new viewHTML();
$view->showHTML($htmlBody);