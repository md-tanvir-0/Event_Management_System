<?php
if($_SESSION['Useid'] === null){
    header("Location: ../views/Login.php");
    exit();
}
require_once('../models/dbmodel.php');
?>