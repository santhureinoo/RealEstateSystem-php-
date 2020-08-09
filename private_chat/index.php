<?php
session_start();
$user_id = empty($_SESSION['userid']) ? NULL : $_SESSION['userid'];

//Connect to database
include "connection.php";

if ( empty($user_id) ) {
	include "login.php";
} else {
	include "chat.php";
}
?>