<?
require_once ("../../../../config.php");
require_once ("../../../../lib/dbsql.class.php");
$DB=new DB_Engine('mysql', $settings['dbHost'], $settings['dbUser'], $settings['dbPass'], $settings['dbName']);

if ($_POST['event'] == 'tempOffStatus'){
$idrow = $_POST['id'];
$id_zp = $_POST['id_zp'];
$offStatus = intval($_POST['offStatus']);

$query = "UPDATE sp_ryad SET `tempOff` = $offStatus WHERE `sp_ryad`.`id` = $idrow";
$DB->execute($query);

$query = "SELECT tempOff FROM sp_ryad WHERE sp_ryad.id = $idrow";
$result = $DB->getAll($query);

$j_data = json_encode($idrow);
echo $j_data;
}