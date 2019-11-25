<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('userID')>0 and $user->get_property('gid')>=23):?>
<div class="menu-top5">Организаторская: Все закупки</div>
<div class="menu-body5">
   <div style="display: inline;" class="message"><?=$message;?></div>

   <?if($checkcity>0):?>
	<div id="sidebar" style="width:320px">
	<div><a href="/com/org/fixed">закрепить закупку</a></div>
    <div><a href="/com/org/add">создать закупку</a></div>
	</div>
   <div class="line3"></div>
       <h3>Фильтр</h3>
   <div style="display: inline;">
        <a href="#st1" class="org-status">Редактирование</a>
        <a href="#st2" class="org-status">Готова к открытию</a>
        <a href="#st3" class="org-status">Открыта</a>
        <a href="#st4" class="org-status">Стоп</a>
        <a href="#st5" class="org-status">Дозаказ</a>
        <a href="#st6" class="org-status">Оплата заказов</a> <p>
        <a href="#st7" class="org-status">Отправлена</a>
        <a href="#st8" class="org-status">Оплата за достаку</a>
        <a href="#st10" class="org-status">Розадача/Склад</a>
		<a href="#st9" class="org-status">Закрыта</a>
    </div>
	<div class="line3"></div>
	<?if(count($all_zakup)>0):
	  foreach($all_zakup as $all_z):
	  if($all_z['type']>=0):
	?>
	  <?if($all_z['name']<>$tmpname):?>
            <!--<h1 class="org-h1"><?=$all_z['name']?>  <?print_r($all_z['status']);?>  </h1>-->
            
                <?if($all_z['status']==1):?> <h1 class="menu-top5" id="st1"><?=$all_z['name']?></h1>               <!--Редактирование-->
                <?elseif ($all_z['status']==2):?><h1 class="menu-top5" id="st2"><?=$all_z['name']?></h1>        <!--Готова к открытию-->
                <?elseif ($all_z['status']==3):?><h1 class="menu-top5" id="st3"><?=$all_z['name']?></h1>        <!--Открыта-->
                <?elseif ($all_z['status']==4):?><h1 class="menu-top5" id="st4"><?=$all_z['name']?></h1>        <!--Стоп-->
                <?elseif ($all_z['status']==5):?><h1 class="menu-top5" id="st5"><?=$all_z['name']?></h1>        <!--Дозаказ-->
                <?elseif ($all_z['status']==6):?><h1 class="menu-top5" id="st6"><?=$all_z['name']?></h1>        <!--Оплата заказов-->
                <?elseif ($all_z['status']==7):?><h1 class="menu-top5" id="st7"><?=$all_z['name']?></h1>        <!--Отправлена-->
                <?elseif ($all_z['status']==8):?><h1 class="menu-top5" id="st8"><?=$all_z['name']?></h1>        <!--Оплата за достаку-->
                <?elseif ($all_z['status']==9):?><h1 class="menu-top5" id="st9"><?=$all_z['name']?></h1>        <!--Закрыта-->
                <?elseif ($all_z['status']==10):?><h1 class="menu-top5" id="st10"><?=$all_z['name']?></h1>      <!--Розадача/Склад-->
                
                <?endif?>
	  <div class="line4"></div> <!--добавлена линия-разделитель-->
	  <?endif;?>
	<?
	//img/uploads/zakup/62563689.jpg
	if(!empty($all_z['foto'])){
		$split=explode('/',$all_z['foto']);
		$img_path='/images/'.$split[2].'/125/100/1/'.$split[3];
		} else $img_path='/theme/sp12/images/no_photo125x100.png';
	
	?>
	  <table class="org-right"><tr>
		<td width="70"><img src="<?=$img_path?>" alt=""/></td>
		<td width="620">
            <a href="/com/org/open/<?=$all_z['id']?>" class="link4-org"><?=$all_z['title']?></a>
            <br/>
            <span>Открыта: <?=date('d-M-y',$all_z['date'])?>  / Закрыта:</span>
            <br><br>
            <span class="org-discr"><?=utf8_substr(strip_tags(html_entity_decode($all_z['text'])),0, 200)?></span>
            <p align="right" class="open-navi">
            <a href="/com/org/editzp/<?=$all_z['id'];?>" class="link7 editzp" style="margin:0;">Редактировать</a>
            </p>
		</td>
	  </tr>
      </table>
	  <?$tmpname=$all_z['name'];
	   endif;
	   endforeach;?>

	  <?foreach($all_zakup as $all_z):
	  if($all_z['type']==1):
		?>
	  <table class="org-right"><tr>
		<td width="70"></td><td width="620">
		<a href="/com/org/open/<?=$all_z['id']?>" class="link4"><?=$all_z['title']?></a><br/>
			<span class="org-discr"><?=utf8_substr(strip_tags(html_entity_decode($all_z['text'])),0, 200)?></span>
			<p align="right" class="open-navi">
			<a href="/com/org/editzp/<?=$all_z['id'];?>" class="link7 editzp" style="margin:0;">редактировать</a>
			</p>
			</td>
	  </tr></table>
	  <?endif;
	   endforeach;?>



	<?else:?>
	<p>У вас пока нет закупок. Но вы можете <a href="/com/org/add" class="link4">добавить</a> новую.</p>
	<p>После добавления <b>новой закупки</b> и добавления в нее <b>рядов</b>, установите статус зукупки - "<b>Готова к открытию</b>", 
	после проверки администратором закупка будет открыта для заказов.</p>
	<?endif?>
   <?else:?>
	<?@include('.city.php');?>
   <?endif?>
