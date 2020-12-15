<?php 
function getCurURL()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
      $pageURL = 'http://';
  }
  if (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80") {
    $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
} else {
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
}
$split = explode("?", $pageURL);
return $split[0];
}

function linkResetPass()
{
	$splited = explode("/", getCurURL());
	unset($splited[count($splited) - 1]);

	return implode("/", $splited) . "/reset-password.php";
}

function linkActiveAccount()
{
    $splited = explode("/", getCurURL());
    unset($splited[count($splited) - 1]);

    return implode("/", $splited) . "/active-account.php";
}

function redirectTo($message,$toPage="index.php"){
    if (strlen($message)>0) {
        echo "<script>alert('$message');</script>";    
    }
    echo "<script>window.location = '$toPage'; </script>";
}

function file_upload_max_size() {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $post_max_size = parse_size(ini_get('post_max_size'));
    if ($post_max_size > 0) {
      $max_size = $post_max_size;
    }

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}

function parse_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
  }
  else {
    return round($size);
  }
}
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/language/phpmailer.lang-vi.php';
require 'PHPMailer/src/SMTP.php';
include 'mailer.php';

include 'connectedDB.php';
include 'nguoidung.php';
?>