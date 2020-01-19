<?php
if(!isset($_POST['user_id']) || !isset($_POST['user_pw'])) exit;
$user_id = $_POST['user_id'];
$user_pw = $_POST['user_pw'];

if($user_pw != "54321") {
        echo "<script>alert('패스워드가 잘못되었습니다.');history.back();</script>";
        exit;
}
session_start();
$_SESSION['user_id'] = $user_id;
$_SESSION['user_pw'] = $user_pw;
?>
<meta http-equiv='refresh' content='0;url=main.php'>
