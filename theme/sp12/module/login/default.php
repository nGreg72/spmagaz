<? defined('_JEXEC') or die('Restricted access'); ?>
<? if (!$user->is_loaded()): ?>
    <div id="login">
        <form action="/" method="post" id="form-login">
            <input id="modlgn_username" name="uname"  alt="username" value="Логин"
                   onfocus="doClear(this)" onblur="if (this.value==''){this.value='Логин'}" type="text"/>
            <input id="modlgn_passwd" name="pwd" alt="password" value="........."
                   onfocus="doClear(this)" onblur="if (this.value==''){this.value='.........'}" type="password"/>
            <input name="Submit" class="inputbox_signin" value="Вход" title="Вход в систему" type="submit"/>
            <a href="/forum/register.php" class="reg-a">Регистрация</a>
<!--            <a href="/forum/login.php?action=forget" class="conf-a">Забыли пароль?</a>-->
        </form>
    </div>
<? else:
    if ($user->get_property('display_name')) $username = $user->get_property('display_name'); else $username = $user->get_property('username');
    ?>
    <div id="login">
        <span id="hello">Вы зашли как: <br><span class="hello-name"><?= $username ?></span></br></span>
        <? if ($user->get_property('gid') == 25):?>
            <a href="/partner/index.php" class="adminlink">Администрирование</a>
            <!--	<a href="/" class="adminlink">Администрирование</a> -->
        <? endif; ?>
        <p align="right"><a href="/?logout=1" class="logout">Выход</a></p>
    </div>

<? endif; ?>