<?php

require('class.login_details.php');

session_start();

$loginDetails = new LoginDetails();
$loginDetails->UpdateLastActivity($_SESSION["login_details_id"]);

?>
