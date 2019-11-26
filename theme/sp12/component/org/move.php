<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID')):?>

<div class="menu-top5">Перенести заказ в другую закупку/другой выкуп</div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>
  <?if(count($allsize)>0 and count($openzakup)>0):?>


<?if(!$message):?>
	<?if($_GET['u']):?><br/><a href="?u=<?=$itm[4]?>" class="link4"><< заказы</a><?endif?>
	<table class="tab_order" width="100%"><tr>
	<tr class="tab_order_name">
	<td width="100">Пользователь</td>
	<td>Название</td>
	<td width="40">Кол-во</td>
	<td width="50">
		<span title="цена товара без орг процента" class="green">Цена заказа</span>
	</td>
	<?if($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID')):?>
	<td width="50">
		<span title="цена товара с орг процентом">Цена +Орг</span>
	</td>
	  <?if($openzakup[0]['dost']>0 and $openzakup[0]['status']>=3):?>
		<td width="50">
			<span title="Доставка">Доставка</span>
		</td>
	  <?endif;?>
	<td width="50">
		<span title="Цена + Орг + Доставка" class="blue">Итого</span>
	</td>
	<?endif;?>

	<td width="70">Статус</td>
	</tr>
	  <?
//print_r($openzakup);exit;
if($openzakup[0]['dost']>0 and $openzakup[0]['status']>=3):
		  $query = "SELECT `sp_ryad`.`price`,`sp_order`.`kolvo`,`sp_order`.`status`
		    FROM `sp_order` 
		    LEFT JOIN `sp_ryad` ON `sp_order`.`id_ryad`=`sp_ryad`.`id`
		    WHERE `sp_order`.`id_zp` = '{$openzakup[0]['id']}' and `sp_order`.`status` != '2'";
		  $tp=$DB->getAll($query);
		$totalprice=0;
		foreach($tp as $t){
		//if($t['status']==2)continue;
		$totalprice=$totalprice+($t['kolvo']*$t['price']);
		}
		  

	   endif;?>

	<?if(floatval($openzakup[0]['curs'])==0) $openzakup[0]['curs']=1;?>
	<?$totaldost=0;
	foreach ($allsize as $itm):?>
		<?

		$timee=explode(':',$itm[1]);
		$datee=explode('.',$timee[0]);
		$datee=$datee[2].'/'.$datee[1];
		$timee=explode('.',$timee[1]);
		$timee=$timee[0].' ч, '.$timee[1].' мин.';

		if ($itm[3]==0 OR $user->get_property('gid')==25 OR $itm[4]==$user->get_property('userID')  or $openzakup[0]['user']==$user->get_property('userID'))
		$linku='<a href="/com/profile/default/'.$itm[4].'" class="link4">'.$itm[5].'</a>'; else $linku='Аноним';
		?>
		<tr id="item<?=$itm[10]?>" class="<?if($itm[9]==1||$itm[9]==3||$itm[9]==4||$itm[9]==5):?>is_yes<?endif?><?if($itm[9]==2):?>is_no<?endif?>">

		<td width="100" class="tab_order_date td1"><?=$linku?>
			<br/><span>дата: <?=$datee?></span>
			<br/><a class="news_body_a4" href="/com/org/delrz/<?=$itm[10];?>/" onclick="if (!confirm('Вы подтверждаете удаление заказа?')) return false;">удалить</a>
		<a class="news_body_a2" href="/com/org/editr/<?=$itm[0]['id'];?>/<?=intval($_GET['value']);?>">редакт.</a>
			<?if(!empty($itm[12])):?><br/><b>ЦР:</b> <?=$itm[12]?><?endif;?><br/>
		</td><td class="td1">
			<u><?=$itm[0]['title']?></u><br/>
			<?if(!empty($itm[0]['articul'])):?><b>Артикул:</b> <?=$itm[0]['articul']?><br/><?endif;?>
			<?if(!empty($itm[7])):?><b>Размер:</b> <?=$itm[7]?><br/><?endif;?>
			<?if(!empty($itm[8])):?><b>Цвет:</b> <?=$itm[8]?><br/><?endif;?>
			<?if(!empty($itm[11])&&($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID'))):?>
				<b>Доп.инфо:</b> <i><?=$itm[11]?></i>
			<?endif;?>
			</td>
			<td class="td1"><?=$itm[2]?> шт.</td>

			<td class="td1"><span title="цена товара без орг процента" class="green"><?=$itm[2]*$itm[0]['price']*$openzakup[0]['curs']?>р</span> </td>

			<?if($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID')):?>
			<td class="td1"><span title="цена товара с орг процентом" class="blue"><?=($itm[0]['price']*$openzakup[0]['curs']*$itm[2])+round(($itm[0]['price']*$itm[2]*$openzakup[0]['curs'])/100*$openzakup[0]['proc'])?>р</span> </td>
			  <?if($openzakup[0]['dost']>0 and $openzakup[0]['status']>=3):?>
				<td class="td1">
				  <span title="Доставка"><?=$userdost=round(($openzakup[0]['dost']/100)*(($itm[0]['price']*$openzakup[0]['curs']*$itm[2])/($totalprice/100)),1);?>р</span>
				</td>
			  <?endif;?>
			<td class="td1">
				<span title="общая цена с учетом оргпроцента"><?=($itm[0]['price']*$openzakup[0]['curs']*$itm[2])+round(($itm[0]['price']*$itm[2]*$openzakup[0]['curs'])/100*$openzakup[0]['proc'])+$userdost?>р</span>
			</td>
			<?endif;?>
			<td class="td2">

<?if($itm[9]==0):?>Новый<?endif?>
<?if($itm[9]==1):?>Включено в счет<?endif?>
<?if($itm[9]==2):?>Отказано<?endif?>
<?if($itm[9]==7):?>Нет в наличии<?endif?>
<?if($itm[9]==3):?>Не оплачен<?endif?>
<?if($itm[9]==4):?>Оплачен<?endif?>
<?if($itm[9]==5):?>Раздача<?endif?>
<?if($itm[9]==6):?>Архив<?endif?>

			</td>
			</tr>
	<?if($itm[9]<>2)$totalp=$totalp+($itm[0]['price']*$itm[2]*$openzakup[0]['curs']);
        $totaldost+=$userdost;
	endforeach?>	
	</table>
<form name="" method="post" action="">
<input type="hidden" name="move" value="1">
<input type="hidden" name="id_o" value="<?=$_GET['value2']?>">
Список закупок:
<select name="id_zp" class="inputbox" style="width:350px;">
	<?foreach($allZP as $item):?>
	<option value="<?=$item['id']?>"><?=$item['title']?></option>
	<?endforeach?>
</select>
<input type="submit" value="Перенести">
</form>
<?endif?>

	<div class="line3"></div>
	<p><a href="/com/org/open/<?=intval($_GET['value'])?>" class="link4">...Вернуться к закупке</a></p>
</div>
  <?else:?>

	<h1>Такого заказа нет</h1>
	<p><a href="javascript:history.back()" class="link4"><< Назад</a></p>

  <?endif?>


<?endif?>