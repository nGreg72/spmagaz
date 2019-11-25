<? defined('_JEXEC') or die('Restricted access');
if ($user->is_loaded()):
    $profile = $DB->getAll('SELECT `punbb_users`.* ,`punbb_groups`.`g_title` AS `gname`
			FROM `punbb_users` 
			LEFT JOIN `punbb_groups` ON `punbb_users`.`group_id`=`punbb_groups`.`g_id`
			WHERE `punbb_users`.`id`=' . $user->get_property('userID'));
//if(count($profile)==0) die('Внутренняя ошибка системы, обратитесь за помощью к администратору');
    $total_mess = $DB->getOne('SELECT count(`message`.`id`)
			FROM `message` 
			WHERE `message`.`tresh`=\'0\' AND `message`.`view`=\'0\' AND `message`.`to`=\'' . $user->get_property('userID') . '\'');
    $total_basket = $DB->getOne('SELECT count(`sp_order`.`id`)
			FROM `sp_order` 
			LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
			WHERE `sp_zakup`.`status`>2 AND `sp_zakup`.`status`<9 
			AND `sp_order`.`user`=\'' . $user->get_property('userID') . '\'');
// Мои текущие заказы
    $total3 = $DB->getOne("SELECT count(`sp_order`.`id`)
			FROM `sp_order` 
			LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
			WHERE 
			((`sp_zakup`.`status`=3 and `sp_zakup`.`type`=0) or (`sp_zakup`.`status`=3 and `sp_zakup`.`type`=2) or (`sp_order`.`status` IN (0,1,2) and `sp_zakup`.`type`=1 and `sp_zakup`.`status`<9))
			and `sp_order`.`user`='{$user->get_property('userID')}'
			");
// Дозаказ
    $total5 = $DB->getOne('SELECT count(`sp_order`.`id`)
			FROM `sp_order` 
			LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
			WHERE `sp_zakup`.`status`=5
			AND `sp_order`.`user`=\'' . $user->get_property('userID') . '\'');
// Мои заказы в стопе
    $total4 = $DB->getOne('SELECT count(`sp_order`.`id`)
			FROM `sp_order` 
			LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
			WHERE `sp_zakup`.`status`=4
			AND `sp_order`.`user`=\'' . $user->get_property('userID') . '\'');
// Мои неоплаченные счета
    $total6 = $DB->getOne("SELECT count(`sp_order`.`id`)
			FROM `sp_order` 
			LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
			WHERE 
			((`sp_zakup`.`status`=6 and (`sp_zakup`.`type`=0 or `sp_zakup`.`type`=2) and ((select count(sp_addpay.status) from `sp_addpay` where `sp_addpay`.`zp_id`=`sp_zakup`.`id` 
			and sp_addpay.`user`='{$user->get_property('userID')}')=0)) or (`sp_order`.`status` IN (3) and `sp_zakup`.`type`=1 ))
			and `sp_order`.`user`='{$user->get_property('userID')}'
			");
// Оплата за доставку
    $total8 = $DB->getOne("SELECT count(`sp_order`.`id`)
			FROM `sp_order` 
			LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
			WHERE 
			((`sp_zakup`.`status`=8 and (`sp_zakup`.`type`=0 or `sp_zakup`.`type`=2)) or (`sp_order`.`status` IN (5) and `sp_zakup`.`type`=1 ))
			and `sp_order`.`user`='{$user->get_property('userID')}'
			");
// Архив заказов
    $total9 = $DB->getOne("SELECT count(`sp_order`.`id`)
			FROM `sp_order` 
			LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
			WHERE 
			((`sp_zakup`.`status`=9 and (`sp_zakup`.`type`=0 or `sp_zakup`.`type`=2)) or (`sp_order`.`status` IN (6) and `sp_zakup`.`type`=1 ))
			and `sp_order`.`user`='{$user->get_property('userID')}'
			");
    /*$total7=$DB->getOne("SELECT count(`sp_order`.`id`)
                FROM `sp_order`
                LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
                WHERE
                ((`sp_zakup`.`status`=7 and `sp_zakup`.`type`=0) or (`sp_order`.`status` IN (4) and `sp_zakup`.`type`=1 ))
                and `sp_order`.`user`='{$user->get_property('userID')}'
                ");
    */
// Мои оплаченные счета
    $sql = 'SELECT count(`sp_order`.`id`)
			FROM `sp_order` 
			LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
			WHERE `sp_order`.`user`=\'' . $user->get_property('userID') . '\' and ((`sp_zakup`.`status`=7)' .
        " or ((select count(sp_addpay.status) from `sp_addpay` where `sp_addpay`.`zp_id`=`sp_zakup`.`id` 
			and sp_addpay.`user`='{$user->get_property('userID')}')>0) 
			and (`sp_zakup`.`status`=5 or `sp_zakup`.`status`=6 or `sp_zakup`.`status`=7))";
//echo $sql;exit;
    $total7 = $DB->getOne($sql);
//Раздача
    $total10 = $DB->getOne("SELECT count(`sp_order`.`id`)
			FROM `sp_order` 
			LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
			WHERE 
			((`sp_zakup`.`status`=10 and (`sp_zakup`.`type`=0 or `sp_zakup`.`type`=2)) or (`sp_order`.`status` IN (6) and `sp_zakup`.`type`=1 ))
			and `sp_order`.`user`='{$user->get_property('userID')}'");
    ?>

    <!--<? //$comp = sp_addpay.`user`='{$user->get_property('userID')}')>0)
    // попытка определить, оплаченный заказ или нет и объявить переменную, чтобы сделать моргалку в оплате за транспорт ---
    ?>     -->

    <div class="menu-top-green">Моя корзина</div>
    <div class="menu-body">
        <div class="basket_menu" class="basket-sel-1">
            <!--<b>Корзина</b><br/>-->
            <div>
                <a href="/com/basket/?status=3" class="basket-sel-1">Текущие заказы
                    <span class="basket-sel-num" style="padding-left: 40px;"> <?= intval($total3) ?> </span></a>
                <a href="/com/basket/?status=4" class="basket-sel-1">Заказы в стопе
                    <span class="basket-sel-num" style="padding-left: 47px;"> <?= intval($total4) ?> </span></a>
                <a href="/com/basket/?status=5" class="basket-sel-1">Дозаказы
                    <span class="basket-sel-num" style="padding-left: 81px;"> <?= intval($total5) ?> </span></a>
                <a href="/com/basket/?status=6" class="basket-sel-1">Неоплаченные счета
                    <span class="basket-sel-num" style="padding-left: 8px;"> <?= intval($total6) ?> </span></a>
                <a href="/com/basket/?status=7" class="basket-sel-1">Оплаченные счета
                    <span class="basket-sel-num" style="padding-left: 22px;"> <?= intval($total7) ?> </span></a>
                <a href="/com/basket/?status=8" class="basket-sel-1">Опл за доставку
                    <span class="basket-sel-num" style="padding-left: 38px;"> <?= intval($total8) ?> </span></a>
                <a href="/com/basket/?status=10" class="basket-sel-1">Раздача
                    <span class="basket-sel-num" style="padding-left: 91px;"> <?= intval($total10) ?> </span></a>
                <a href="/com/basket/?status=9" class="basket-sel-1">Архив
                    заказов <span class="basket-sel-num" style="padding-left: 52px;"> <?= intval($total9) ?> </span></a>
            </div>
        </div>
        <? if ($user->get_property('gid') >= 23):?>
            <div>
                <div>
                    <a href="/com/org/" class="basket-sel-org">Кабинет организатора</a>
                </div>
        <? endif;
        if (($user->get_property('group_id') == 5) OR ($user->get_property('group_id') == 1)):?>
                <div>
                    <a href="/com/store/orgStore" class="basket-sel-org">Заказы на складе</a>
                </div>
        <?endif;?>
        <?if (($user->get_property('group_id') == 2) OR ($user->get_property('group_id') == 1)):?>
                <div>
                    <a href="/com/store/" class="basket-sel-org">Кабинет Хранителя</a>
                </div>
        <?endif;?>
            </div>

    </div>

<? else: /*  style="font: bold 14px arial underline"

$total_zak=$DB->getOne('SELECT count(`sp_zakup`.`id`)
			FROM `sp_zakup` 
			WHERE `sp_zakup`.`status`>2 and `sp_zakup`.`status`<9');


$sql="	SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`user`,`sp_zakup`.`text`,`sp_zakup`.`min`,`sp_zakup`.`proc`,
		`sp_zakup`.`foto`,`punbb_users`.`username`,`cities`.`city_name_ru`,
		(select count(`sp_order`.`id`) from `sp_order` where `sp_order`.`id_zp`=`sp_zakup`.`id`) AS `res`
	FROM `sp_zakup`
	LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
	JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
	WHERE `sp_zakup`.`status`=3 or `sp_zakup`.`status`=5 
	ORDER BY RAND()
	LIMIT 1";
$popzp = getAllcache($sql,300);

  if(!empty($popzp[0]['foto']))
	{
	$split=explode('/',$popzp[0]['foto']);
	$img_path='/images/'.$split[2].'/75/50/1/'.$split[3];
	} else $img_path='/'.$theme.'images/no_photo125x100.png';
?>

  <div class="sidebar-green-top">
    <div class="sidebar-green-top-l"></div>
    <div class="sidebar-green-top-r"></div>
    <div class="sidebar-beer-m11"></div>
	<a href="/com/zakup/">Совместные покупки</a>
  </div>
  <div class="sidebar-body">

                <?if(count($popzp)>0):?>
		<table><tr>
		  <td valign="top" align="center" width="80"><a href="/com/org/open/<?=$popzp[0]['id']?>/">
			<img src="<?=$img_path?>" width="75" height="50" border="0" alt="<?=$popzp[0]['title']?>" title="<?=$popzp[0]['title']?>" class="photo"/></a>
		  </td>
		  <td valign="top" align="left" class="popular">
			<span class="text">
			<a href="/com/org/open/<?=$popzp[0]['id']?>/" class="link-sp" title="<?=utf8_substr(str_replace('"','\'',strip_tags($popzp[0]['text'])),0,150)?>..."><?=$popzp[0]['title']?></a><br/><br/>

			<span class="status">Открыта, <b><?=$popzp[0]['res']?> заказ(ов)</b></span><br/>
			<b>Оргсбор</b>: <?=$popzp[0]['proc']?>%<br/>
			<b>Минималка</b>: <?=$popzp[0]['min']?> руб.<br/>
			<!--г. <?=$popzp[0]['city_name_ru']?>-->
		  </td></tr>
		</table>
		Организатор: <a href="/com/profile/default/<?=$popzp[0]['user']?>/" class="link2"><?=$popzp[0]['username']?></a>
		<?else:?>
		       <p>Закупки готовятся к открытию</p>
		<?endif?>
  </div>

  <div class="sidebar-green-bot">
    <div class="sidebar-green-bot-l"></div>
    <div class="sidebar-green-bot-r"></div>
	<a href="/com/zakup/">Всего закупок (<?=intval($total_zak)?>)</a>
  </div>
<?*/endif ?>