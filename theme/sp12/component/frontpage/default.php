<?defined('_JEXEC') or die('Restricted access');?>
  <div id="blocks">
<!--	  
	  <div id="lastbay">
	    <div class="menu-top4">Последние заказы</div>
	    <div class="menu-body4">
		<?if(count($lastorder)>0):?>

		<?$i=0;foreach($lastorder as $lastor):
		if(trim($popzp[0]['title'])=='')continue; $i++;

		$date=explode(':',$lastor['date']);
//		$time=explode('.',$date[1]);
		$date=explode('.',$date[0]);

		$mes=$mesarr[($date[1]-1)];
		$date_see=$date[2].' '.$mes.', '.$time[0].':'.$time[1];?>
		<span class="date"><?=$date_see;?></span>
		<a href="/com/org/open/<?=$lastor['id_zp'];?>" class="title"><?=utf8_substr($lastor['title'],0,60).'...';?></a>
		<span class="price"><?if(!empty($lastor['price'])):?><?=$lastor['price']*$lastor['curs'];?> <?=$registry['valut_name']?><?endif?></span>
		<?endforeach;?>
		<?else:?>
		       Заказов нет
		<?endif?>


</div>
  </div>
-->  
<!--
<div id="populary">
  	    <div class="menu-top4">Популярные закупки</div>
	    <div class="menu-body4">
		<?if(count($popzp)>0): 
		?>
		<table><tr>
		  <td valign="top" align="center" width="180"><a href="/com/org/open/<?=$popzp[0]['id']?>">
			<img src="<?=$img_path?>" width="125" height="100" border="0" alt="<?=$popzp[0]['title']?>" title="<?=$popzp[0]['title']?>" class="photo"/></a>
			<br/><a href="/com/org/open/<?=$popzp[0]['id']?>" style="12px arial; color:black"><?if(utf8_strlen($popzp[0]['title'])>60):?><?=utf8_substr($popzp[0]['title'],0,60)?><?else:?><?=$popzp[0]['title']?><?endif?></a>
		  </td>
		  <td valign="top" align="left" class="popular">
			<span class="text">
				<?if(utf8_substr(strip_tags($popzp[0]['text']),0,70)==''):?>
					<?=$popzp[0]['title'];?>
				<?else:?>
					<?=utf8_substr(strip_tags(htmlspecialchars_decode($popzp[0]['text'])),0,70);?><?if(trim(strip_tags(htmlspecialchars_decode($popzp[0]['text'])))>''):?>...<?endif?>
				<?endif;?>
				<br/>&nbsp;<br/>
			<span class="status">Открыта, <b><?=$popzp[0]['res']?> заказ(а)</b></span><br/>
			<b>Оргсбор</b>: <?=$popzp[0]['proc']?>%<br/>
			<b>Минималка</b>: <?=$popzp[0]['min']?> <?=$registry['valut_name']?><br/>
			<a href="/com/profile/default/<?=$popzp[0]['user']?>" class="link2"><?=$popzp[0]['username']?></a>
			г. <?=$popzp[0]['city_name_ru']?>
		  </td></tr>
		</table>
		<?else:?>
		       Закупок нет
		<?endif?>

	    </div>
	  </div>
-->	  
  </div>

<div id="sidebar" style=" border-bottom: 4px double gray">
	<a href="/<?if($sort_city==0):?>every<?else:?><?=$sort_city?><?endif;?>/new" <?if($_GET['catz']=='new' or $_GET['catz']=='all' or $_GET['catz']==''):?>class="active"<?endif;?>>Новые</a>
	<?foreach($cat_zp as $catz):?>
	<a href="/<?if($sort_city==0):?>every<?else:?><?=$sort_city?><?endif;?>/<?=$catz[0]['id']?>"
	<?if($catz[0]['id']==$sort_catz_scroll):?>class="active"<?endif;?>> <?=$catz[0]['name']?> </a>
	<?endforeach;?>
	<div><a href="/<?if($sort_city==0):?>every<?else:?><?=$sort_city?><?endif;?>/ready" <?if($_GET['catz']=='ready'):?>
            class="active" <?endif;?> style="font: bold 20px arial black; line-height: 12px; background-color: #9fbcfd">Стопы </a>
	</div>
</div>


  <?if($sort_catz>0):?>
 <div id="sidebar2" <?if(count($cat_zp[$sort_catz_scroll])>7):?>style="height:60px"<?endif?>>
	<?$i=0;foreach($cat_zp[$sort_catz_scroll] as $catz):$i++;
	if($i==1)continue;?>
	<a href="/<?if($sort_city==0):?>every<?else:?><?=$sort_city?><?endif;?>/<?=$catz['id']?>"
		<?if($catz['id']==$sort_catz):?>class="active"<?endif;?>><?=$catz['name']?></a>
	<?endforeach;?>
</div>
  <?endif;?>
  
<!-- Верхние кнопки страниц 
<div style="margin-top: 20px;"> 
		<?if ($total>1) echo '<div class="pagenation" align="center">'
		.$pervpage .$page4left .$page3left .$page2left .$page1left.
		'<span>'.$page.'</span>'.$page1right .$page2right .$page3right .$page4right .$nextpage.'</div>';?>
