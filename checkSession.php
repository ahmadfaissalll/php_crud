<?php

// START SESSION
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['login'])) {

  $redirectUrl = '../login_system/login.php';

  header("Location: " . $redirectUrl);
//   exit;

}