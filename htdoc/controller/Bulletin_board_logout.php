<?php

session_start();
$_SESSION = [];
session_destroy();

header('Location:Bulletin_board_user_login.php');
exit;



?>