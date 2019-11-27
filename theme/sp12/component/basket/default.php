<? defined('_JEXEC') or die('Restricted access'); ?>

<script src="/<?=$theme?>js/sweetalert2/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" type="text/css" href="/<?=$theme?>js/sweetalert2/dist/sweetalert2.css">
<script type="text/javascript">
    function confirm(ln) {
        var link = ln.href; // Получаем значение тега href
        swal({
                title: 'Удалить заказ ?', // Заголовок окна
                type: "question", // Тип окна
                showCancelButton: true, // Показывать кнопку отмены
            }).then(function () {
                window.location.href = link;
            })

        return false;
    }
</script>

<? if ($user->get_property('userID') > 0): ?>
    <style>
        .newstitle {
            float: left;
            width: 500px
        }

        .newstitle:hover {
            text-decoration: none;
        }

        .shop-items {
            margin: 10px 0 0 0;
        }

        .shop-items td {
            padding: 5px 10px;
            color: #4C4C4C;
        }

        .shop-items .title {
            background-color: #F2F3F3;
        }

        .anonim {
            color: #076AAD;
            font: 11px arial;
        }

        .next {
            background-image: none;
            padding: 10px 10px 50px 10px;
        }

        .fixbasket {
            padding: 10px 10px 50px 10px;
        }

        .shop-items .kolvo {
            color: #076AAD
        }

        .menu-top5 form {
            margin: 0;
            padding: 0;
            float: right;
            width: 300px;
            text-align: right;
        }

        .shower {
            width: 24px;
            height: 24px;
            display: block;
            float: left;
            margin: 0 10px 0 0
        }

        .show {
            background: url(/theme/sp12/images/plus.png) 0 0 no-repeat;
        }

        .hide {
            background: url(/theme/sp12/images/minus.png) 0 0 no-repeat;
        }

        .minprice {
            font: 16px arial;
            float: left;
            margin: 0 30px 0 0;
            color: #873535
        }

        .clearfix {
            clear: both
        }
    </style>
    <script type="text/javascript">
        $().ready(function () {
            $(".shower").click(function () {
                var id = $(this).attr('rel');
                var st = parseInt($.cookie('zp' + id));
//alert(st);
                if (st == 0 || st != 1) $.cookie('zp' + id, 1); else $.cookie('zp' + id, 0);
                $(this).toggleClass('show');
                $(this).toggleClass('hide');
                $("#zp" + id).toggle(100);
                if (st == 0 || st != 1) $("#minprice" + id).html('= ' + $("#price" + id).html()); else $("#minprice" + id).html('');


            });
        });

        function get_price(id) {
            $("#minprice" + id).html('= ' + $("#price" + id).html());
        }
    </script>


    <? if ($_GET['status'] == 3): ?>
        <div class="menu-top5">Корзина: Текущие заказы</div>
    <? elseif ($_GET['status'] == 4): ?>
        <div class="menu-top5">Корзина: Заказы в стопе</div>
    <? elseif ($_GET['status'] == 5): ?>
        <div class="menu-top5">Корзина: Дозаказы</div>
    <? elseif ($_GET['status'] == 6): ?>
        <div class="menu-top5">Корзина: Неоплаченные счета</div>
    <? elseif ($_GET['status'] == 7): ?>
        <div class="menu-top5">Корзина: Оплаченные счета</div>
    <? elseif ($_GET['status'] == 8): ?>
        <div class="menu-top5">Корзина: Оплата за доставку</div>
    <? elseif ($_GET['status'] == 9): ?>
        <div class="menu-top5">Корзина: Архив заказов</div>
    <? elseif ($_GET['status'] == 10): ?>
        <div class="menu-top5">Корзина: Раздача</div>
    <? endif ?>

    <? if (count($zakup)): ?>
        <? $i = 0;
        $total_all_zp = 0;
        $corect_total = 0;
        foreach ($zakup as $zp): ?>
            <?
            if ($order[$zp['id']] == null) continue;
            $i++;
            if ($zp['curs'] == 0) $zp['curs'] = 1;
            if (!empty($zp['foto'])) {
                $split = explode('/', $zp['foto']);
                $img_path = '/images/' . $split[2] . '/125/100/1/' . $split[3];
            } else $img_path = '/' . $theme . 'images/no_photo125x100.png';
            ?>
            <div class="menu-body5 <? if ($i > 1): ?>next<? endif; ?> <? if (count($zakup) == 1): ?>fixbasket<? endif ?>">
                <a href="javascript://"
                   class="shower <? if (intval($_COOKIE['zp' . $zp['id']]) == 1): ?>show<? else: ?>hide<? endif ?>"
                   title="скрыть\показать" rel="<?= $zp['id']; ?>"></a>
                <a href="/com/org/open/<?= $zp['id']; ?>" class="newstitle"><?= $zp['title']; ?></a>
                <span class="minprice" id="minprice<?= $zp['id']; ?>"></span>
                <div class="clearfix"></div>
                <div id="zp<?= $zp['id']; ?>"
                     <? if (intval($_COOKIE['zp' . $zp['id']]) == 1): ?>style="display:none"<? endif ?>>
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
                                        <b>Организатор</b>: <a href="/com/profile/default/<?= $zp['user'] ?>"
                                                               class="link4"><?= $zp['username'] ?></a><br>
                                        <b>Город</b>: <?= $zp['city_name_ru'] ?><br/>
                                        <b>Статус</b>: <span
                                                class="price"><?= $zp['name']; ?></span>, <?= $zp['res']; ?>заказов<br/>
                                        <b>Минималка</b>: <?= $zp['min'] ?> <?= $registry['valut_name'] ?><br/>
                                        <? if (floatval($zp['curs']) == 0) $openzakup[0]['curs'] = 1; ?>
                                        <b>Курс</b>: 1 у.е. = <?= $zp['curs'] ?> <?= $registry['valut_name'] ?>
                                        <br/><br/>

                                        <? /*if($_GET['status']>3||($_GET['status']=='4' and $zp['type']==1)):
if(count($addpay[$zp['id']])==0):?>
	<a class="a-button" href="/com/basket/addpay/<?=$zp['id']?>/">Уведомить об оплате</a>
<?elseif($addpay[$zp['id']][0]['status']==0):?>
	<span class="a-button">Статус оплаты: ждет подтверждения орга</span>
<?elseif($addpay[$zp['id']][0]['status']==1):?>
	<span class="green">Оплата подтверждена</span>
<?elseif($addpay[$zp['id']][0]['status']==2):?>
	<span class="red">Оплата не подтверждена</span>
<?endif?>
<?endif*/ ?>


                                        <? if ($zp['dost'] > 0 and $zp['status'] > 3) {
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
											$userdost = round(($zp['dost'] / 100) * (($totalzp[$zp['id']] * $zp['curs']) / ($totalprice / 100)), 1);
                                        } else $userdost = 0;
                                        ?>

                                        <? //print_r($_GET['status']); die();?>

                                        <!--Вытаскиваем данные о наценке из массива   -->
                                        <? $extraCharge = 0 ?>
                                        <? foreach ($order[$zp['id']] as $ord): ?>
                                            <? $extraCharge = $extraCharge + $ord['oversize']; ?>
                                        <? endforeach; ?>

                                        <? //if($zp['showpay']==1):
                                        if (count($addpay[$zp['id']]) == 0):?>
                                            <? if ($zp['paytype'] == 1 || $zp['paytype'] == 3 || $zp['paytype'] == 0 AND ($_GET['status']) == 5 || ($_GET['status']) == 6):?>
                                                <a class="a-button"
                                                   href="/com/basket/addpay/<?= $zp['id'] ?>/?status=<?= $_GET['status'] ?>">
                                                    Уведомить об оплате заказа</a><? endif ?>
                                            <!--  AND ($_GET['status'])>=4 ) --  Чтобы не былo кнопки об уведомлении заказа до начала оплаты!!!  -->
                                            <!--  Поменять статус на 6-ку после реконструкции конструкции   -->

                                            <!--<? if ($zp['paytype'] == 2 || $zp['paytype'] == 3 || $zp['paytype'] == 0): ?><a class="a-button" href="/com/basket/paywm/<?= $zp['id'] ?>/?status=<?= $_GET['status'] ?>">Оплатить с кошелька</a><? endif ?> -->
                                        <? else: ?>

                                        <? $vasego = 0;
                                        $i = 0;
                                        foreach ($addpay[$zp['id']] as $itap):
                                        $vasego += $itap['summ']; //всего оплачено, разными оплатами
                                        $i++;
                                        //echo count($addpay[$zp['id']]);
                                        ?>

                                        <!--<p><span title="ID номера уведомления">#<?= $itap['id'] ?></span>-->
                                        <br><br><br>Оплачено:
                                        <span style="font: bold 16px arial; color:#C30E21;"><?= abs($itap['summ'] + $itap['summExtra']) ?>
                                            р.</span>
                                        <br>
                                        <? if ($itap['status'] == 0):?>    Статус оплаты: <span
                                                style="font: bold 14px arial; color: grey;">Ждет подтверждения организатора.</span>
                                        <? elseif ($itap['status'] == 1 || $itap['status'] == 4 || $itap['status'] == 5):
                                        $good++; ?>

                                        <? if ($itap['summ'] >= 0): ?>
                                            <span class="green">Оплата заказа подтверждена.</span>
                                        <? endif ?>

                                        <!--todo вытаскиваем подтверждение для пользователя о подтверждении оплаты -->
                                        <? if ($itap['transp'] - $itap['transpUser'] == 0 AND $itap['transpStatus'] == 1): ?>
                                            <br><span class="blue">Оплата доставки подтверждена.</span>
                                        <? endif ?>

                                            <? if ($itap['summ'] < 0): ?><span class="red">Возвтрат средств по переплате. </span>
                                            <? endif ?>
                                            <? elseif ($itap['status'] == 2):?>
                                                <span class="red">Оплата не подтверждена.</span>
                                            <? endif ?>

                                            <!------------------------------------ ВОТ ТУТ -------------------------------------!>

