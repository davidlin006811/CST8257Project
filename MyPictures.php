<?php
session_start();
include 'ProjectCommon/Header.php';
include_once 'ProjectCommon/Functions.php';
if (urldecode($_GET['albumId'])){
    var_dump($_GET['albumId']);
}
?>
<?php include 'ProjectCommon/Footer.php' ?>


