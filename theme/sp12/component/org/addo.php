<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('userID')>0):?>
<?if(empty($oke)):?>

<script 
	src="/<?=$theme?>js/tinymce4/tinymce.min.js">
</script>
<script>
        
	tinymce.init
({
	selector:"#textarea1", language:"ru",

	font_formats:
				"Andale Mono=andale mono,times;"+
        "Arial=arial,helvetica,sans-serif;"+
        "Arial Black=arial black,avant garde;"+
        "Book Antiqua=book antiqua,palatino;"+
        "Comic Sans MS=comic sans ms,sans-serif;"+
        "Courier New=courier new,courier;"+
        "Georgia=georgia,palatino;"+
        "Helvetica=helvetica;"+
        "Impact=impact,chicago;"+
        "Symbol=symbol;"+
        "Tahoma=tahoma,arial,helvetica,sans-serif;"+
        "Terminal=terminal,monaco;"+
        "Times New Roman=times new roman,times;"+
        "Trebuchet MS=trebuchet ms,geneva;"+
        "Verdana=verdana,geneva;"+
        "Webdings=webdings;"+
        "Wingdings=wingdings,zapf dingbats",
	fontsize_formats : "8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt",
	plugins : "autolink autoresize smileys textcolor link image lists charmap pagebreak preview ",
	toolbar : "undo redo | forecolor backcolor smileys link image pagebreak |  | fontselect | fontsizeselect",
	contextmenu : "link image insertable | cell row column deletable",
	pagebreak_separator: "<!-- my page break -->",
	image_advtab: true,
	relative_urls: false, remove_script_host: true,
	});
</script>


<div class="menu-top5">Добавить новый заказ - <?=$zakup[0]['title'].' - '.$zakup[0]['city_name_ru'];?></div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>


   <div class="block1">
       <table>
           <tr>
               <td width="135" valign="top">
                   <img src="<?= $img_path ?>" width="125" height="100" alt="" border="0" align="left" class="photo"/>
               </td>
               <td valign="top">
                   <div class="text_body_full">
                       <span class="newstitle"><?= $zakup[0]['title']; ?></span>
                       <table>
                           <tr>
                               <td width="250">
                                   <b>Организатор</b>: <a href="/com/profile/default/<?= $zakup[0]['user'] ?>"
                                                          class="link4"><?= $zakup[0]['username'] ?></a><br>
                                   <b>Город</b>: <?= $zakup[0]['city_name_ru'] ?><br/>
                                   <b>Статус</b>:
                                   <? if ($zakup[0]['user'] == $user->get_property('userID')): ?>
                                       <a href="#" onclick="openClose('statuslist');"><img
                                                   src="/<?= $theme ?>images/2downarrow.png" border="0" alt=""
                                                   height="16" width="16" title="изменить статус"></a>
                                       <div id="statuslist">
                                           <b>Изменить статус:</b><br/>
                                           <? if ($zakup[0]['status'] == 0 or $zakup[0]['status'] == 1) { ?><a
                                               href="/com/org/status/<?= intval($_GET['value']) ?>/1"><?= $statuslist[0]['name'] ?></a><? } ?>
                                           <? if ($zakup[0]['status'] <= 2) { ?><a
                                               href="/com/org/status/<?= intval($_GET['value']) ?>/2"><?= $statuslist[1]['name'] ?></a><? } ?>
                                           <? if ($zakup[0]['status'] == 3) { ?><a
                                               href="/com/org/status/<?= intval($_GET['value']) ?>/3"><?= $statuslist[2]['name'] ?></a><? } ?>
                                           <? if ($zakup[0]['status'] >= 3 AND $zakup[0]['status'] <= 5) { ?><a
                                               href="/com/org/status/<?= intval($_GET['value']) ?>/4"><?= $statuslist[3]['name'] ?></a><? } ?>
                                           <? if ($zakup[0]['status'] >= 4 AND $zakup[0]['status'] <= 5) { ?><a
                                               href="/com/org/status/<?= intval($_GET['value']) ?>/5"><?= $statuslist[4]['name'] ?></a><? } ?>
                                           <? if ($zakup[0]['status'] == 4 OR $zakup[0]['status'] == 6) { ?><a
                                               href="/com/org/status/<?= intval($_GET['value']) ?>/6"><?= $statuslist[5]['name'] ?></a><? } ?>
                                           <? if ($zakup[0]['status'] == 6 OR $zakup[0]['status'] == 7) { ?><a
                                               href="/com/org/status/<?= intval($_GET['value']) ?>/7"><?= $statuslist[6]['name'] ?></a><? } ?>
                                           <? if ($zakup[0]['status'] == 7 OR $zakup[0]['status'] == 8) { ?><a
                                               href="/com/org/status/<?= intval($_GET['value']) ?>/8"><?= $statuslist[7]['name'] ?></a><? } ?>
                                           <? if ($zakup[0]['status'] == 8 OR $zakup[0]['status'] == 9) { ?><a
                                               href="/com/org/status/<?= intval($_GET['value']) ?>/9"><?= $statuslist[8]['name'] ?></a><? } ?>
                                       </div>

                                   <? endif ?>
                                   <?= $zakup[0]['name']; ?>, <?= $total_order_zp; ?> заказов<br/>
                                   <b>Уровень доступа</b>: <?= $zakup[0]['levname']; ?><br/>
                   </div>
               </td>
               <td valign="top">
                   <b>Оргсбор</b>: <?= $zakup[0]['proc'] ?>%<br/>
                   <b>Минималка</b>: <span class="price"><?= $zakup[0]['min'] ?></span> <?= $registry['valut_name'] ?>
                   <br/>

               </td>
           </tr>
       </table>
       <b style="color:#F4A422">Информация</b>: <? if (!empty($zakup[0]['inform'])): ?><?= $zakup[0]['inform']; ?><? else: ?> <?= $zakup[0]['name']; ?><? endif ?>
       <br/>
       <? if ($zakup[0]['russia'][0] > 0): ?>
           &nbsp;<br/>
           <b>Отправка в регионы России</b><br/>
           <p>
               <? $i = 0;
               foreach ($delivery as $del):$i++; ?>
                   <? if (in_array($del['id'], $zakup[0]['russia'])): ?><img
                       src="/<?= $theme ?>images/delivery/<?= $del['img'] ?>" width="60" width="32"
                       alt="<?= $del['name'] ?>" title="<?= $del['name'] ?>" class="deliverimg" border="1"/><? endif; ?>
               <? endforeach; ?>
           </p>
       <? endif; ?>
       </td>
       </tr>
       </table>
   </div>

  <div class="text-post">
	<h2><big>Описание закупки:</big></h2>
  	<a href="javascript://" onclick="$('.details').slideToggle('slow');"> 
	<input type="button" class="pushme" value="Нажми меня" ></input></a>
	
	<div class="line3"></div>
	<div style="padding-top: 20px" class="details">
		<?=$zakup[0]['text'];?>
  </div>


  <div class="line3"></div>

