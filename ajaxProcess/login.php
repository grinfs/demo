<?php

if (isset($_POST['user']) && isset($_POST['pass'])) {
	include '../controller/incl.php';
	$nguoidung = new nguoidung;

	echo json_encode($nguoidung->logIn($_POST['user'], $_POST['pass']));
}

?>