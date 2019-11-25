<?defined('_JEXEC') or die('Restricted access');?>
<?if ( @!$user->is_loaded() ):?>

<div id="login">
<p align="left">Login</p>
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<div class="input">
	<p id="form-login-username">
	 <label for="uname">логин<br/>
	  <input name="uname" id="uname" class="inputbox" alt="username" size="18" type="text"/>
	 </label>
	</p>
	<p id="form-login-password">
	 <label for="pwd">пароль<br/>
	  <input name="pwd" id="pwd" class="inputbox" size="18" alt="password" type="password"/>
	 </label>
	</p>
	 Запомнить? <input type="checkbox" name="remember" value="1" /><br /><br />
	<input name="Submit" class="button" value="Login" type="submit"/>
	</div>
	<a href="http://spmagaz.com/">Сайт совместных покупок 2017г.</a>
	</form></div>
<?else:?>
	Здорово <b><?=$user->get_property('username');?>!</b>
	<?if ($user->is_active()):?>
		<?if ($user->get_property('userID')==1 OR $user->get_property('gid')==25):?>

<style>	
	.small_button {
		color:black;
		background-image: url(../partner/images/left_admin_menu.gif)
		}
</style>

			<p>
                        <a href="/forum/admin/index.php" class="small_button">Админка форума</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=settings" class="small_button">Настройки сайта</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=users" class="small_button">Пользователи</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=org" class="small_button">Организаторы</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=office" class="small_button">Офисы (ЦР)</a>
			</p>

			<p>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=zakup" class="small_button">Закупки</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=catzp" class="small_button">Категории закупок</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=report" class="small_button">Отчеты</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=orgorders" class="small_button">Управление счетами</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=static" class="small_button">Статистика</a>
			</p>

		<?endif?>
		<?if ($user->get_property('gid')>=22):?>
			<p>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=article" class="small_button">Опубликовать запись</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=category" class="small_button">Категории записей</a>
		<?endif;?>
			<p>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=groupsblog" class="small_button">Рубрики групп</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=blog" class="small_button">Записи в блогах</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=concurs" class="small_button">Конкурсы</a>
			</p>
			<p>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=comment" class="small_button">Комментарии</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=votes" class="small_button">Опросы</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=links" class="small_button">Реклама на сайте</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=mail" class="small_button">Рассылки e-mail</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=mailSMS" class="small_button">Рассылки СМС</a>
			<a href="<?=$_SERVER['PHP_SELF']?>?component=users&section=history" class="small_button">История счетов</a>
			</p>
			
	<?endif;?>
	<a href="<?=$_SERVER['PHP_SELF']?>?logout=1">До скорого!</a>
</div>	
<?endif;?>