</div>

<div class="menu-top6">Выставленные счета</div>
<div class="menu-body5">
	<?if($registry['percent']>0 and count($registry['orgorder'])!=0):?>
	<table class="tab-admin-orgi"  style="border:1px solid #eee;width:100%;background:#fff;">
	<tr>	<td class="tab-td-2">Закупка</td>
		<td class="tab-td-2" style="width:100px" valign="top">Статус закупки</td>
		<td class="tab-td-2" style="width:100px" valign="top">Сумма заказа</td>
		<td class="tab-td-2" style="width:100px" valign="top">Заказы орга</td>
		<td class="tab-td-2" style="width:100px" valign="top">Счет на</td>
		<td class="tab-td-2" style="width:130px">Действие</td></tr>


		<?foreach ($registry['orgorder'] as $item):?>
		<tr><td class="tab-td-1" width="350">
			<table border="0"><tr><td>
			<img src="<?=$img_path?>" alt=""/></td>
			<td>
                        <a href="/com/org/open/<?=$item['id']?>" class="link1"><?=$item['title']?></a><br/>
			Заказов: <?=$item['countorder']?></a>
			</td></tr></table>
		</td>
		<td class="tab-td-1"><?=$item['statname']?></td>
		<td class="tab-td-1"><?=$item['summprice']?> руб.</td>  <!--Сумма заказа-->
        <td class="tab-td-1"><?=$item['orgBuy']?> руб.</td>  <!--Заказы орга-->
		<td class="tab-td-1">
            <?/*=$item['sum']*/?><!-- руб. <br>-->  <!--"Счёт на" без учёта заказов орга. Т.е., всё вместе-->
            <?=round(($item['summprice'] - $item['orgBuy']) / 100 * $registry['percent'], 0)?> </td>  <!--"Счёт на" заказы огра не учитываются-->
		<td class="tab-td-1" valign="">
			
			<?if($item['status_oo']==1):?>
			<a href="/com/org/addpayorg/<?=$item['id']?>/" class="link1">Уведомить об оплате</a>
			<?elseif($item['status_oo']==2):?>
				На модерации
			<?elseif($item['status_oo']==3):?>
				Оплачен
			<?endif;?>
		</td></tr>
		<?endforeach?>
	</table>

	<?else:?>
	<p>Счетов для оплаты нет.</p>
	<?endif?>
	<br/>
	<p><small>* В данном блоке отображаются выставленные счета от администрации, подлежащие оплате. 
(Сумма счета эквивалентна <?=$DB->getOne('SELECT `setting`.`value` 
				FROM `setting`
				WHERE `setting`.`name`=\'percent\'');
?>% от суммы подтвежденных завказов в закупке)</small></p>
</div>


<?else:?>
<?@include('.access.php');?>
<?endif?>
