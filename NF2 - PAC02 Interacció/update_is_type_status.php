<?php

require('class.login_details.php');

session_start();

$loginDetails = new LoginDetails();
$loginDetails->UpdateIsTypeStatus($_SESSION["login_details_id"], $_POST["is_type"]);

?>
