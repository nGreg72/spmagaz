<? defined('_JEXEC') or die('Restricted access'); ?>
<? if ($user->get_property('id') == 1 OR $user->get_property('gid') == 25):
    $userid = intval($_GET['edit']);
    $all = $DB->getAll('SELECT * FROM punbb_users WHERE punbb_users.id=' . $userid);

//print_r($_GET);exit;


    $wh = "and `sp_zakup`.`status`>2 and `sp_zakup`.`status`<=10";      // todo ежели закомментить эту строку, то становятся видны закупки в статусе "рОздача"
    if ($_GET['status'] == 1) $wh = "and `sp_zakup`.`status`=3";
    if ($_GET['status'] == 2) $wh = "and `sp_zakup`.`status`=5";
    if ($_GET['status'] == 3) $wh = "and `sp_zakup`.`status`=4";
    if ($_GET['status'] == 4) $wh = "and `sp_zakup`.`status`=6";
    if ($_GET['status'] == 5) $wh = "and `sp_zakup`.`status`=8";
    if ($_GET['status'] == 6) $wh = "and `sp_zakup`.`status`=9";
    if ($_GET['status'] == 7) $wh = "and `sp_zakup`.`status`=7";

    $id = intval($_GET['value']);

    if ($_GET['section'] == 'addpay') $wh = "and `sp_zakup`.`id`='$id'";

    $sql = "	SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`user`,`sp_zakup`.`text`,`sp_zakup`.`min`,`sp_zakup`.`proc`
		,`sp_zakup`.`rekviz`,`sp_zakup`.`status`,`sp_zakup`.`dost`,`sp_zakup`.`proc`,`sp_zakup`.`curs`,
		`sp_zakup`.`foto`,`punbb_users`.`username`,`punbb_users`.`last_visit`,`cities`.`city_name_ru`, `sp_status`.`name`,
		(select count(`sp_order`.`id`) from `sp_order` where `sp_order`.`id_zp`=`sp_zakup`.`id`) AS `res`
	FROM `sp_zakup`
	LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
	JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
	JOIN `sp_order` ON `sp_zakup`.`id`=`sp_order`.`id_zp`
	JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
	WHERE `sp_order`.`user`='{$userid}'
		$wh	
	GROUP BY `sp_zakup`.`id`
	ORDER BY `sp_zakup`.`id` DESC
	";
    $zakup = $DB->getAll($sql);
    //print_r($zakup);exit;

    foreach ($zakup as $zp):
        $sql = "  select `sp_order`.*, `sp_ryad`.`title`,`sp_ryad`.`articul`,
	`sp_ryad`.`message`,`sp_ryad`.`price`,`sp_size`.`name` as `sizename`,
	`sp_size`.`anonim`
	from `sp_order` 
	JOIN `sp_size` ON `sp_order`.`id_order`=`sp_size`.`id`
	JOIN `sp_ryad` ON `sp_ryad`.`id`=`sp_size`.`id_ryad`
	where `sp_order`.`id_zp`='" . $zp['id'] . "' and `sp_order`.`user`='$userid'";
        $order[$zp['id']] = $DB->getAll($sql);
        $totalprice = 0;
        foreach ($order[$zp['id']] as $ord):
            if ($ord['kolvo'] == 0) $ord['kolvo'] = 1;
            if ($ord['status'] == 2 || $ord['status'] == 7) continue;
            $totalprice = $totalprice + ($ord['price'] * $ord['kolvo']);
        endforeach;
        $totalzp[$zp['id']] = $totalprice;

        $sql = "select *
	from `sp_addpay`
	where `zp_id`='" . $zp['id'] . "' and `user`='$userid'";
        $addpay[$zp['id']] = $DB->getAll($sql);

    endforeach;


    if (count($all) > 0):
        foreach ($all as $num):?>
            <h2>Редактирование профиля, <?= $num['username'] ?>: </h2>
            </?print_r($num); exit;?>
            <? //print_r($num['last_visit']);
            ?>
            <div>Зарегался: <?= date('d.m.Y H:i', $all[0]['registered']) ?></div><br/>
            <div>Шарился: <?= date('d.m.Y H:i', $all[0]['last_visit']) ?></div>

            <div id='tabname_1' onclick="showtab('1')" class='active'><u>Основные</u></div>
            <div id='tabname_2' onclick="showtab('2')" class='nonactive'><u>Дополнительно</u></div>

            <div id='tabcontent_1' class='show'>
                <form method="post" action="?component=users&page=<?= $_GET['page'] ?>" enctype="multipart/form-data"/>
                <table class="user-table">
                    <tr>
                        <td width="250" align="center">
                            <? if (generate_avatar_markup($userid, true) == ''):
                                $img_path = '<img src="/' . $theme . 'images/no_photo125x100.png" alt="" class="photo" width="' . $avator_width_profile . '" height="' . $avator_height_profile . '"/>';
                            else:
                                $img_path = generate_avatar_markup($userid, true);
                            endif; ?>
                            <?= $img_path ?><br/>
                            <input type="checkbox" name="del_ava" value="1"/> удалить фото<br/>
                            фото: <input type="file" name="photo"/></td>
                        <td>
                            <input type="hidden" name="id" value="<?= $num['id'] ?>"/>
                            <input type="hidden" name="event" value="users"/>
                            <input type="hidden" name="update" value="1"/>

                            <table>
                                <tr>
                                    <td>ФИО:</td>
                                    <td><input class="inputbox" type="text" name="fam" value="<?= $num['realname'] ?>"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Группа:</td>
                                    <td><select name="group" class="inputbox">
                                            <? $all_cat = $DB->getAll('SELECT * FROM `group` ORDER BY id ASC');
                                            foreach ($all_cat as $alca) {
                                                if ($alca['fgid'] == $num['group_id']) $sel = 'selected'; else $sel = '';
                                                echo '<option value="' . $alca['fgid'] . '" ' . $sel . '>' . $alca['name'] . '</option>';
                                            } ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><input class="inputbox" type="text" name="email" value="<?= $num['email'] ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td><input class="inputbox" type="text" name="phone" value="<?= $num['phone'] ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Коротко о себе</td>
                                    <td><input class="inputbox" type="text" name="desc" value="<?= $num['desc'] ?>"/>
                                    </td>
                                </tr>


                                <tr>
                                    <td>Страна</td>
                                    <td>
                                        <select name="country" id="country" class="inputbox">
                                            <option value="">---</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Регион</td>
                                    <td>
                                        <select name="region" id="region" class="region inputbox">
                                            <option value="">---</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Городок</td>
                                    <td>
                                        <select name="city" id="city" class="city inputbox">
                                            <option value="">---</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Новый пароль</td>
                                    <td><input class="inputbox" type="text" name="pass" value=""/></td>
                                </tr>

                                <tr>
                                    <td>Баланс</td>
                                    <td><input class="inputbox" type="text" name="wm" value="<?= $num['wm'] ?>"/></td>
                                </tr>

                                <tr>
                                    <td>Пользователь WhatsApp?</td>
                                    <td><select name="waUser" >
                                            <option value="0" <?if($num['wm']==0):?>selected<?endif?>>Нет</option>
                                            <option value="1" <?if($num['wm']==1):?>selected<?endif?>>Да</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <!--<td>Номер кошелька:</td>
                                    <td><?/*=740000 + $num['id'] */?></td>-->
                                    <td>Чел согласился на рассылку? -</td>
                                    <td><select name="alertmail">
                                        <option value="0" <?if ($num['alertmail'] == 0):?>selected<?endif;?> >Да</option>
                                        <option value="1" <?if ($num['alertmail'] == 1):?>selected<?endif;?> >Нет</option>
                                    </select>
                                    </td>
                                </tr>


                            </table>


                        </td>
                    </tr>

                </table>


            </div>
            <div id='tabcontent_2' class='hide'>
                <table class="user-table" width="680" style="padding:10px">
                    <? $i = 0;
                    foreach ($name as $n):?>
                        <tr>
                            <td valign="top"><?= $n ?>:</td>
                            <td valign="top"><?= $form[$i] ?></td>
                        </tr>
                        <? $i++;endforeach; ?>
                </table>
            </div>
            <div style="display: flex; align-items:baseline;">
                <div>
                    <p>
                        <input type="button" class="cancel-button" onclick="history.back();" value="Назад"/>
                    </p>
                </div>
                <div style="padding-left:50px;">
                    <? if ($num['id'] != 2):?><a
                        href="?component=users&delete=<?= $num['id'] ?>&page=<?= $num['page'] ?>"
                        onclick="if (!confirm('Натурально хочите удалить юзера       <?= $num['username'] ?>')) return false;">
                            <input class="ok-button" style="width:50px;" value="Удалить" alt="del" title="удалить"/></a>
                    <? endif ?></div>
                <div style="padding-left:348px;"><input type="submit" class="ok-button" value="Сохранить"/></div>
            </div>
            </form>


            <script>
                function goBack() {
                    window.history.back()
                }
            </script>


        <? endforeach; ?>


        <h1>Корзина пользователя</h1>
        <? if (count($zakup)):?>
        <? $i = 0;
        $total_all_zp = 0;
        $corect_total = 0;
        foreach ($zakup as $zp):?>
            <? $i++;
            if ($zp['curs'] == 0) $zp['curs'] = 1;
            if (!empty($zp['foto'])) {
                $split = explode('/', $zp['foto']);
                $img_path = '/images/' . $split[2] . '/125/100/1/' . $split[3];
            } else $img_path = '/' . $theme . 'images/no_photo125x100.png';
            ?>
            <div class="menu-body5 <? if ($i > 1):?>next<? endif; ?> <? if (count($zakup) == 1):?>fixbasket<? endif ?>">
                <div class="block1">
                    <table>
                        <tr>
                            <td width="150" valign="top" align="center">
                                <a href="/com/org/open/<?= $zp['id']; ?>"><img src="<?= $img_path ?>" width="125"
                                                                               height="100" alt="" border="0"
                                                                               align="left" class="photo"/></a>
                            </td>
                            <td valign="top" width="330">
                                <div class="text_body_full">
                                    <a href="/com/org/open/<?= $zp['id']; ?>"
                                       class="newstitle"><b><?= $zp['title']; ?></b></a><br/>
                                    <b>Организатор</b>: <a href="/com/profile/default/<?= $zp['user'] ?>"
                                                           class="link4"><?= $zp['username'] ?></a><br>
                                    <b>Город</b>: <?= $zp['city_name_ru'] ?><br/>
                                    <b>Статус</b>: <span class="price"><?= $zp['name']; ?></span>, <?= $zp['res']; ?>
                                    заказов<br/>
                                    <b>Минималка</b>: <?= $zp['min'] ?> <?= $registry['valut_name'] ?><br/>
                                    <? if (floatval($zp['curs']) == 0) $openzakup[0]['curs'] = 1; ?>
                                    <b>Курс</b>: 1 у.е. = <?= $zp['curs'] ?> <?= $registry['valut_name'] ?><br/><br/>


                                    <? if ($_GET['status'] > 3):
                                        if (count($addpay[$zp['id']]) == 0):?>
                                            <a class="a-button" href="/com/basket/addpay/<?= $zp['id'] ?>/">Уведомить об
                                                оплате</a>
                                        <? elseif ($addpay[$zp['id']][0]['status'] == 0):?>
                                            <span class="a-button">Статус оплаты: ждет подтверждения орга</span>
                                        <? elseif ($addpay[$zp['id']][0]['status'] == 1):?>
                                            <span class="green">Оплата подтверждена</span>
                                        <? elseif ($addpay[$zp['id']][0]['status'] == 2):?>
                                            <span class="red">Оплата не подтверждена</span>
                                        <? endif ?>
                                    <? endif ?>


                                </div>
                            </td>
                            <td width="245">
                                <div class="info-top"></div>
                                <div class="info-mid">
                                    <b>Сумма</b>: <?= $totalzp[$zp['id']] * $zp['curs']; ?> <?= $registry['valut_name'] ?> <? if ($zp['curs'] <> 1):?>(<?= $totalzp[$zp['id']] ?> у.е.)<? endif; ?>
                                    <br/>
                                    <b>Оргсбор</b>: <?= $zp['proc'] ?>%<br/>
                                    <? if ($zp['dost'] > 0 and $zp['status'] > 3):
                                        $query = "SELECT `sp_ryad`.`price`,`sp_order`.`kolvo`,`sp_order`.`status`
		    FROM `sp_order` 
		    LEFT JOIN `sp_ryad` ON `sp_order`.`id_ryad`=`sp_ryad`.`id`
		    WHERE `sp_order`.`id_zp` = '{$zp['id']}' and `sp_order`.`status` != '2' and `sp_order`.`status` != '7'";
                                        $tp = $DB->getAll($query);
                                        $totalprice = 0;
                                        foreach ($tp as $t) {
                                            //if($t['status']==2)continue;
                                            $totalprice = $totalprice + ($t['kolvo'] * $t['price']);
                                        }
                                        $userdost = round(($zp['dost'] / 100) * (($totalzp[$zp['id']] * $zp['curs']) / ($totalprice / 100)), 1); ?>

                                        <b title="от поставщика до организатора, делится на всех участников">
                                            Доставка</b>: <?= $userdost ?> <?= $registry['valut_name'] ?><br/>
                                    <? else: $userdost = 0; endif; ?>
                                    <span title="Итого к оплате">Итого</span>: <span
                                            style="font:bold 16px arial; color:red;"><?= ceil(($totalzp[$zp['id']] * $zp['curs']) + round(($totalzp[$zp['id']] * $zp['curs']) / 100 * $zp['proc'], 2) + $userdost); ?> <?= $registry['valut_name'] ?>
                                        <? if ($addpay[$zp['id']][0]['status'] == 1):?><span
                                                class="green">(оплачено)</span><? endif ?>
			</span><br/>
                                    <? if ($zp['status'] == 6):?><b style="color:red">Реквизиты для оплаты</b>:
                                        <br/> <? if ($zp['rekviz'] > ''):?><?= $zp['rekviz'] ?><? else:?>пока не указаны<? endif ?>
                                        <br/><? endif ?>
                                </div>
                                <div class="info-bot"></div>
                            </td>
                        </tr>
                    </table>
                </div>
                <table class="shop-items" summary="">
                    <tbody>
                    <tr>
                        <td width="350" class="title">Название - Артикул</td>
                        <td width="60" class="title" align="center">Цена</td>
                        <td width="60" class="title" align="center">Кол-во</td>
                        <td width="60" class="title" align="center">Размер</td>
                        <td width="60" class="title" align="center">Цвет</td>
                        <td width="80" class="title" align="center">Статус</td>
                    </tr>
                    <? foreach ($order[$zp['id']] as $ord):?>
                        <tr class="<? if ($ord['status'] == 0):?>is_new<? elseif ($ord['status'] == 1):?>is_yes<? elseif ($ord['status'] == 2):?>is_no<? endif ?>">
                            <td>
                                <table>
                                    <tr>
                                        <td width="35">
                                            <? if ($zp['status'] <= 3):?>
                                                <a href="/com/basket/editorder/<?= $ord['id'] ?>"><img
                                                            src="/<?= $theme ?>images/edit2.png" width="16" height="16"
                                                            border="0" alt="Редактировать заказ"
                                                            title="Редактировать заказ"/></a>
                                                <a href="/com/basket/delorder/<?= $ord['id'] ?>"
                                                   onclick="if (!confirm('Вы подтверждаете удаление заказа?')) return false;"><img
                                                            src="/<?= $theme ?>images/del2.jpg" width="16" height="16"
                                                            border="0" alt="Удалить заказ"
                                                            title="Удалить заказ из корзины"/></a><br/>
                                            <? else:?>


                                                <a href="/com/pristroy/add/?frombasket=<?= $ord['id'] ?>"><img
                                                            src="/<?= $theme ?>images/auction.png" width="16"
                                                            height="16" border="0" alt="Отправить в пристрой"
                                                            title="Отправить заказ в пристрой"/></a>
                                                <img src="/<?= $theme ?>images/info.png" width="16" height="16"
                                                     border="0" alt="Вы не можете удалять или редактировать заказ"
                                                     title="Вы не можете удалять или редактировать заказ"/>
                                            <? endif; ?>
                                        </td>
                                        <td>
                                            <?= $ord['title'] ?>
                                            <? if (!empty($ord['articul'])):?> - <?= $ord['articul'] ?><? endif ?>
                                            <? if ($ord['anonim'] == 1):?> <span
                                                    class="anonim">(анонимно)</span><? endif ?>
                                    </tr>
                                </table>
                            </td>
                            <td align="center"><?= $ord['price'] * $zp['curs']; ?> <?= $registry['valut_name'] ?></td>
                            <? //if($ord['kolvo']>1) $corect_total=$corect_total+(((($ord['price']*$ord['kolvo'])-$ord['price'])*$zp['curs'])+round((($ord['price']*$ord['kolvo'])-$ord['price'])*$zp['curs'])/100*$zp['proc']);
                            ?>
                            <td align="center" class="kolvo">x <?= $ord['kolvo'] ?></td>
                            <td align="center"><?= $ord['sizename'] ?></td>
                            <td align="center"><?= $ord['color'] ?></td>
                            <td align="center">
                                <? if ($ord['status'] == 0):?>Новый
                                <? elseif ($ord['status'] == 1):?>Включен в счет
                                <? elseif ($ord['status'] == 2):?>Отказано
                                <? elseif ($ord['status'] == 3):?>Не оплачен
                                <? elseif ($ord['status'] == 4):?>Оплачен
                                <? elseif ($ord['status'] == 5):?>Раздача
                                <? elseif ($ord['status'] == 6):?>Архив
                                <? elseif ($ord['status'] == 7):?>Нет в наличии
                                <? endif ?>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    </tbody>
                </table>
                <? $total_all_zp = $total_all_zp + $userdost + ($totalzp[$zp['id']] * $zp['curs']) + round(($totalzp[$zp['id']] * $zp['curs']) / 100 * $zp['proc'], 2);
                $total_all_zp = ceil($total_all_zp + $corect_total);
                if (count($zakup) == $i):?>
                    <div class="total-all-zp" title="Общая сумма стоимости заказов во всех закупках">
                        Общая сумма: <?= $total_all_zp; ?> <?= $registry['valut_name'] ?>
                    </div>
                    <div style="font: 18px Arial;">
                        Всего заказов: <?=count($zakup)?>
                    </div>
                <? endif; ?>
            </div>
        <? endforeach; ?>
    <? else:?>
        Корзина пуста
        <br/><br/>
    <? endif ?>


    <? else:?>Для редактирования записи перейдите в раздел "опубликовать новость", затем кликните "Редактировать".<? endif; ?>
