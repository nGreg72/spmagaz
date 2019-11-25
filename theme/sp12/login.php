<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title><?@include($theme.'title.php')?></title>
<link href="/<?=$theme;?>css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" 
type="text/javascript" src="/<?=$theme;?>js/func.js"></script>

</head>
<body>
<div id="container">
	<div id="wrapper">
	  <div id="content">

	<div id="login" class="laccess">
	 <form action="/index.php" method="post" id="form-login">
            <input id="modlgn_username" name="uname" class="inputbox wl2" size="20" alt="username" value="Логин" onfocus="doClear(this)" onblur="if (this.value==''){this.value='Логин'}" type="text"/>
            <input id="modlgn_passwd" name="pwd" class="inputbox wl2" size="20" alt="password" value="........." onfocus="doClear(this)" onblur="if (this.value==''){this.value='.........'}" type="password"/>
            <input name="Submit" class="inputbox_signin" value="Вход" title="Вход в систему" type="submit"/>
	    <a href="/forum/register.php" class="reg-a">Регистрация</a><span class="slash">|</span>
	    <a href="/forum/login.php?action=forget" class="conf-a">Забыли пароль?</a>
        </form>	
	</div>

	  </div>
	</div>
</div>
</body>
</html>