<? if ($addpay[$zp['id']][0]['summ_transp'] != 0): ?>
<br><br><span class="red";> Транспортные расходы - <?= round($addpay[$zp['id']][0]['summ_transp'], 2) ?>руб.:</span><br>
<? endif ?>

<? $itog = round(($totalzp[$zp['id']] * $zp['curs']) + ($totalzp[$zp['id']] * $zp['curs']) / 100 *
                                                $zp['proc'] + $userdost + $itap['doplata'] + $extraCharge - $itap['summExtra'], 2); ?>
	
<? if ($itap['status'] > 4 || ($itog > $vasego and $i == count($addpay[$zp['id']]))): // + требуется доплата, тоже выводим эту кнопку
                                                ?>
   <? //if($itap['status']!=4 and ($itog>$vasego and $i==count($addpay[$zp['id']]))) $itap['doplata']=$itog-$vasego;
                                                ?>

<? if (round($itog) > round($itap['summ'])):?>
	<br/><b style="color:red">Требуется доплатить <?= round($itog - $itap['summ']) ?>руб.:</b><br/>
<? endif; ?>


<? endif ?>

 <? if (round($itog) < round($itap['summ'])):?>
	<br/><b style="color:red">Зафиксирована переплата <?= round($vasego - $itog); ?>руб., ожидается возврат средств</b><br/>