<?
if($zakup[0]['status']==3 or $zakup[0]['status']==5){
if(floatval($zakup[0]['curs'])==0)$zakup[0]['curs']=1;
?>
	<p>Если в закупке нет рядка с товаром который бы вы хотели приобрести, но при этом он есть на сайте поставщика, то вы можете самостоятельно отправить заявку на приобретение товара (Добавить в корзину). Для этого следует заполнить приведенную ниже форму с указанием названия, артикула, стоимости и размера. После добавления в корзину, у Организатора в списке заказов появится ваш заказ.</p>

<form action="" method="post">
		<input type="hidden" name="action" value="addo">
		<input type="hidden" name="idpost" value="<?=intval($_GET['value']);?>">
		<table summary="" class="inputbox-container">
			<tr>
			<td valign="top"><b>Название</b></td>
			<td><input type="text" class="inputbox" name="title" value=""/> <!--sp_ryad.title-->
			<br/>пример: Кофта вязанная
			</td>
		</tr>
		<tr>
			<td valign="top">Артикул</td>
			<td><input type="text" class="inputbox" name="articul" value=""/> <!--sp_ryad.articul-->
			<br/>пример: 110-02
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Цена</b></td>
			<td><input type="text" class="inputbox" name="price" value=""/> у.е. <!--sp_ryad.price-->
			<br/> курс в закупке 1 у.е. = <?=$zakup[0]['curs']?> руб.
		</td>
			</tr>
			<tr>
			<td valign="top">Размер</td>
			<td><input type="text" class="inputbox" name="size" value=""/> <!--sp_ryad.size-->
			<br/>пример: 42-44
			</td>
		</tr>
		<tr>
			<td valign="top">Цвет</td>
			<td><input type="text" class="inputbox" name="color" value=""> <!--sp_order.color-->
			<br/>пример: красный
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Количество</b></td>
			<td><input type="text" class="inputbox" name="kolvo" value="1"> <!--sp_order.kolvo -->
			<br/>пример: 3
			</td>
		</tr>
		<tr>
			<td valign="top">Дополнительно</td>
			<td>
			<textarea  id="textarea1" class="extra" placeholder="После вставки ссылки, нажми пробел!" name="textarea1"></textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
			<input name="isAnonim" type="checkbox" value="1">Анонимно (Ваше имя в заказе сможет видеть только Организатор данной закупки)
			</td>
		</tr>
		</table><br/>
    <div style="margin-top: 20px">
        <a href="/com/org/open/<?=intval($_GET['value']);?>" class="btn-cancel"> <span class="text-cancel">Oтмена</span> </a>
		<input type="submit" class="btn-addOrder" value="Добавить заказ">
    </div>
</form>

        <?}else{?>
<p>Вы не можете совершить заказ в данной закупке.</p>
<?}?>
</div>
   
<!-- <p><a href="/com/org/open/<?=intval($_GET['value']);?>" class="btn">Вернуться к просмотру закупки</a></p>   -->
   
<?elseif(!empty($oke)):?>
<div class="menu-top5">Новый заказ успешно добавлен</div>
<div class="menu-body5">
<h1>Спасибо! Ваш заказ добавлен в корзину.</h1>

<p><a class="btn" href="/com/org/open/<?=intval($_GET['value']);?>">...Вернуться к закупке</a></p>

<p>Информация:</p>
<ul>
<li>Ваш заказ помещен в <a class="link4" href="/com/basket">Корзину</a>, вы можете отказаться от него до тех пор пока закупка находится в статусе "Открыта". После перехода в статус "Стоп" вы не сможете отказаться от заказа.</li>
</ul>
</div>
<?endif?>

<?else:?>
<?@include('.access.php');?>
<?endif?>