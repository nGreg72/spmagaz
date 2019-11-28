<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('userID')>0 and $user->get_property('gid')>=23):?>
<div class="menu-top5">Организаторская: Удаление закупки</div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>

   <?if($checkcity>0):?>
	&nbsp;<br/>
	<p align="center">Вы подтверждаете удаление?</p>
	&nbsp;<br/>
	<center>
	<form action="" method="post">
		<input type="hidden" name="fixeddel" value="1"/>
		<input type="submit" name="submit" class="button" value="Нет"/>
	</form>
	<form action="" method="post">
		<input type="hidden" name="fixeddel" value="2"/>
		<input type="submit" name="submit" class="button2" value="Да"/>
	</form>	</center>
	&nbsp;<br/>
	<p><b>Внимания!</b><br/><ul>
	<li>Если в течении месяца новая закупка не будет переведена в статус "Открыта", она автоматически удалится из списка закрепленных закупок за вами.</li>
	<li>При удалении закупки из списка закрепленных, она также удалится с сайта и более не будет доступна для пользователей.</li>
	</ul>
	</p>
	&nbsp;<br/>

   <?else:?>
	<?@include('.city.php');?>
   <?endif?>
</div>
<?else:?>
<?@include('.access.php');?>
<?endif?>