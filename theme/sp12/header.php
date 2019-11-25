/* <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> */
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name=viewport content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><? @include($theme . 'title.php') ?></title>
    <link href="/<?= $theme; ?>css/style.css" rel="stylesheet" type="text/css"/>
    <link href="/<?= $theme; ?>css/tabbs.css" rel="stylesheet" type="text/css"/>
    <link href="/<?= $theme; ?>css/floats.css" rel="stylesheet" type="text/css"/>
	<link href="/<?= $theme; ?>css/media.css" rel="stylesheet" type="text/css"/>
    <!--<link rel="stylesheet" href="/<?= $theme; ?>css/lightbox.css" type="text/css" media="screen" />-->
    <link href="/<?= $theme; ?>css/jquery.lightbox-0.5.css" rel="stylesheet" type="text/css"/>
    <meta name="keywords" content="<?= $config['metakey'] ?>"/>
    <meta name="description" content="<?= $config['metadesc'] ?>"/>
    <meta name='loginza-verification' content='0b0c051214a5af36570deeaa2b47753a'/>
    <link rel="shortcut icon" href="/<?= $theme; ?>images/Site_Icon.ico" type="image/x-icon"/>
    <link rel="icon" href="/<?= $theme; ?>images/Site_Icon.ico" type="image/x-icon"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
    <script type="text/javascript">var jQuery_1_4_1 = jQuery.noConflict();</script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script>
        /**
         * Функция, которая отслеживает клики по исходящим ссылк в Analytics.
         * Эта функция применяет в качестве аргумента строку с действительным URL, после чего использует ее
         * как ярлык события. Если указать beacon в качестве метода передачи, данные обращений
         * будут отправляться с использованием метода navigator.sendBeacon в поддерживающих его браузерах.
         */
        var trackOutboundLink = function (url) {
            ga('send', 'event', 'outbound', 'click', url, {
                'transport': 'beacon',
                'hitCallback': function () {
                    document.location = url;
                }
            });
        }
    </script>


    <script language="JavaScript" type="text/javascript"
            src="/<?= $theme ?>js/jquery-ui-1.8.18.custom.min.js"></script>
    <!--<script language="JavaScript" type="text/javascript"
	src="/<?= $theme; ?>js/lightbox.js"></script>-->
    <script language="JavaScript" type="text/javascript"
            src="/<?= $theme; ?>js/jquery.cookie.js"></script>

    <script type="text/javascript"
            src="/<?= $theme; ?>js/jquery.lightbox-0.5.js"></script>


    <script language="JavaScript"
            type="text/javascript" src="/<?= $theme; ?>js/func.js"></script>
    <script language="JavaScript"
            type="text/javascript" src="/<?= $theme; ?>js/tabbs.js"></script>

    <script type="text/javascript" src="/<?= $theme; ?>js/softScroll.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            $("a").each(function () {
                if ($(this).attr("href").indexOf('http://') + 1 || $(this).attr("href").indexOf('https://') + 1) $(this).attr("target", "_blank");
            });


            $(".ryadok img").each(function () {
                var width = $(this).attr("width");
                var src = $(this).attr("src");
                var clas = $(this).parent().attr("rel");

                if (width >= 150) {
                    $(this).attr("width", "150");
                    $(this).css('width', '150px');
                }
                $(this).attr('height', '');
                sr = explode('=', src);

                sr[1] = 'http://' + sr[1];
                if (sr[1] == 'undefined' || sr[1] == '' || strpos(src, "=") == 0) {
                    //sr[1]=sr[0];
                    sect = explode('/', sr[0]);
                    sr[1] = sect[0] + '/' + sect[1] + '/' + sect[2] + '/0/0/' + sect[5] + '/' + sect[6] + '/';
                }
                if (clas != "lightbox") {
                    if (this.parentElement.tagName == 'a' || this.parentElement.tagName == 'A') $(this).unwrap("a");
                    $(this).wrap('<a rel="lightbox" href="' + sr[1] + '">');
                }
            });
            $("a[rel*=lightbox]").lightBox({
                txtImage: 'Изображение',
                txtOf: 'из'
            });


        });
    </script>
    <link href="/<?= $theme; ?>css/dd.css" rel="stylesheet" type="text/css"/>
    <link href="/<?= $theme; ?>css/voteimg.css" rel="stylesheet" type="text/css"/>
    <link href="/<?= $theme; ?>css/corusel.css" rel="stylesheet" type="text/css"/>

    <!-- <link href="/<?= $theme; ?>css/payment.css" rel="stylesheet" type="text/css" /> -->


    <!-- Put this script tag to the <head> of your page -->
    <script type="text/javascript" src="//vk.com/js/api/openapi.js?115"></script>

    <script type="text/javascript">
        VK.init({apiId: 4654181, onlyWidgets: true});
    </script>
    <!--  menu  -->

