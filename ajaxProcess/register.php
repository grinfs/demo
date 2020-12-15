<?php

if (isset($_POST['email']) && isset($_POST['sdt']) && isset($_POST['mk']) && isset($_POST['ten']) && isset($_POST['ho'])) {
	include '../controller/incl.php';
	$nguoidung = new nguoidung;

	echo $nguoidung->addUser($_POST['email'], $_POST['sdt'], $_POST['mk'], $_POST['ten'], $_POST['ho']);
}
else {
	echo $_POST['email'].'---'.$_POST['sdt'].'---'.$_POST['mk'].'---'.$_POST['ten'].'---'.$_POST['ho'];
}
?>