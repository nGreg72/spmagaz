<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?= $page_title; ?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="generator" content="rcheCMS"/>
    <link href="<?= $theme_admin; ?>css/style.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $theme_admin; ?>css/navi.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $theme_admin; ?>css/tabbs.css" rel="stylesheet" type="text/css"/>
    <script language="JavaScript" type="text/javascript" src="<?= $theme_admin; ?>js/open.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= $theme_admin; ?>js/func.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= $theme_admin; ?>js/tabbs.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= $theme_admin; ?>js/jquery-3.2.1.min.js"></script>
    <link rel="shortcut icon" href="/<?= $theme; ?>images/Site_Icon.ico" type="image/x-icon"/>
</head>
<body>
<? if (!$user->is_loaded()) {
    require_once($theme_admin . 'module/login/default.php');
} else {
    ?>

    <div id="container">
        <? if ($user->get_property('userID') == 1 OR $user->get_property('gid') >= 22): ?>
            <div id="header">
                <a href="../index.php"
                   style="margin-top: 2px;
		padding-left: 27px;
		padding-top: 4px;
		float:left;width: 100px;
		height: 26px;
		background-image: url(../theme/sp12/images/button_login.png);
		background-repeat: no-repeat;
		background-size: 99px 25px;">На сайт</a>
                <a href="<?= $root_path ?>"
                   style="margin-top: 2px;
		margin-left: 10px;
		padding-left: 4px;
		padding-top: 4px;
		float:left;width: 100px;
		height: 26px;
		background-image: url(../theme/sp12/images/button_login.png);
		background-repeat: no-repeat;
		background-size: 99px 25px;">Админская зона</a>

                <a href="/?logout=1"
                   style="margin-top: -29px;
		margin-left: 850px;
		padding-left: 27px;
		padding-top: 4px;
		float:left;width: 100px;
		height: 26px;
		background-image: url(../theme/sp12/images/button_login.png);
		background-repeat: no-repeat;
		background-size: 99px 25px;">Нафиг</a>

            </div>
        <? endif; ?>
        <div id="wrapper">
            <div id="content">
                <? if ($err > 0) require_once($theme_admin . 'component/error/default.php'); ?>
                <? require_once($contents_view) ?>
            </div>
        </div>
        <!--    <div id="navigation" style="font:bold 14px arial; line-height:20px; text-indent: 5px; color: red">
        <? require_once($theme_admin . 'module/login/default.php') ?>
-->    </div>
    </div>

<? } ?>

<!-- GoogleAnalytics-->
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-57968694-1', 'auto');
    ga('send', 'pageview');

</script>
<!--GoogleAnalytics-->

</body>
</html>