<? endif ?>

<!--todo Находим разницу между требуемой суммой за доставку и отправленной пользователем-->
                                            <? $diff = $addpay[$zp['id']][0]['transp'] - $addpay[$zp['id']][0]['transpUser']; ?>

                                            <!-- Кнопка об оплате появляется только в статусе 8 (оплата за доставку)-->
                                            <? if (($zp['status'] == 8 OR $zp['status'] == 10) AND $diff != 0):?>
                                                <a class="a-button"
                                                   href="/com/basket/addpayTransp/<?= $zp['id'] ?>/?status=<?= $_GET['status'] ?>">Уведомить об оплате за доставку </a>
                                            <? endif; ?>

                                            <!-- todo Создаём переменную с суммой о трансп расходах-->
                                            <? $tr = $itap['transp'] ?>
                                            </p>
                                            <? endforeach ?>

                                            <!--todo Кнопка уведомления о дополнительной оплате появляется, если есть доплата-->
                                            <? if (round($itog) > round($itap['summ'])):?>
                                                <a class="a-button"
                                                   href="/com/basket/addpayExtra/<?= $zp['id'] ?>/?status=<?= $_GET['status'] ?>">Уведомить об доплате</a>
                                            <? endif; ?>

                                            <? endif ?>

                                            <? //endif?>

                                            <? //if($_GET['status']==5):?>
                                            <!--	<a class="a-button" href="/com/basket/goarch/<?= $zp['id'] ?>/">Убрать в архив</a> -->
                                            <? //endif?>


                                    </div>
                                </td>
                                <td width="245">
                                    <div class="info-top"></div>
                                    <div class="info-mid">
                                        <b>Сумма</b>: <?= $totalzp[$zp['id']] * $zp['curs']; ?> <?= $registry['valut_name'] ?>
                                        <? if ($zp['curs'] <> 1): ?>(<?= $totalzp[$zp['id']] ?> у.е.)
                                        <? endif; ?>
                                        <br/>

                                        <? if ($zp['proc'] > 0): ?><b>Оргсбор</b>: <?= $zp['proc'] ?>%<br/>
                                        <? endif ?>

                                        <? if ($userdost): ?><b
                                                title="от поставщика до организатора, делится на всех участников">
                                                Доставка</b>: <?= $userdost ?> <?= $registry['valut_name'] ?><br/>
                                        <? endif; ?>


                                        <!--К общеей сумме добавляем доплату и наценку-->
                                        <span style="font: bold 14px Arial;"
                                              title="Итого к оплате">Итого к оплате</span>: <span class="price"
                                                                                                  id="price<?= $zp['id']; ?>">
	  <?= ceil(($totalzp[$zp['id']] * $zp['curs']) + round(($totalzp[$zp['id']] * $zp['curs']) / 100 * $zp['proc'], 2) + $userdost + $itap['doplata'] + $extraCharge); ?><?= $registry['valut_name'] ?>
                                            <? if ($addpay[$zp['id']][0]['status'] == 1): ?><span class="green">(оплачено)</span><? endif ?>
			</span><br/>
