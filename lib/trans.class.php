<?
/*  system: CMS  web: www.spmagaz.com  */

if($_GET['table']=='forecasts') {
	if($_GET['type']==1)$where='WHERE `forecasts`.`type`= 1 ';
	$all = $DB->getAll('SELECT `forecasts`.* FROM `forecasts` 
			'.$where.'
			ORDER BY `forecasts`.`id` DESC 
			LIMIT '.intval($_GET['limit']));
	}
if($_GET['table']=='kontors') {
	$all = $DB->getAll('SELECT `kontors`.* FROM `kontors` 
			'.$where.'
			ORDER BY `kontors`.`id` ASC;');
	}

header('Content-type: application/json; charset=utf-8');
header('Cache-Control: no-cache');
//print_r($all);
echo json_encode($all);
exit;
?>