<?
require_once ("../../../../config.php");
require_once ("../../../../lib/dbsql.class.php");
$DB=new DB_Engine('mysql', $settings['dbHost'], $settings['dbUser'], $settings['dbPass'], $settings['dbName']);

if ($_POST['event'] == "changeZakupStatus"){
$id = intval($_POST['id']);
$newZakupStatus = intval($_POST['newZakupStatus']);

$query = "UPDATE sp_zakup SET sp_zakup.status = $newZakupStatus WHERE sp_zakup.id = $id";
$DB->execute($query);
}