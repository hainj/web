<?php

session_start();
/*echo session_id();*/
$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "";
$DB_name = "mydb";

try
{
     $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
     echo $e->getMessage();
}


include_once 'User.class.php';
$user = new USER($DB_con);
?>