<!--                                        --><?// if (($zp['status'] == 6 and ($zp['type'] == 0 or $zp['type'] == 2)) || ($_GET['status'] == 6 and $zp['type'] == 1)): ?>
                                        <?if ($zp['status'] == 6 OR $zp['status'] == 5 OR $zp['status'] == 8): ?>
                                            <b style="color:red; font: 24px arial;"> Реквизиты для оплаты </b>:
                                            <br/>   <? if ($zp['rekviz'] > ''): ?><?= $zp['rekviz'] ?>
                                                        <? else: ?>пока не указаны
                                                    <? endif ?><br/>
                                        <? endif ?>

                                        <? if ($zp['status'] == 8): ?>
                                            <span style="font: bold 14px Arial;">Доставка: <?= $tr ?> р.</span>
                                            <img src="/<?= $theme ?>images/Truck.png"
                                                 style="width: 48px; margin: 0 0 -5px 15px;">   <!-- todo Вывод суммы транспорных расходов-->

                                        <? endif; ?>

                                    </div>
                                    <div class="info-bot"></div>
<!--Товар на складе-->
                               <?foreach ($onSite[$zp['id']] AS $itm):?>
                                    <?if (($zp['status'] == 8 OR $zp['status'] == 10) AND $itm['on_site']==1): ?>
                                    <div style="margin: 10px 0 10px 77px; font: bold 25px arial; color: #da0c0c;">Заказ на складе.</div>
                                    <?endif;?>
                                <?endforeach;?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <? if (intval($_COOKIE['zp' . $zp['id']]) == 1): ?>
                        <script>document.write(get_price('<?=$zp['id']?>'));</script><? endif ?>


                    <table class="shop-items" summary="">
                        <tbody>
                        <tr>
                            <td class="title" align="center">Ред<br>заказ</td>
                            <td width="50%" class="title" style="text-indent: 97px;">Название</td>
                            <td width="10%" class="title" align="center">Цена/Наценка</td>
                            <td width="10%" class="title" align="center">Кол-во</td>
                            <td class="title" align="center">В коробке</td>
                            <td width="2%" class="title" align="center">Цвет</td>
                            <td width="10%" class="title" align="center">Статус</td>
                            <td class="title" align="center">Удалить <br> заказ</td>
                        </tr>
                        <? foreach ($order[$zp['id']] as $ord): ?>
                                <? if ($ord['tempOff'] == 1) {$ord['status'] = 7;}?>
                            <tr class="
                                <? if ($ord['status'] == 0): ?>is_new<? elseif ($ord['status'] == 1 || $ord['status'] == 3 || $ord['status'] == 4 || $ord['status'] == 5): ?>is_yes
                                <? elseif ($ord['status'] == 7): ?>is_no
                                <? elseif ($ord['status'] == 2): ?>is_deny
                                <? elseif ($ord['status'] == 8): ?>is_accept
                                <? elseif ($ord['status'] == 9): ?>is_arrived
                                <? endif ?> ">

                                <!--Редактир-->
                                <td width="35">
                                    <? if (($zp['status'] <= 3 and ($zp['type'] == 0 or $zp['type'] == 2) and $ord['status'] != 7 /*and ($ord['colvoFreePos']>0 || $ord['autoblock']==0)*/ and $ord['allblock'] == 0) || (($ord['status'] == 0 || $ord['status'] == 2) and $zp['type'] == 1 and $ord['allblock'] == 0)): ?>
                                        <!--изо Ред -->     <a href="/com/basket/editorder/<?= $ord['id'] ?>"><img
                                                    src="/<?= $theme ?>images/Edit_order.png" width="32" height="32"
                                                    alt="Редактировать заказ" title="Редактировать заказ"/></a>
                                    <? else: ?>
                                        <? if (($zp['status'] == 9 and ($zp['type'] == 0 or $zp['type'] == 2)) || (($ord['status'] == 6) and $zp['type'] == 1)): ?>
                                            <!--изо Пристр -->  <a
                                                href="/com/pristroy/add/?frombasket=<?= $ord['id'] ?>"><img
                                                    src="/<?= $theme ?>images/auction.png" width="16" height="16"
                                                    border="0" alt="Отправить в пристрой"
                                                    title="Отправить заказ в пристрой"/></a><? endif; ?>
                                        <!--изо Закр -->    <img src="/<?= $theme ?>images/Closed.png" width="25"
                                                                 height="25" style="margin-left: 3px;"
                                                                 alt="После СТОПА вы не можете  редактировать заказ"
                                                                 title="После СТОПА вы не можете редактировать заказ"/>
                                    <? endif; ?>
                                </td>
                                <td>
                                    <table>
                                        <tr style="font:bold 14px arial;">
                                            <td>
                                                <small> <?= date('d.m.Y H:i', $ord['dateunix']) ?></small>
                                                <br/>
                                                <!--Название--> <?= $ord['title'] ?>
                                                <? if (!empty($ord['articul'])): ?> - <?= $ord['articul'] ?><? endif ?>
                                                <? if ($ord['anonim'] == 1): ?> <span
                                                        class="anonim">(анонимно)</span><? endif ?>
                                            </td>
                                        </tr>
                                        <tr style="word-break: break-all" ;>
                                            <td>
                                                <?= $ord['msg']; ?>
                                            </td>
                                        </tr>

                                    </table>
                                </td>


                                <!--Цена-->
                                <td align="center"><?= $ord['price'] * $zp['curs']; ?> <?= $registry['valut_name'] ?>
                                    <!-- Наценка--> <? if ($ord['oversize'] != 0): ?>
                                        <br><span style="color: red;"><?= $ord['oversize']; ?> р.</span>
                                    <? endif; ?>
                                </td>
                                <? //if($ord['kolvo']>1) $corect_total=$corect_total+(((($ord['price']*$ord['kolvo'])-$ord['price'])*$zp['curs'])+round((($ord['price']*$ord['kolvo'])-$ord['price'])*$zp['curs'])/100*$zp['proc']);?>
                                <!--Количество-->
                                <td align="center" class="kolvo">x <?= $ord['kolvo'] ?></td>
                                <!--Размер-->
                                <td align="center"><?= $ord['sizename'] ?></td>
                                <!--Цвет-->
                                <td align="center"><?= $ord['color'] ?></td>
                                <!--Статус-->
                                <td align="center">