<? else: ?>У вас нет прав для доступа в этот раздел. Авторизируйтесь пожалуйста.<? endif ?>


<script type="text/javascript">
    $(document).ready(function () {

        //post autoloat

        $.post("/cities.php", {},
            function (xml) {
                $(xml).find('country').each(function () {
                    id = $(this).find('id_country').text();
                    if (id == parseInt('<?=$all[0]['country']?>')) sel = 'selected'; else sel = '';
                    $("#country").append("<option value='" + id + "' " + sel + ">" + $(this).find('country_name_ru').text() + "</option>");
                });
            });

        var id_country = $("#country option:selected").val();

        if (id_country == "") id_country = parseInt('<?=$all[0]['country']?>');
        if (id_country == "") {
            // $(".region, .city, #submit").hide();
        } else {
            $("#region").html('');
            $("#region").html('<option value="">Выберите регион</option>');
            $.post("/cities.php", {id_country: id_country},
                function (xml) {
                    $(xml).find('region').each(function () {
                        id = $(this).find('id_region').text();
                        if (parseInt(id) == parseInt('<?=$all[0]['region']?>')) {
                            sel = 'selected';

                            id_region = id;
                            $("#city").html('');
                            $("#city").html('<option value="">Выберите город</option>');
                            $.post("/cities.php", {id_region: id_region},
                                function (xml) {
                                    $(xml).find('city').each(function () {
                                        id = $(this).find('id_city').text();
                                        if (parseInt(id) == parseInt('<?=$all[0]['city']?>')) sel2 = 'selected'; else sel2 = '';
                                        $("#city").append("<option value='" + id + "' " + sel2 + ">" + $(this).find('city_name_ru').text() + "</option>");
                                    });
                                });


                        } else sel = '';


                        $("#region").append("<option value='" + id + "' " + sel + ">" + $(this).find('region_name_ru').text() + "</option>");
                    });
                });
            $(".region").show();
        }

        $("#country").change(function () {
            id_country = $("#country option:selected").val();
            $("#region").html('');
            $("#region").html('<option value="">Выберите регион</option>');
            $.post("/cities.php", {id_country: id_country},
                function (xml) {
                    $(xml).find('region').each(function () {
                        id = $(this).find('id_region').text();
                        if (parseInt(id) == parseInt('<?=$all[0]['region']?>')) {
                            sel = 'selected';

                            id_region = id;
                            $("#city").html('');
                            $("#city").html('<option value="">Выберите город</option>');
                            $.post("/cities.php", {id_region: id_region},
                                function (xml) {
                                    $(xml).find('city').each(function () {
                                        id = $(this).find('id_city').text();
                                        if (parseInt(id) == parseInt('<?=$all[0]['city']?>')) sel2 = 'selected'; else sel2 = '';
                                        $("#city").append("<option value='" + id + "' " + sel2 + ">" + $(this).find('city_name_ru').text() + "</option>");
                                    });
                                });


                        } else sel = '';


                        $("#region").append("<option value='" + id + "' " + sel + ">" + $(this).find('region_name_ru').text() + "</option>");
                    });
                });
        });

        $("#region").change(function () {
            id_region = $("#region option:selected").val();
            if (id_region == "") {
                $(".city").hide();
            } else {
                $("#city").html('');
                $("#city").html('<option value="">Выберите город</option>');
                $.post("/cities.php", {id_region: id_region},
                    function (xml) {
                        $(xml).find('city').each(function () {
                            id = $(this).find('id_city').text();
                            $("#city").append("<option value='" + id + "'>" + $(this).find('city_name_ru').text() + "</option>");
                        });
                    });
            }
            $(".city").show();
        });
        $("#city").change(function () {
            if ($("#city option:selected").val() == "") {
                $("#submitc").hide();
            } else {
                $("#submitc").show();
            }
        });

    });
</script>