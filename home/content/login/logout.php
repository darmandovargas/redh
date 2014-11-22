<?php
session_start();
if(isset($_SESSION['sesskey']))
  unset($_SESSION['sesskey']);

if(isset($_SESSION['username']))
  unset($_SESSION['username']);

session_destroy();

echo "success";
?>