</div>  -->

  <div id="assort" class="assort_740">
    <?if(count($zakup)>0):?>
    <?$i=0;
    foreach($zakup as $zak):$i++;
	
	if(!empty($zak['foto']))
	{
	$split=explode('/',$zak['foto']);
	$img_path='/images/'.$split[2].'/229/190/1/'.$split[3];
	}
	else $img_path='/'.$theme.'images/no_photo229x190.png';?>
    
	<div class="item <?if($i==3):$i=0;?>closing<?endif?>">
	<a href="/com/org/open/<?=$zak['id']?>"><img src="<?=$img_path?>" border="0" width="229" height="190" alt="<?=$zak['title']?>" title="<?=$zak['title']?>"/></a>
	
	<span class="slash"></span>

        <div class="item-cont">
            <a href="/com/org/open/<?= $zak['id'] ?>" class="link3"><?= $zak['title'] ?></a><br/>
            <div class="pricesh">
                <span>Организатор:</span>
                <a href="/com/profile/default/<?= $zak['user'] ?>" class="link2"><?= $zak['username'] ?></a>
                <!--            , г. --><? //=$zak['city_name_ru']?>
                <br>
                <span style="font: bold 17px Arial;"><?= $zak['namestat'] ?></span>
                    , <span><?= $zak['res'] ?></span> заказа(ов)
            </div>

<!-- вывод процентов сбора заказов на первую страницу --->	
	<div>
	<?
	$query = "SELECT o.id, o.id_order, o.id_ryad, o.date, o.kolvo, o.message, o.color, o.status
			, o.user, `punbb_users`.`username`
			FROM `sp_order` AS o
			LEFT JOIN `punbb_users` ON o.user=`punbb_users`.`id`
			WHERE o.`id_zp` = '".intval($zak['id'])."' ORDER BY o.id DESC";
	$allorder=$DB->getAll($query);
	
	$sql = "SELECT `sp_zakup`.`min`, `sp_zakup`.`minType`,`sp_zakup`.`curs` FROM `sp_zakup` WHERE `sp_zakup`.`id` = '".intval($zak['id'])."'";
	$min=$DB->getAll($sql);
	  
	  $items_total_all=0;
	  $items_qnt_all=0;
	  $allsize=array(); 
	  foreach($allorder as $aord)
		{
		$query = "SELECT * FROM `sp_ryad` WHERE `id` = ".$aord['id_ryad'];
		$allryad=$DB->getAll($query);
		$query = "SELECT `sp_size`.`anonim`, `sp_size`.`name`,`sp_size`.`user`,
				`sp_ryad`.`size`,`sp_ryad`.`spec`
			  FROM `sp_size` 
			  JOIN `sp_ryad` ON `sp_size`.`id_ryad`=`sp_ryad`.`id`
			  WHERE `sp_size`.`id` = ".$aord['id_order'];
		$allsize_tmp=$DB->getAll($query);
		$items_total_all=$items_total_all+($allryad[0]['price']*$aord['kolvo']*$min[0]['curs'])/$min[0]['min']*100;
		$items_qnt_all = $items_qnt_all + ($aord['kolvo']/$min[0]['min'])*100;
		
		if ($items_total_all<100) $items_total_all=$items_total_all;
		else $items_total_all=100;		
		
		if ($items_qnt_all<100) $items_qnt_all=$items_qnt_all;
		else $items_qnt_all=100;
		
		}
	?>
	Собрано:
        <!-- графический индикатор --->
        <div style="margin-left: 20px;">
            <progress max="100"  value="<?  switch ($min[0]['minType']){
                case 0 : echo floor ($items_total_all) ;  break;
                case 1 : echo floor ($items_qnt_all)   ;  break;
                case 2 : echo floor ($items_qnt_all)   ;  break;}?>" style="width: 150px;">
            </progress>
            <?  switch ($min[0]['minType']){
                case 0 : echo floor ($items_total_all) ;  break;
                case 1 : echo floor ($items_qnt_all)   ;  break;
                case 2 : echo floor ($items_qnt_all)   ;  break;
            }?>%
        </div>
        <!-- графический индикатор --->


	</div> 
<!-- вывод процентов сбора заказов на первую страницу --->	
		
</div>


	
</div>
<?endforeach;?>

<!-- Нижние кнопки страниц 
<div>
<?if ($total>1) echo '<div class="pagenation" align="center">'
		.$pervpage .$page4left .$page3left .$page2left .$page1left.
		'<span>'.$page.'</span>'.$page1right .$page2right .$page3right .$page4right .$nextpage.'</div>';?>
</div> -->

<div>

<?if(empty($component)):?>



<?endif?>
   <?else:?>
    <div class="menu-body5">
	<p>В этом городе, в данной категории закупок пока нет.</p>
	<p><a class="link4" href="/every/all">...Смотреть все закупки</a></p>
	<h1>Информация</h1>
	<ul>
	<li>Вы можете стать Организатором и добавить свою закупку</li>
	<li>Вашим предложениям будут рады на <a href="/forum" class="link4">Форуме</a></li>
	</ul>
   </div>
   <?endif;?>

 </div>