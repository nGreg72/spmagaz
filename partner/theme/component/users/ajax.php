<?php
require_once ("../../../../config.php");
require_once ("../../../../lib/dbsql.class.php");
$DB=new DB_Engine('mysql', $settings['dbHost'], $settings['dbUser'], $settings['dbPass'], $settings['dbName']);

$id = $_POST['id'];
$status = $_POST['status'];

$sql = "UPDATE punbb_users SET `punbb_users`.`no_answer` = $status WHERE `punbb_users`.`id` = $id";
$DB->execute($sql);

