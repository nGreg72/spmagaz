<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('userID')==1 OR $user->get_property('gid')==25):?>

<h1>Список заявок в группу Организаторы</h1>
    <style>
        tr:nth-child(1n) {
            background: #ededed;
        }
        tr:nth-child(2n) {
            background: #dddddd;
        }

        tr:nth-child(1) {
            background: #a2a2a2;
            color: #fff;
        }
    </style>


<table class="tab-admin-orgi">
<tr><td class="tab-td-0" style="width:30px">ID</td>
	<td class="tab-td-0" style="width:100px">ФИО|Ник</td>
	<td class="tab-td-0" style="width:100px">Регион</td>
<!--	<td class="tab-td-2" style="width:100px">Сканы док.</td> -->
	<td class="tab-td-0" style="width:100px">Телефон</td>
	<td class="tab-td-0" style="width:50px"><span title="сын ошибок трудных">Опыт</span>|<span title="имеет ли своих поставщиков">Пост</span></td>
	<td class="tab-td-0" style="width:100px">Порно-Сайты</td>
	<td class="tab-td-0">Статус</td>
	<td class="tab-td-0"><span title="рождает противодействие">Действие</span></td></tr>
	<?foreach ($items as $item):
		if ($item['status']==3) $status='Принят';
		if ($item['status']==2) $status='Отказанно';
		if ($item['status']==1) $status='Сидит-Ждет';
			$query = "SELECT count(id) FROM `sp_zakup` WHERE `status`>2 and `user`=".$item['user'];
			$colvo2=$DB->getOne($query);

			$query = "SELECT count(id) FROM `sp_url_ckeck` WHERE `user`=".$item['user'];
			$colvo=$DB->getOne($query);?>
	<tr><td class="approve"><?=$item['id']?></td>
	<td class="approve"><?=$item['name']?> (<a href="/com/profile/default/<?=$item['user']?>"><?=$item['username']?></a>)<br/>

		 закупок: <a href="?component=org&section=viewcheck&id=<?=$item['user']?>"><?=$colvo?></a> / 
			  <a href="?component=org&section=viewopen&id=<?=$item['user']?>"><?=$colvo2?></a></td>
	<td class="approve"><?=$item['region_name_ru']?>, <?=$item['city_name_ru']?></td>

<!-- Колонка сканов документов. Функция не активна
<td class="approve">
<?$query = "SELECT path FROM `sp_add_org_img` WHERE `tend`=".$item['id'];
$skan=$DB->getAll($query);?>
<?if(count($skan)>0):$ia=0;?>
<?foreach($skan as $ska):$ia++;?>
<a href="../<?=$ska['path']?>" target="_blank">документ <?=$ia?></a>
<?endforeach?>
<?endif?>
</td>-->


	<td><?=$item['phone']?> <br/>Подтв: <?if($item['activate']==1):?><span style="color:green">Да</span><?else:?><span style="color:red">Нет</span><?endif?></td>
	<td><?if($item['opyt']==1):?>Да<?else:?>Нет<?endif?> | <?if($item['post']==1):?>Да<?else:?>Нет<?endif?></td>
	<td><?=$item['site']?></td>
	<td class="tab-td-<?=$item['status']?>"><?=$status?></td>
	<td><a href="?component=org&section=add&id=<?=$item['id']?>" style="color: green">Сразу принять</a><br/>
		<a href="?component=org&section=del&id=<?=$item['id']?>" style="color: red">Немного отклонить</a><br/>
		<a href="?component=org&section=see&id=<?=$item['id']?>" style="color: blue;">Долго ждать</a><br/>
		<a href="?component=org&section=dela&id=<?=$item['id']?>" style="color: black" onclick="if (!confirm('Чо? В Натуре???')) return false;">Совсем удалить</a>
	</td></tr>
		<?endforeach;?>
</table>

<!-- Нижние кнопки страниц -->
<?if ($total>1)
	
		echo '<p><div class="pagenation" align="center" style="margin-bottom:10px; margin-top:10px;">'
		.$pervpage.$page2left.$page1left.'<span>'.$page.'</span>'.$page1right.$page2right
		.$nextpage.'</div></p>';
	

		
		$query = "SELECT `cities`.`city_name_ru`, `cities`.`id_city`,
		(select count(pp.id) from punbb_users pp where pp.city=p1.city and pp.group_id=5) as count1
		FROM `punbb_users` p1
		JOIN `cities` ON `cities`.`id_city`=`p1`.`city`
		GROUP BY `cities`.`city_name_ru`";
		$items=$DB->getAll($query);
	
	echo '<table class="tab-admin-orgi">
	<div style="display: inline-flex; text-indent: 5px; margin-top:50px; line-height:20px; background-color: #ededed;">
	<div  style="width: 261px;">Город</div>
	<div style="width: 194px;">Кол-во оргов</div>
	<div style="width: 344px;">Закупок: всего/активных</div>
	</div>';
	
		
		foreach($items as $item):
		$query = "SELECT count(id) FROM `sp_url_ckeck` WHERE `city`=".$item['id_city'];
		$colvo=$DB->getOne($query);

		$query = "SELECT count(`sp_zakup`.`id`) FROM `sp_zakup` 
		  LEFT JOIN `sp_url_ckeck` ON `sp_zakup`.`id_check`=`sp_url_ckeck`.`id`
		WHERE `sp_zakup`.`status`=3 and `sp_url_ckeck`.`city`=".$item['id_city'];
		$colvo2=$DB->getOne($query);
	
	echo '
	<tr>
	<td style="background-color: beige;"><a href="index.php?component=org&city='.$item['id_city'].'">'.$item['city_name_ru'].'</a></td>
	<td style="background-color: beige;">'.$item['count1'].'</td>
	<td style="background-color: beige;"><a href="?component=org&section=viewcheck&id='.$item['id_city'].'&city=1">'.$colvo.'</a> / 
		<a href="?component=org&section=viewopen&id='.$item['id_city'].'&city=1">'.$colvo2.'</a></td>
	</tr>'; //city 1 - flag is city
	
endforeach;?>


</table>
<?endif;?>