</head>
<body>
<? foreach ($registry['ads3'] as $link): ?>
    <? if ($link['type'] == 1): ?>
        <? if ($link['noindex'] == 1): ?><noindex><? endif; ?>
        <? if ($link['show'] == 1): ?><a class="link1" target="_blank"
                                         href="<?= $link['url'] ?>" <? if ($link['nofollow'] == 1): ?>rel="nofollow"<? endif; ?>><?= $link['ankor'] ?></a><? endif; ?>
        <? if ($link['noindex'] == 1): ?></noindex><? endif; ?>
    <? endif; ?>
    <? if ($link['type'] == 2): ?>
        <? if ($link['noindex'] == 1): ?><noindex><? endif; ?>
        <? if ($link['show'] == 1): ?><a class="link1" target="_blank"
                                         href="<?= $link['url'] ?>" <? if ($link['nofollow'] == 1): ?>rel="nofollow"<? endif; ?>>
            <img src="<?= str_replace('../', '/', $link['photo']) ?>" title="<?= $link['ankor'] ?>"
                 alt="<?= $link['ankor'] ?>"/></a><? endif; ?>
        <? if ($link['noindex'] == 1): ?></noindex><? endif; ?>
    <? endif; ?>
    <? if ($link['type'] == 3): ?>
        <? if ($link['show'] == 1): ?><?= $link['ankor'] ?><? endif; ?>
    <? endif; ?>
<? endforeach; ?>

<div id="container">
    <div id="header">
        <a href="/" id="logo1_city"></a>
        <div id="logo2"></div>
        <div id="logo_smile"></div>

        <div><? @include($theme . 'module/login/default.php') ?></div>

        <!--Banner place! Insert text here!!!-->
        <div id="banner_head">
<!--            <div id="blink7" ;> Приглашайте друзей и знакомых!</div>-->
            <div id="banner_rek" ;></div>
        </div>

        <div>
            <div id="search">
                <form action="/com/search/" method="post" id="sform">
                    <div onclick="document.getElementById('sform').submit();" id="submit"></div>
                    <input class="search_input" name="search" value="Поиск по сайту" onfocus="doClear(this)"
                           onblur="if (this.value==''){this.value='Поиск по сайту'}" type="text"/>
                </form>
            </div>


            <div id="top-menu">
                <a href="/<? if ($sort_city > 0): ?><?= $sort_city ?>/all<? endif ?>"
                   <? if (empty($_GET['component']) or $_GET['component'] == 'frontpage'): ?>class="active"<? endif; ?>>Закупки</a>
                <a href="/doc/news.html"
                   <? if ($_GET['dcat'] == 'eto-interesno' or $_GET['dcat'] == 'news' or $_GET['dcat'] == 'sovmestnye-pokupki'): ?>class="active"<? endif; ?>>Новости</a>
                <a href="/com/hvastics/"
                   <? if ($_GET['component'] == 'hvastics'): ?>class="active"<? endif; ?>>Хвастики</a>
<!--                <a href="/forum/">Форум</a>-->
                <a href="/com/otziv/" <? if ($_GET['component'] == 'otziv'): ?>class="active"<? endif; ?>>Отзывы</a>
                <a href="/com/pristroy/"
                   <? if ($_GET['component'] == 'pristroy'): ?>class="active"<? endif; ?>>Пристрой</a>
                <a href="/com/help" <? if ($_GET['dcat'] == 'help'): ?>class="active"<? endif; ?>>Помощь</a>
                <a href="/com/allart/" <? if ($_GET['component'] == 'allart'): ?>class="active"<? endif; ?>>Статьи</a>
                <!--	<a href="/com/groups/" <? if ($_GET['component'] == 'groups'): ?>class="active"<? endif; ?>>Группы</a>			-->
                <!--	<a href="/com/blog/" <? if ($_GET['component'] == 'blog'): ?>class="active"<? endif; ?>>Дневники</a>			-->
                <!--	<a href="/com/concurs/" <? if ($_GET['component'] == 'concurs'): ?>class="active"<? endif; ?>>Конкурсы</a>		-->

                <span class="slash"></span>
                <a href="#" onclick="openClose('regionmenu');" class="region-a"><span>
		<? if ($sort_city > 0): ?>
            <? $i = 0;
            foreach ($all_city as $alc): ?>
                <? if ($sort_city == $alc['id_city']):$i = 1; ?><?= $alc['city_name_ru'] ?><? endif; ?>
            <? endforeach; ?>
            <? if ($i == 0): ?>Все города<? endif; ?>
        <? else: ?>
            Все города
        <? endif; ?>
                        &#8595;</span></a>
            </div>
        </div>


        <?// include($theme . 'module/region/default.php') ?> <!--     из-за этого виснет сайт -->
    </div>


