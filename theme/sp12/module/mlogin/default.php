<? defined('_JEXEC') or die('Restricted access'); ?>

    <style>
        #blink2 {

            font: bold 15px arial;
            color: red;
            -webkit-animation: blink2 1s linear infinite;
            animation: blink2 1s linear infinite;
        }

        @-webkit-keyframes blink2 {
            100% {
                color: rgba(34, 34, 34, 0);
            }
        }

        @keyframes blink2 {
            100% {
                color: rgba(34, 34, 34, 0);
            }
        }
    </style>

<? if (!$user->is_loaded()): ?>
    <div class="menu-top">Как это работает?</div>
    <div class="howIt"></div>


    <!--
        <div class="menu-body">
        <span id="step1">Организатор выходит на оптового поставщика.</span>
        <span id="step2">Пользователи делают заказы, набирают рядки.</span>
        <span id="step3">Организатор делает заказ товаров у поставщика.</span>
        <span id="step4">Товары приходят, пользователи их получают.</span>
        </div>

    -->


<? else:
    $profile = $DB->getAll('SELECT `punbb_users`.*, `punbb_groups`.* ,`group`.`name` as `gname`,`countries`.`country_name_ru`
			FROM `punbb_users` 
			LEFT JOIN `punbb_groups` ON `punbb_users`.`group_id`=`punbb_groups`.`g_id`
			LEFT JOIN `group` ON `punbb_users`.`group_id`=`group`.`fgid`
			LEFT JOIN `countries` ON `punbb_users`.`country`=`countries`.`id_country`
			WHERE `punbb_users`.`id`=' . $user->get_property('userID'));
    $max_rate = $DB->getOne('SELECT `punbb_users`.`rate` FROM `punbb_users` ORDER by rate DESC LIMIT 1');
    if (count($profile) == 0) die('Внутренняя ошибка системы, обратитесь за помощью к администратору');
    $oneper = round($max_rate / 100, 2);
    $rate = round($profile[0]['rate'] / $oneper);

    /*if ($profile[0]['photo']==''): $img_path="/img/nofoto.png";
            else: //http://rche.ru/cms/images/1/100/100/1/83341552_ava.png
            $split=explode('/',$profile[0]['photo']);
            $img_path='/images/'.$split[3].'/140/140/1/'.$split[4];
            endif;
    */
    $total_mess = $DB->getOne('SELECT count(`message`.`id`)
			FROM `message` 
			WHERE `message`.`tresh`=\'0\' and `message`.`view`=\'0\' and `message`.`to`=\'' . $user->get_property('userID') . '\'');
    ?>
    <div class="menu1-left" style="">
    <div class="menu1-cont1">

        <? /*<img src="<?=$img_path;?>" alt="Фото профиля" />*/
        ?>

        <? if (generate_avatar_markup($user->get_property('userID')) == ''):
            $img_path = '<img src="/' . $theme . 'images/no_photo125x100.png" alt="" class="photo" width="99" />';
        else:
            $img_path = generate_avatar_markup($user->get_property('userID'), false, 99);
        endif; ?>
        <?= $img_path ?>

        <a href="/com/addart">Добавить материал</a>
        <!--	<a href="/com/blogpost">Написать в дневник</a>								-->
        <!--	<a href="/blog/<?= $user->get_property('userID') ?>">Мой дневник</a>			-->
        <a href="/com/message"><span title="Site messager.">Мои сообщения</span> <? if ($total_mess > 0):?> <span
                    id="blink2">(<?= $total_mess ?>)</span> <? endif; ?></a>

        <a href="/com/reviews">Мои отзывы</a>

        <? $sql = "SELECT 
	SUM((SELECT count(c.id) FROM comments c WHERE c.id > s.lastcomm and c.table=s.table and c.news = s.id_post)) as summ,
	count(id) as count
	FROM subs s 
	WHERE s.user='{$user->get_property('userID')}'";
        $total_subs = $DB->getAll($sql);
        ?>

        <? if ($total_subs[0]['count'] > 0):?>
            <a href="/com/sub/">Новые
                события <? if ($total_subs[0]['summ']):?>(<?= $total_subs[0]['summ'] ?>)<? endif ?></a>
        <? endif ?>

        <a href="/com/friends/">Мои друзья</a>
        <!--	<a href="/com/album/">Мои фотоальбомы</a>

            <a href="/com/feed/">Мои новости</a>
            <a href="/com/groups/my">Мои группы</a> -->
        <? if ($user->get_property('gid') == 18):?>
        <a href="/com/org/tender"
           <? if ($_GET['component'] == 'org' and $_GET['section'] == 'tender'): ?>class="active"<? endif;
        ?>>Заявка в Орги</a><? endif; ?>


        <!--	<a href="/com/friends/news">Новости друзей</a>
	<? if ($user->get_property('gid') == 23):?><a href="/com/profile/consul/<?= $user->get_property('userID') ?>">Мои консультации</a><? endif; ?>
	<? if ($user->get_property('gid') == 25):?><a href="/admin/index.php">Админпанель</a><? endif; ?>-->
    </div>
    <div class="menu1-cont2">
        <span class="profile-name"><?= $profile[0]['family']; ?> <?= $profile[0]['name']; ?> <?= $profile[0]['name_two']; ?></span>
        <span class="profile-status">Online</span>
        <a href="/com/profile/default/<?= $user->get_property('userID'); ?>" class="profile-full">Полный профиль</a>
        <div class="line1"></div>
        <span class="profile-rate">Рейтинг:</span>
        <div class="progress" title="Рейтинг <?= $profile[0]['rate'] ?>">
            <div class="progress_load" style="width: <?= $rate ?>px;"></div>
            <div class="progress_text">+<?= $profile[0]['rate'] ?></div>
        </div>
        <div class="line1"></div>
        <span class="profile-group">Группа:</span><span
                class="profile-groupname"><?= $profile[0]['g_user_title'] ?></span>
        <div class="line1"></div>
        <? if ($profile[0]['country'] > 0):?>
            <span class="profile-country">Страна:</span><img src="/<?= $theme; ?>images/country/ru.png" align="left"
                                                             width="16" height="14" alt=""/><span
                    class="profile-countryname"><?= $profile[0]['country_name_ru'] ?></span>
            <div class="line1"></div><? endif ?>
        <a class="a-but" href="/com/setup"><span class="menul"></span><span class="menur"></span>Ред. профиль</a>
        <!--<a class="a-but-grey" href="/?logout=1"><span class="menul-g"></span><span class="menur-g"></span>Выйти</a>
    </div>


	<!--<p align="center">Баланс: <a href="/com/setup/history/" class="link4" style="color:#222" title="История счета | Операции по счету">
	<b><?= $user->get_property('wm') ?></b> руб.</a> <? if ($user->get_property('userID') >= 2):?>(<a href="/com/setup/balance/" class="link4">пополнить</a>)<? endif ?></p>-->

    </div>
    <div class="fix-menu1"></div>
<? endif; ?>