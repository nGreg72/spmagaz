<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('gid')>=23):?>
<?if(empty($message)):?>
<div class="menu-top5">Организаторская: Изменить позиции</div>
<div class="menu-body5">
	<form action="" method="post" enctype="multipart/form-data" id="sendeform" name="sendeform">
	<input type="hidden" name="action" value="edpos">
	<input type="hidden" name="idpost" value="<?=$idpost;?>">
	<input type="hidden" name="edpos" value="<?=$edpos;?>">
	<table summary="">
	<tr><td></td><td>
	 <table class="table-sizes" summary="">
	 <tbody><tr class="sizes">
	 <?foreach ($items_size as $item_s):?>
				<?if ($item_s['name']>''):?><td><?=$item_s['name']?></td><?endif;?>
	 <?endforeach;?>
	 </tr><tr>
	 <?foreach ($items_size as $item_s):?>
		<td><a href="/com/org/delpos/<?=$item_s['id']?>/<?=$edpos?>/<?=$idpost?>">
		<img src="/<?=$theme?>images/del.png" alt="" title="удалить позицию" height="16" width="16" onclick="if (!confirm(\'Вы подтверждаете удаление размера?\')) return false;"></a></td>
	 <?endforeach;?>
	</tr></table>
	</td>
	</tr> 	
        <tr>
	<td class="title"><b>Размеры или количество.</b></td>
	<td><input class="inputbox" style="width: 400px;" name="size" value="" type="text"><br>
	<?=$tp?>
	</td>
	</tr> 	
	<tr>
	<td>
		<a class="cancel-button" href="/com/org/open/<?=intval($_GET['value2']);?>">Назад</a>
	</td>
	<td align="right">
		<input type="submit" class="ok-button" value="Добавить">
	</td>
	</tr>
	</table>
	</form>
</div>
<?else:?>
<div class="menu-top5">Ошибка.</div>
<div class="menu-body5">
	<h1>Вы не можете редактировать данную закупку (рядок)</h1>
   <div style="display:block" class="message"><?=$message;?></div>
	<p><a href="/" class="link4">...На главную.</a></p>
	<p>Возможные причины ошибки:</p>
	<ul>
	<li>Время сессии авторизации истекло.</li>
	<li>Вы пытаетесь попасть в раздел только для зарегистрированных пользователей.</li>
	<li>Вы пытаетесь редактировать чужую закупку.</li>
	<li>Такой закупки не существует.</li>
	</ul>
</div>
<?endif?>

<?else:?>
<?@include('.access.php');?>
<?endif?>