<?defined('_JEXEC') or die('Restricted access');?>

<?if ( !$user->is_loaded() ):?>
<div class="menu-top5">Вход в систему</div>
<div class="menu-body5">
<form method="post" action="" />
<table class="regs-table">
	 <tr><td>Логин: </td><td><input type="text" class="inputbox" name="uname" /></td></tr>
	 <tr><td>Пароль: </td><td><input type="password" class="inputbox" name="pwd" /></td></tr>
</table>

<p align="center" style="width:460px">
	<input type="hidden" name="event" value="signup"/>
	<input type="submit" class="button" value="Войти" />
</p>
</form>
	<p align="left" class="recovery">
	<a href="/forum/login.php?action=forget" class="link4">Забыли пароль?</a> | <a href="/forum/register.php"  class="link4">Регистрация</a><br>
	</p>
</div>
<?else:?>
<div class="menu-top5">Выход из системы</div>
<div class="menu-body5">

<p align="center" class="recovery">
	<a href="/com/setup/" class="out-link">Сменить пароль</a>
	<a href="<?=$_SERVER['PHP_SELF']?>?logout=1" class="out-link">Выход</a>
</p>
</div>
<?endif;?>