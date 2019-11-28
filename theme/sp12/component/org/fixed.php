<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('userID')>0 and $user->get_property('gid')>=23):?>
<div class="menu-top5">Организаторская: Закрепление закупки</div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>
   <?if($checkcity>0):?>
	<?if(empty($_POST['action'])):?>

		<p>В этом разделе вы можете закрепить за собой закупку, указав при этом адрес сайта поставщика и краткое описание. Перед закреплением, введенный вами адрес сайта проверяется на предмет принадлежности закупки другому организатору в вашем городе.</p>
		<form action="" method="post">
		<input type="hidden" name="action" value="checked">
		<table summary="">
			<tr>
				<td class="title"><b>Адрес сайта (url)</b></td>
			<td>
				<input type="text" class="inputbox" style="width:320px;" name="url" value="">  <i>(пример: postavshik.ru)</i>
			</td>
			</tr>
		</table>
	
		<input type="submit" class="button" value="Проверить" />
		</form>
	
		<p>&nbsp;</p>
		<h1 class="newstitle">Ваши закрепленные закупки:</h1>
		<table style="border:1px solid #eee;width:100%;background:#fff;">
		<tr class="sizes"><td width="25%"><b>Адрес сайта (url)</b></td><td width="25%"><b>Бренд</b></td><td width="25%"><b>Описание</b></td><td width="15%"><b>Дата добавления</b></td><td></td></tr>
		<?foreach($myfixed as $item):?>
			<tr class="sizes"><td><?=$item['url']?></td>
			<td><?=$item['desc']?></td>
			<td><?=$item['brend']?></td>
			<td><?=date('d.m.Y',$item['date'])?></td>
			<td>
			<a href="/com/org/fixeddel/<?=$item['id']?>"><img src="/<?=$theme?>images/cross.png" width="16" height="16" alt="" border="0"/></a>
			</td>
			</tr>
		<?endforeach;?>
		</table>
		&nbsp;<br/>
		<p><b>Внимание!</b><br/><ul>
		<li>Если в течении месяца новая закупка не будет переведена в статус "Открыта", она автоматически удалится из списка закрепленных закупок за вами.</li>
		<li>При удалении закупки из списка закрепленных, она также удалится с сайта и более не будет доступна для пользователей.</li>
		</ul>
		</p>
		&nbsp;<br/>
		<p><a class="link4" href="/com/org/">...В организаторскую</a></p>
	<?elseif($_POST['action']=='checked'):?>
	      <?if(count($testcheck)==0):?>
		<p>Закупка не за кем не закреплена, вы можете закрепить ее за собой.</p>
		<form action="" method="post">
		<input type="hidden" name="action" value="fixedadd">
		<table summary="">
			<tr><td class="title"><b>Адрес сайта (url)</b></td>
			<td><input type="text" class="inputbox" name="url" value="<?=$_POST['url']?>">  <i>(пример: postavshik.ru)</i></td>
			</tr>
			<tr><td class="title"><b>Бренд</b></td>
			<td><input type="text" class="inputbox" name="brend" value="<?=$_POST['brend']?>">  <i>(пример: Капика)</i></td>
			</tr>
			<tr><td class="title"><b>Краткое описание закупки</b></td>
			<td><input type="text" class="inputbox" style="width:320px;" name="desc" value="">  <i>(250 символов максимум)</i></td>
			</tr>
		</table>

		<table border="0" width="100%">
			<tr class="last">
				<td>
				<a class="link4" href="/com/org/fixed">Отмена</a>
				</td>
				<td align="right">
				<input type="submit" class="button" value="Закрепить" />
				</td>
				</tr>
			</table>
		</form>
	      <?else:?>

		<p>Закупка в вашем регионе уже закреплена за организатором. <a class="link4" onclick="if (!confirm(\'Вы подтверждаете удаление?\')) return false;" href="/com/profile/default/<?=$testcheck[0]['id']?>"><?=$testcheck[0]['username']?></a></p>
		<p><a class="link4" href="/com/org/fixed">...Вернуться</a></p>
	      <?endif;?>
	<?elseif($_POST['action']=='fixedadd'):?>
	      <?if(empty($message)):?>
		<p>Закупка успешно закреплена за вами.</p>
		<p><a class="link4" href="/com/org/fixed">...Вернуться</a></p>
	      <?endif;?>
	<?endif;?>
   <?else:?>
	<?@include('.city.php');?>
  <?endif;?>
</div>
<?else:?>
<?@include('.access.php');?>
<?endif?>