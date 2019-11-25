<?defined('_JEXEC') or die('Restricted access');?>
<?

	$query = "SELECT `sp_zakup`.`id` FROM `sp_zakup`
	ORDER BY `sp_zakup`.`id` ASC";
        $items = $DB->getAll($query);
	$i=0;
	foreach($items as $it):
		$dat=time()-13600+($it['id']*2);
		$query = "UPDATE `sp_zakup` SET `date`='$dat' WHERE `id` =".$it['id']." LIMIT 1 ;";
		$DB->execute($query);
		$i++;
	endforeach;

?>

Установлен штамп даты для: <?=$i?> закупок.