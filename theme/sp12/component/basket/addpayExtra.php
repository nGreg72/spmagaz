<? defined('_JEXEC') or die('Restricted access'); ?>

    <link href="/theme/sp12/css/tiny-date-picker.css" rel="stylesheet" type="text/css">
    <script src="/theme/sp12/js/tiny-date-picker.js"></script>

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
    <div class="menu-top5">Уведомление организатору о доплате</div>
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
Удалить, если норм                  <? //$ttsumm = ($totalzp[$zp['id']] * $zp['curs']) + round(($totalzp[$zp['id']] * $zp['curs']) / 100 * $zp['proc'], 2) + $userdost; ?>
                                    <? $ttsumm = round(($totalzp[$zp['id']] * $zp['curs']) + ($totalzp[$zp['id']] * $zp['curs']) / 100 * $zp['proc'] + $userdost +$itap['doplata'] + $extraCharge - $itap['summExtra'], 2); ?>									
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
                                    <input name="shopidExtra" value="<?= intval($_GET['value']) ?>" type="hidden">
                                    <input name="statusExtra" value="<?= intval($_GET['status']) ?>" type="hidden">
                                    <table summary="">
                                        <tbody>
                                        <tr>
                                            <td id="summPay" class="title"><b>Доплата</b></td>
                                            <td><input class="text int  amount" id="summ" name="summExtra"
                                                       value="<?=$addpay[$zp['id']][0]['doplata'];?>" type="text"> р.
                                            </td>
                                        </tr>
                                        <!--    --><? //endif;
                                        ?>

                                        <!--		<tr>-->
                                        <!--                    <td id="datePay" >Дата оплаты</td>-->
                                        <!--                    <td><input id="date" name="date" class="text int"  value="" type="text"></td>-->
                                        <!--		</tr>-->
                                        <!--                <tr>-->
                                        <!--                    <td><b>Название Банка</b> <br/>(СБ, ВТБ, АЭБ ...)</td>-->
                                        <!--                    <td><input id="bankName" name="bankName" class="text int" required/></td>-->
                                        <!--                </tr>                                -->
                                        <!--                <tr>-->
                                        <!--                    <td id="cardPay" class="title"><b>Номер вашей карты</b> <br/> (последние 4 цифры)</td>-->
                                        <!--			<td><input class="text int " id="desc" name="desc" type="text"></td>-->
                                        <!--		</tr>-->
                                        <!--                <tr>-->
                                        <!--                    <td><b>Отправитель</b> <br/>(От кого выполнен платёж)</td>-->
                                        <!--                    <td><input id="whoPay" name="whoPay" class="text int" required/></td>-->
                                        <!--                </tr>-->
                                        <!--                -->
                                        <!--		<tr>-->

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

                    <!--Подключаем плагин маску-->
                    <script type="text/javascript" src="/theme/sp12/js/jquery.inputmask.min.js"></script>

                    <!--Валидация номера карты-->
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $("#desc").inputmask("xxxx-xxxx-xxxx-9999");//маска
                        });
                    </script>

                    <script>
                        // Добавляем календарик в поле ввода даты платежа
                        TinyDatePicker(document.querySelector('#date'));
                    </script>

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