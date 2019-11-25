<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('userID')>0):?>
<?if(empty($message) and empty($oke)):?>

<script 
		src="/<?=$theme?>js/tinymce4/tinymce.min.js">
	</script>
	
	<script>
    tinymce.init({
        height : "450",
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
	plugins : "smileys autolink textcolor link image jbimages lists charmap pagebreak preview",
	toolbar : "undo redo | forecolor backcolor | smileys | link image pagebreak |  | fontselect | fontsizeselect",
	contextmenu : "link image insertable | cell row column deletable",
	pagebreak_separator: "<!-- my page break -->",
	image_advtab: true,
	relative_urls: false, remove_script_host: true	,
	});
</script>


<div class="menu-top5">Добавить новый заказ</div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>
	<form action="" method="post">
		<input type="hidden" name="action" value="order">
		<input type="hidden" name="idpost" value="<?=intval($_GET['value']);?>">
		<?if(floatval($items[0]['curs'])==0) $items[0]['curs']=1;?>
		<table summary="" style="border-spacing: 0 10px;">
			<tr>
			<td valign="top" width="100"><b>Название</b></td>
			<td><input type="text" class="inputbox" style="width:400px;" name="title" value="<?=str_replace('"','\'',$items[0]['title']);?>" disabled="disabled"/>
			</td>
		</tr>
		<tr>
			<td valign="top">Артикул</td>
			<td><input type="text" class="inputbox" style="width:200px;" name="articul"  value="<?=$items[0]['articul'];?>" disabled>
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Цена</b></td>
			<td><input type="text" class="inputbox" style="width:200px;" name="price" value="<?=$items[0]['price']*$items[0]['curs'];?>" disabled="disabled"/> руп. </td>
		</tr>
		<tr>
			<td valign="top">Размер</td>
			<td><input type="text" class="inputbox" style="width:200px;" name="size"  value="<?=$items_order[0]['name'];?>" disabled="disabled"/>
			</td>
		</tr>
		<tr>
			<td valign="top">Цвет</td>
			<td><input type="text" class="inputbox" style="width:200px;" name="color"  value="<?=$items[0]['color'];?>">
			<br/>пример: красный
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Количество</b></td>
			<td><input type="text" class="inputbox" style="width:100px;" name="kolvo"  value="<?if(empty($items[0]['kolvo'])):?>1<?else:?><?=$items[0]['kolvo']?><?endif?>">
			<br/>пример: 3
			</td>
		</tr>
<!--		<tr>
			<td valign="top">Дополнительно</td>
			<td><textarea id="textarea1" class="tinymce" name="textarea1"></textarea></td>
		</tr>-->
		<tr>
			<td></td>
			<td>
			<input name="isAnonim" type="checkbox" value="1">Анонимно (Ваше имя в заказе сможет видеть только Организатор данной закупки)
			</td>
		</tr>
		</table><br/>
	<table border="0" width="100%">
		<tr class="last">
			<td>
			<a class="cancel-button" href="/com/org/open/<?=$items[0]['id_zp'];?>">Отмена</a>
			</td>
			<td align="right">
			<input type="submit" class="ok-button" style="width:150px" value="Добавить в корзину"  onclick="ga('send', 'event', 'order', 'click', 'заказ через корзинку');">
			</td>
		</tr>
	</table>
	</form>
</div>
<?elseif(!empty($message)):?>
<div class="menu-top5">Ошибка</div>
<div class="menu-body5">
<h1>Заказ данной позиции невозможен</h1>

   <div style="display:block" class="message"><?=$message;?></div>

<p><a class="btn" href="/com/org/ryad/<?=$items[0]['id'];?>/<?=$items_ryad[0]['id'];?>">Вернуться</a></p>
<!--<p><a class="btn" href="/com/org/open/--><?//=$items[0]['id'];?><!--">Вернуться к закупке</a></p>-->

<p>Вы можете:</p>
<ul>
<li>Заказать другой размер</li>
<li>Заказать другой артикул</li>
<li>Написать Организатору для уточнения</li>
</ul>
</div>
<?elseif(!empty($oke)):?>
<div class="menu-top5">Новый заказ успешно добавлен</div>
<div class="menu-body5">
<h1>Спасибо! Ваш заказ добавлен в корзину.</h1>

<p><a class="btn" href="/com/org/open/<?=$items[0]['id'];?>">Вернуться к закупке</a></p>

<p>Информация:</p>
<ul>
<li>Ваш заказ помещен в <a class="link4" href="/com/basket">Корзину</a>, вы можете отказаться от него до тех пор пока закупка находится в статусе "Открыта". После перехода в статус "Стоп" вы не сможете отказаться от заказа.</li>
</ul>
</div>
<?endif?>

<?else:?>
<?@include('.access.php');?>
<?endif?>