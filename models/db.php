<?php

function getConnection()
{
	$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname="ems_db";
    $conn = new mysqli($servername, $username, $password,$dbname);
    return $conn;

}

?>