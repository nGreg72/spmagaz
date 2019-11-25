<? defined('_JEXEC') or die('Restricted access'); ?>


    <style>
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

        table.addpay td {
            vertical-align: top
        }

        table.addpay input.text {
            border: 1px solid #D7D7D7;
            width: 200px;
        }

        table.addpay input.text:hover {
            border: 1px solid #B61426;
            width: 200px;
        }

    </style>

<? if ($user->get_property('userID') > 0): ?>
    <div class="menu-top5">Уведомление организатору об оплате за доставку</div>
    <? if (count($zakup)): ?>
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
                                    <a href="/com/org/open/<?= $zp['id']; ?>" class="newstitle"><?= $zp['title']; ?></a>
                                    <b>Организатор</b>: <a href="/com/profile/default/<?= $zp['user'] ?>"
                                                           class="link4"><?= $zp['username'] ?></a><br>
                                    <b>Город</b>: <?= $zp['city_name_ru'] ?><br/>
                                    <b>Статус</b>: <span class="price"><?= $zp['name']; ?></span>, <?= $zp['res']; ?>
                                    заказов<br/>
                                    <b>Минималка</b>: <?= $zp['min'] ?> <?= $registry['valut_name'] ?><br/><br/>
                                </div>
                            </td>
                            <td width="245">
                                <div class="info-top"></div>
                                <div class="info-mid">
                                    <b>Сумма</b>: <?= $totalzp[$zp['id']] * $zp['curs']; ?> <?= $registry['valut_name'] ?> <? if ($zp['curs'] <> 1):?><?= $totalzp[$zp['id']] ?> у.е.<? endif; ?>
                                    <br/>
                                    <b>Оргсбор</b>: <?= $zp['proc'] ?>%<br/>

                                    <? if ($zp['dost'] > 0 and $zp['status'] > 3):
                                        $query = "SELECT `sp_ryad`.`price`,`sp_order`.`kolvo`,`sp_order`.`status`
		    FROM `sp_order` 
		    LEFT JOIN `sp_ryad` ON `sp_order`.`id_ryad`=`sp_ryad`.`id`
		    WHERE `sp_order`.`id_zp` = '{$zp['id']}' and `sp_order`.`status` != '2'";
                                        $tp = $DB->getAll($query);
                                        $totalprice = 0;
                                        foreach ($tp as $t) {
                                            $totalprice = $totalprice + ($t['kolvo'] * $t['price']);
                                        }
                                        $userdost = round(($zp['dost'] / 100) * (($totalzp[$zp['id']] * $zp['curs']) / ($totalprice / 100)), 1); ?>

                                        <b title="от поставщика до организатора, делится на всех участников">
                                            Доставка</b>: <?= $userdost ?> <?= $registry['valut_name'] ?><br/>
                                    <? else: $userdost = 0; endif; ?>
                                    <? $ttsumm = ($totalzp[$zp['id']] * $zp['curs']) + round(($totalzp[$zp['id']] * $zp['curs']) / 100 * $zp['proc'], 2) + $userdost; ?>
<!--Общая сумма, включая доплату-->
                                    <span title="Итого к оплате">Итого</span>:
                                    <span class="price"><?=($ttsumm + $addpay[$zp['id']][0]['doplata']);?><?= $registry['valut_name']?></span><br/>
                                </div>
                                <div class="info-bot"></div>
                            </td>
                        </tr>
                    </table>


                    <table class="addpay">
                        <tr>
                            <td class="tl"></td>
                            <td class="color"></td>
                            <td class="tr"></td>
                        </tr>
                        <tr>
                            <td class="color"></td>
                            <td class="color">

                                <form action="" method="post">
                                    <input name="shopidTransp" value="<?= intval($_GET['value']) ?>" type="hidden">
                                    <input name="statusTransp" value="<?= intval($_GET['status']) ?>" type="hidden">
                                    <table summary="" style="border-spacing: 0 10px;">
                                        <tbody>
                                        <tr>
                                            <td id="summPay" class="title"><b>Доплата</b></td>
                                            <td><input style="width: 100px; height: 20px; font-size: 15px; background-color: #f4f4f4;" name="summTransp"
                                                       value="<?=$addpay[$zp['id']][0]['transp'];?>" type="text"> р.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Название Банка</b> <br/>(Куда оплатили)</td>
                                            <td>
                                                <select name="bankNameTransp" style="height: 30px;" required>
                                                    <option value="" selected disabled>Укажите банк</option>
                                                    <option value="1">Сбербанк</option>
                                                    <option value="2">ВТБ24</option>
                                                    <option value="3">АЭБ</option>
                                                    <option value="4">Наличка</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Отправитель</b> <br/>(От чьего имени выполнен платёж)</td>
                                            <td>
                                                <input id="whoPay" name="whoPayTransp" required style="width: 300px; height: 20px; font-size: 15px; background-color: #f4f4f4;"/><br/>например:
                                                Феоктист Леопольдович Ъ.
                                            </td>
                                        </tr>
                                        <td>
                                            <a class="btn" style="margin-left: -168px;"
                                               href="/com/basket/?status=<?= $_GET['status'] ?>">Отмена</a>
                                            <input class="btn" style="margin-right: 0; margin-left: 170px;" id="checkSubmit" type="submit" name="register" value="Отправить">
                                        </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </form>
                            </td>
                    </table>
                </div>

            </div>
        <? endforeach; ?>
    <? else: ?>
        <div class="menu-body5">
            <h1>Такой закупки нетути.</h1>
        </div>
    <? endif; ?>

<? else: ?>
    <? @include('.access.php'); ?>
<? endif ?>