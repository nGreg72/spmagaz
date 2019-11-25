<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('userID')>0):?>
	<?if (count($all)>0):?>
		<?foreach($all as $num):?>
		<div class="message"><?=$message?></div>
		<h1>Мои данные</h1>
		<form method="post" action="" enctype="multipart/form-data"/>
		<?if (generate_avatar_markup($user->get_property('userID'),1)==''):
			$img_path='<img src="/'.$theme.'images/no_photo125x100.png" alt="" class="photo" width="'.$avator_width_profile.'" height="'.$avator_height_profile.'"/>'; 
			else:
			$img_path=generate_avatar_markup($user->get_property('userID'),true);
		endif;?>
<div id='tabname_1' onclick="showtab('1')" class='active'><u>Основные</u></div>
<div id='tabname_2' onclick="showtab('2')" class='nonactive'><u>Дополнительно</u></div>

	<div id='tabcontent_1' class='show'>
		<table class="user-table">
		<tr><td width="250" align="center" valign="top">
			<?=$img_path?><br/>
			<?if ($num['photo']>''):?><input type="checkbox" name="del_ava" value="1"/> удалить фото<br /><?endif;?>
			фото: <input type="file" name="photo"/>
		</td><td>
		<input type="hidden" name="username" value="<?=$num['username']?>"/>
		<input type="hidden" name="email" value="<?=$num['email']?>"/>
		<input type="hidden" name="event" value="signup"/>
		<input type="hidden" name="update" value="1"/>
		
		<table>
		<tr><td>Фамилия:</td><td><input class="inputbox" type="text" name="fam" value="<?=$num['family']?>"/></td></tr>
		<tr><td>Имя:</td><td><input class="inputbox" type="text" name="name" value="<?=$num['name']?>"/></td></tr>
		<tr><td>Отчество:</td><td><input class="inputbox" type="text" name="sr" value="<?=$num['name_two']?>"/></td></tr>
		<tr><td>Пароль:</td><td><input class="inputbox" type="password" name="pwd" value=""/></td></tr>
		<tr><td>Повторить пароль</td><td><input class="inputbox" type="password" name="pwd2" value=""/></td></tr>
		<tr><td>R-Кошелек</td><td><input class="inputbox" type="text" name="wm" value="<?=$num['wm']?>"/></td></tr>
		<tr><td>Коротко о себе</td><td><input class="inputbox" type="text" name="desc" value="<?=$num['desc']?>"/></td></tr>
		</table>
		</td></tr></table>
	</div>	
	<div id='tabcontent_2' class='hide'>
		<table class="user-table" width="680" style="padding:10px">
		<?$i=0;foreach ($name as $n):?>
		<tr><td valign="top"><?=$n?>:</td><td valign="top"><?=$form[$i]?></td></tr>
		<?$i++;endforeach;?>
		</table>
	</div>
<input type="submit" value="Сохранить" />
</form>
        	<?endforeach;?>
	<?endif?>
<?else:?>У вас нет прав для доступа в этот раздел. Авторизируйтесь пожалуйста.<?endif?>