<? if ($ord['tempOff'] == 1) {$ord['status'] = 7;}?>
                                    <? if ($ord['status'] == 0): ?>Новый
                                    <? elseif ($ord['status'] == 1): ?>Включен в счет
                                    <? elseif ($ord['status'] == 2): ?>Отказано
                                    <? elseif ($ord['status'] == 3): ?>Не оплачен
                                    <? elseif ($ord['status'] == 4): ?>Оплачен
                                    <? elseif ($ord['status'] == 5): ?>Раздача
                                    <? elseif ($ord['status'] == 6): ?>Архив
                                    <? elseif ($ord['status'] == 7): ?>Нет в наличии
                                    <? elseif ($ord['status'] == 8): ?>В обработке
                                    <? elseif ($ord['status'] == 9): ?>Прибыл
                                    <? endif ?>
                                </td>
                                <!--Удаление-->
                                <td>
                                    <? if (($zp['status'] <= 3 and ($zp['type'] == 0 or $zp['type'] == 2) and $ord['status'] != 7 /* and ($ord['colvoFreePos']>0 || $ord['autoblock']==0) */ and $ord['allblock'] == 0) || (($ord['status'] == 0 || $ord['status'] == 2) and $zp['type'] == 1 and $ord['allblock'] == 0)): ?>
                                        <br>
                                        <a href="/com/basket/delorder/<?= $ord['id'] ?>" onclick="return confirm(this)">
                                            <img src="/<?= $theme ?>images/Remove_order.png" class="del-order"
                                                 alt="Удалить заказ" title="Удалить заказ из корзины"/></a><br/>
                                    <? else: ?>
                                        <img src="/<?= $theme ?>images/Closed.png" width="27" height="27"
                                             style="margin-left: 11px;" alt="После СТОПА вы не можете удалять заказ"
                                             title="После СТОПА вы не можете удалять заказ"/>
                                    <? endif; ?>
                                </td>
                            </tr>
                        <? endforeach; ?>
                        </tbody>
                    </table>

                    <? $total_all_zp = $total_all_zp + $userdost + ($totalzp[$zp['id']] * $zp['curs']) + round(($totalzp[$zp['id']] * $zp['curs']) / 100 * $zp['proc'], 2);
                    $total_all_zp = ceil($total_all_zp + $corect_total);
                    ?>
                    <? if (count(@unserialize($zp['office'])) > 0): ?>
                        <select name="office_<?= $zp['id'] ?>" class="office inputbox" rel="<?= $zp['id'] ?>"
                                <? if ($zp['status'] >= 7): ?>disabled<? endif ?>>
                            <option value="<?= $item['id'] ?>">-- Выберите ЦР --</option>
                            <?
                            $setoffice = $DB->getOne("SELECT office FROM office_set WHERE  zp_id='{$zp['id']}' and user='{$user->get_property('userID')}' LIMIT 1");
                            foreach ($office as $item):?>
                                <? if (in_array($item['id'], @unserialize($zp['office'])) || in_array(9999, @unserialize($zp['office']))):?>
                                    <option
                                    value="<?= $item['id'] ?>" <? if ($setoffice == $item['id']): ?>selected<? endif ?>><?= $item['name'] ?></option><? endif ?>
                            <? endforeach; ?>
                        </select>
                    <? endif ?>
                </div>

                <? if (count($zakup) == $i): ?>
                    <div class="total-all-zp" title="Общая сумма стоимости заказов во всех закупках">
                        Общая сумма: <?= $total_all_zp; ?> <?= $registry['valut_name'] ?>
                    </div>
                <? endif; ?>
            </div>
            <br>
            <div class="basket_line" ;></div>

        <? endforeach; ?>


        <script type="text/javascript">
            $(document).ready(function () {
                $(".office").change(function () {
                    var rel = $(this).attr("rel");
                    var id = $("select[name='office_" + rel + "'] option:selected").val();
                    $.post("/index.php?component=basket&section=ajax", {
                            sect: "<?=$_GET['section']?>",
                            value: id,
                            rel: rel,
                            key: "<?=$registry['license']?>",
                            event: "changeoffice"
                        },
                        function (html) {
                        });
                });
            });
        </script>


        <? if ($i == 0): ?>
            <div class="menu-body5">
                <h1>В данном разделе корзины заказы отсутствуют</h1>

                <a href="/" class="link4">Перейти на страницу закупок</a>

                <h1>Информация</h1>
                <ul>
                    <li>В корзине будут отображаться все сделанные вами заказы, с возможностью удаления и редактирования
                        их.
                    </li>
                    <li>Кроме всего, в корзине вы всегда можете посмотреть стоимость заказа, стоимость заказа с учетом
                        оргсбора и доставки, количество заказанных товаров и статус закупки.
                    </li>
                </ul>
            </div>
        <? endif ?>

    <? else: ?>
        <div class="menu-body5">
            <h1>В данном разделе корзины заказы отсутствуют</h1>

            <a href="/" class="link4">Перейти на страницу закупок</a>

            <h1>Информация</h1>
            <ul>
                <li>В корзине будут отображаться все сделанные вами заказы, с возможностью удаления и редактирования
                    их.
                </li>
                <li>Кроме всего, в корзине вы всегда можете посмотреть стоимость заказа, стоимость заказа с учетом
                    оргсбора и доставки, количество заказанных товаров и статус закупки.
                </li>
            </ul>
        </div>
    <? endif; ?>

<? else: ?>
    <? @include('.access.php'); ?>
<? endif ?>

