<?defined('_JEXEC') or die('Restricted access');?>
<div class="menu-top5">Ошибка доступа</div>
<div class="menu-body5">
<h1>У вас нет прав для доступа к данному разделу.</h1>
<?if ($user->get_property('gid')==0):?>
<p><a href="/forum/login.php" class="link4">Авторизируйтесь пожалуйста.</a></p>
<?endif?>
<p>Возможные причины ошибки:</p>
<ul>
<li>Вы не являетесь Организатором</li>
<li>Время сессии авторизации истекло</li>
<li>Вы пытаетесь попасть в раздел только для зарегистрированных пользователей</li>
</ul>
</div>