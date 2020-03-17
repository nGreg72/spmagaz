<? defined('_JEXEC') or die('Restricted access'); ?>

<? if (count($openzakup) > 0):
    $openzakup[0]['russia'] = unserialize($openzakup[0]['russia']); ?>
    <div class="menu-top5"><?= $openzakup[0]['title'] . ' - ' . $openzakup[0]['city_name_ru']; ?></div>
    <div class="menu-body5">
        <div style="display:block" class="message"><?= $message; ?></div>

        <? if ($openzakup[0]['user'] == $user->get_property('userID') OR $user->get_property('gid') > 23):?>
            <p align="right" class="open-navi">
                <? if ($openzakup[0]['user'] == $user->get_property('userID') or $user->get_property('gid') == 25):?>
                    <? if ($user->get_property('gid') == 25):?>
                        <!--				<span style="margin:0 10px">Права админа:</span> -->
                        <a href="/com/org/delsel/<?= $_GET['value']; ?>" class="link7 cross" style="margin:0;">Удалить
                            закупку</a>
                    <? endif ?>
                    <a href="/com/org/send/<?= $_GET['value']; ?>" class="link7 omail">Рассылка</a>
                    <a href="/com/org/editzp/<?= $_GET['value']; ?>" class="link7 editzp">Ред. закупку</a>
                <? endif; ?>
                <a href="/com/org/exportr/<?= $_GET['value']; ?>" class="link7 addr">Импорт/Экспорт товаров</a>
                <!--			<a href="/com/org/multi/<?= $_GET['value']; ?>" class="link7 addr">Multi</a>-->
                <!--			<a href="/com/org/multi/<?= $_GET['value']; ?>" class="link7 addr">Multi</a>-->
                <a href="/com/org/addr/<?= $_GET['value']; ?>" class="link7 addr">Добавить товар</a>
                <!--Floating basket -->
                <a href="/com/org/addr/<?= $_GET['value']; ?>" class="add_menu"></a>
                <a href="/com/org/editzp/<?= $_GET['value']; ?>" class="edit_menu"></a>
                <a href="/com/org/vieworder/<?= $_GET['value']; ?>" class="mod_menu"></a>
            </p>
            <div class="open-slash"></div>
        <? endif; ?>
        <div class="block1">
            <table>
                <tr>
                    <td width="135" valign="top">
                        <img src="<?= $img_path ?>" width="125" height="100" alt="" border="0" align="left"
                             class="photo"/>
                    </td>
                    <td valign="top">
                        <div class="text_body_full">
                            <span class="newstitle"><?= $openzakup[0]['title']; ?></span>
                            <? //print_r($t['status']); die();
                            ?>
                            <table>
                                <tr>
                                    <td width="250">
                                        <b>Организатор</b>: <a href="/com/profile/default/<?= $openzakup[0]['user'] ?>"
                                                               class="link4"><?= $openzakup[0]['username'] ?></a><br>
                                        <b>Телефон</b>: <span style="font-size: 15px;"><?= $openzakup[0]['phone'] ?></span><br/>
<!--                                        <b>Город</b>: --><?//= $openzakup[0]['city_name_ru'] ?><!--<br/>-->
                                        <b>Статус</b>:

                                        <div id="statuslist">

                                            <? if ($openzakup[0]['status'] == 0 or $openzakup[0]['status'] == 1) { ?><a
                                                href="/com/org/status/<?= intval($_GET['value']) ?>/1"><?= $statuslist[0]['name'] ?></a><? } ?>
                                            <? if ($openzakup[0]['status'] <= 2) { ?><a
                                                href="/com/org/status/<?= intval($_GET['value']) ?>/2"><br/>
                                                <br/><?= $statuslist[1]['name'] ?></a><? } ?>
                                            <? if ($openzakup[0]['status'] == 3 || ($registry['premoder'] == 1 and $openzakup[0]['status'] < 5)) { ?>
                                                <a
                                                href="/com/org/status/<?= intval($_GET['value']) ?>/3"><?= $statuslist[2]['name'] ?></a><? } ?>

                                            <? if ($openzakup[0]['type'] == 0 || $openzakup[0]['type'] == 2): ?>
                                            <? if ($openzakup[0]['status'] == 3 OR $openzakup[0]['status'] == 5) { ?><a
                                                href="/com/org/status/<?= intval($_GET['value']) ?>/4"><br/>
                                                <br/><?= $statuslist[3]['name'] ?></a><? } ?>
                                            <? if ($openzakup[0]['status'] == 4) {
                                                ?><a
                                                href="/com/org/status/<?= intval($_GET['value']) ?>/5"><?= $statuslist[4]['name'] ?></a>
                                                <br/><br/><? } ?>
                                            <? if ($openzakup[0]['status'] == 4) {
                                                ?><a
                                                href="/com/org/status/<?= intval($_GET['value']) ?>/6"><?= $statuslist[5]['name'] ?></a>
                                                <br/><br/><? } ?>
                                            <? if ($openzakup[0]['status'] >= 6) {
                                                ?>
												<? $query = "SELECT user, status FROM `sp_addpay` 
				                                  WHERE `zp_id` = '" . intval($_GET['value']) . "' AND `status` = 1 GROUP BY User ORDER BY id DESC ";
                                                $counter_confirm_user = $DB->getAll($query); ?>
												
												<? $query = "SELECT user, status FROM `sp_order` 
				                                  WHERE `id_zp` = '" . intval($_GET['value']) . "' AND (sp_order.status = 1 OR sp_order.status = 9)
				                                  GROUP BY User ORDER BY id DESC ";
                                                $counter_all_user = $DB->getAll($query); ?>
												
												<a href="/com/org/status/<?= intval($_GET['value']) ?>/7"
                                                      <?if ($counter_all_user > $counter_confirm_user):?>
                                                       onclick="return next_level (this)"
                                                        <?endif;?> >
                                                        <?= $statuslist[6]['name']?>
                                                </a> <br><br>
                                                <? } ?>
                                            <? if ($openzakup[0]['status'] == 7) {
                                                ?><a
                                                href="/com/org/status/<?= intval($_GET['value']) ?>/8"><?= $statuslist[7]['name'] ?></a>
                                                <br/><br/><? } ?>
                                            <? if ($openzakup[0]['status'] == 8) {
                                                ?><a
                                                href="/com/org/status/<?= intval($_GET['value']) ?>/10"><?= $statuslist[9]['name'] ?></a>
                                                <br/><br/><? } ?>
                                            <? if ($openzakup[0]['status'] == 10) {
                                                ?><a
                                                href="/com/org/status/<?= intval($_GET['value']) ?>/9"><?= $statuslist[8]['name'] ?></a>
                                                <br/><br/><? } ?>
                                        </div>
                                    <? endif ?>

                                        <span style="font: bold 15px tahoma;text-decoration: underline;"
                                              class="status<?= $openzakup[0]['status'] ?>"><?= $openzakup[0]['name']; ?></span>,
                                        <div style="font: bold 13px tahoma;"><?= $total_order_zp; ?> заказов<br/></div>
                                        <b>Уровень доступа</b>: <?= $openzakup[0]['levname']; ?><br/>

                                        <? if ($openzakup[0]['user'] == $user->get_property('userID') OR $user->get_property('gid') == 25):?>
                                            <a href="javascript://" onclick="$('#statuslist').slideToggle('fast');">
                                                <h1>Изменить статус</h1></a>
                                        <? endif ?>

                                    </td>

                                    <td valign="top">
                                        <? if (floatval($openzakup[0]['curs']) == 0) $openzakup[0]['curs'] = 1; ?>
                                        <b>Курс</b>: 1 у.е.
                                        = <?= $openzakup[0]['curs'] ?> <?= $registry['valut_name'] ?><br/>
                                        <? if ($openzakup[0]['proc'] > 0):?>
                                            <b>Оргсбор</b>: <?= $openzakup[0]['proc'] ?>%<br/><? endif ?>

                                        <? if ($openzakup[0]['type'] == 0 or $openzakup[0]['type'] == 2):?>
                                            <b>Минималка</b>: <span class="price"><?= $openzakup[0]['min'] ?></span>
                                            <? switch ($openzakup[0]['minType']) {
                                                case 0 :
                                                    echo "руб.";
                                                    break;
                                                case 1 :
                                                    echo "штук";
                                                    break;
                                                case 2 :
                                                    echo "кг.";
                                                    break;
                                            } ?>

                                            <br/>

                                            <? if ($openzakup[0]['min'] > 0):?><b>Собрано</b>:
                                                <? switch ($openzakup[0]['minType']) {
                                                    case 0 :
                                                        echo $items_total_width;
                                                        break;
                                                    case 1 :
                                                        echo $items_qnt_width;
                                                        break;
                                                    case 2 :
                                                        echo $items_qnt_width;
                                                        break;
                                                } ?>%
                                            <? endif ?>

                                            <!-- Добавлена информация для орга и админа / на сколько собрана закупка без ограничения до 100 процентов -->
                                            <? if ($openzakup[0]['user'] == $user->get_property('userID') OR $user->get_property('gid') > 23):?>
                                                <br><br>Информация для организаторов (не видна пользователям)<br>
                                                <? if ($openzakup[0]['min'] > 0):?>
                                                    <big><b>Закупка собрана на</b>:
                                                        <? switch ($openzakup[0]['minType']) {
                                                            case 0 :
                                                                echo $items_total_all;
                                                                break;
                                                            case 1 :
                                                                echo $items_qnt_all;
                                                                break;
                                                            case 2 :
                                                                echo $items_qnt_all;
                                                                break;
                                                        } ?>%</big><br/>
                                                <? endif ?>
                                                <? if ($openzakup[0]['minType'] == 1):?>Собрано количество:<?= $justQnt ?><? endif ?>
                                            <? endif ?>
                                        <? endif ?>


                                    </td>
                                </tr>
                            </table>

                            <? if (!empty($openzakup[0]['inform'])): ?>
                            <div style="background-color: #dadada; margin-left: -142px; ">
                                <b class="info-zak">
                                    <div style="display: flex; justify-content: space-around;">
                                        <div>!!Информация !!</div>
                                        <div>!!Информация !!</div>
                                        <div>!!Информация !!</div>
                                    </div>
                                </b><br>
                                <div style="font: bold 12px tahoma; color: black;">
                                    <?= $openzakup[0]['inform']; ?>
                                </div>
                                <? else:?>
                                <? endif ?><br/>
                                <? if ($openzakup[0]['russia'][0] > 0): ?>
                                &nbsp;<br/>
                            </div>

                            <b>Отправка в регионы России</b><br/>
                            <p>
                                <? $i = 0;
                                foreach ($delivery as $del):$i++; ?>
                                    <? if (in_array($del['id'], $openzakup[0]['russia'])):?><img
                                        src="/<?= $theme ?>images/delivery/<?= $del['img'] ?>" width="60" width="32"
                                        alt="<?= $del['name'] ?>" title="<?= $del['name'] ?>" class="deliverimg"
                                        border="1"/><? endif; ?>
                                <? endforeach; ?>
                            </p>
                        <? endif;
                        ?>
                    </td>
                </tr>
            </table>

            <? if ($openzakup[0]['file1'] || $openzakup[0]['file2'] || $openzakup[0]['file3']): ?>

            <h2>Прикрепленные файлы</h2>
            <div class="line3"></div>

            <? if ($openzakup[0]['file1']): ?>
            <span style="font: bold 12px arial; color: black;">Прайс-1 скачать:</span> <a
                    href="/<?= $openzakup[0]['file1'] ?>"><img src="/theme/sp12/images/excel-logo.png" width="35"
                                                               height="35"
                                                               style="bottom: -8px; position: relative;"></a>
            <label style="font: bold 12px arial; color: red;"><?= $openzakup[0]['price_name1'] ?>
                <br/>
                <? endif ?>

                <? if ($openzakup[0]['file2']): ?>
                <span style="font: bold 12px arial; color: black;">Прайс-2 скачать:</span> <a
                        href="/<?= $openzakup[0]['file2'] ?>"><img src="/theme/sp12/images/excel-logo.png" width="35"
                                                                   height="35"
                                                                   style="bottom: -8px; position: relative;"></a>
                <label style="font: bold 12px arial; color: green;"><?= $openzakup[0]['price_name2'] ?>
                    <br/>
                    <? endif ?>

                    <? if ($openzakup[0]['file3']): ?>
                    <span style="font: bold 12px arial; color: black;">Прайс-3 скачать:</span> <a
                            href="/<?= $openzakup[0]['file3'] ?>"><img src="/theme/sp12/images/excel-logo.png"
                                                                       width="35" height="35"
                                                                       style="bottom: -8px; position: relative;"></a>
                    <label style="font: bold 12px arial; color: blue;"><?= $openzakup[0]['price_name3'] ?>
                        <br/>
                        <? endif ?>

                        <? endif ?>

        </div>

        <div class="text-post">
            <h2><big>Описание закупки:</big></h2>

            <div class="line3"></div>
            <a href="javascript://" onclick="$('.details').slideToggle('slow');">
                <input type="button" class="pushme" value="Развернуть / Свернуть"></a>
            <div style="padding-top: 20px;" class="details">
                <? echo $openzakup[0]['text'];
                //str_replace(  "\"https://",'"http://'.$_SERVER['HTTP_HOST'].'/redirect.php?ssh=1&url=',
                //str_replace(  "\"http://", //"(<a.*?href=\"?'?)([^ \"'>]+)(\"?'?.*? >)
                //'"http://'.$_SERVER['HTTP_HOST'].'/redirect.php?url=',
                // ));
                ?>
            </div>
        </div>


        <div class="line3"></div>
        <div class="line3"></div>

        <? if ($openzakup[0]['user'] == $user->get_property('userID') OR $user->get_property('gid') > 23):?>
            <span class="newstitle2">Оплата:</span>
            <? if (count($addpay) > 0):?>

                <table class="tab_order" width="100%">
                    <tr>
                    <tr class="tab_order_name">
                        <td width="120">Пользователь</td>
                        <td width="80">Дата</td>
                        <td width="190">Сумма/<span class="blue">Сумма+Орг</span>/<span class="green">Уплочено</span>
                        </td>
                        <td>Данные плательщика</td>
                        <td width="160">Действие/Противодействие</td>
                    </tr>
<!--Получаем и подсчитываем общую сумму заказов. Далее будет использоваться для рассчёта индивид. суммы за доставку от поставщика до орга (та, которая в редактировании закупки). -->
                    <?$query = "SELECT `sp_ryad`.`price`,`sp_order`.`kolvo`,`sp_order`.`status`
                    FROM `sp_order`
                    LEFT JOIN `sp_ryad` ON `sp_order`.`id_ryad`=`sp_ryad`.`id`
                    WHERE `sp_order`.`id_zp` = '{$openzakup[0]['id']}' and `sp_order`.`status` != '2' and `sp_order`.`status` != '7'";
                    $tp = $DB->getAll($query);
                    $totalprice = 0;
                    foreach ($tp as $t) {
                        $totalprice = $totalprice + ($t['kolvo'] * $t['price']);
                    };?>
                    <!-- ОПЛАТА ЮЗЕРОВ -->
                    <? foreach ($all_payers as $itm):?>
                        <?$userdost = round(($openzakup[0]['dost'] / 100) * (($itm['tprice'] * $openzakup[0]['curs']) / ($totalprice / 100)), 1);?>
                        <tr>
                            <td class="tab_order_date td1">
                                <a class="news_body_a4"
                                   href="/com/profile/default/<?= $itm['orderUserNumber'] ?>"><?= $itm['username'] ?></a>
                                <? if ($itm['officeid'] > 0):?><br/><b style="color:blue">Выбран
                                    офис:</b> <?= $office[$itm['officeid']]['name'] ?><? endif ?>
                            </td>

                            <td class="td1">
                    <?if (isset($itm['date'])):?>
                                <span style="font-size: 12px;"><?= date('d/m/Y' , $itm['date'])?></span>
                    <?endif;?>
                            </td>
                            <td class="td1">
                    <?if ($itm['user']):?>
                                <?= round($itm['tprice'] * $openzakup[0]['curs'], 2) ?>р./<!--Сумма-->
                                <span class="blue">
                                    <?= $tpr = round($itm['tprice'] * $openzakup[0]['curs'] + $userdost +
                                        ($itm['tprice'] * $openzakup[0]['curs'] / 100 * $openzakup[0]['proc'] + $itm['over']),
                                        2) ?> <!--Сумма + орг + доставка-->
                                    р.</span>/
                                <span class="green"><?= $itm['summ'] + $itm['summExtra']?>р.</span>    <!--Заплачено-->
                                <? if ($tpr < ($itm['summ'] + $itm['summExtra']) - 1):?>
                                    <br><span style="color:red">Переплата на: <?= round(($itm['summ'] + $itm['summExtra']) - $tpr, 2) ?>
                                        руб.</span>
                                <? endif ?>
                                <? if ($tpr > ($itm['summ'] + $itm['summExtra'])):?>
                                    <br><span style="color:red">Треба доплата: <?= round($tpr - ($itm['summ'] + $itm['summExtra']), 2) ?>
                                        руб.</span>
                                <? endif ?>
                    <?endif;?>
                            </td>
                            <td class="td1">
<!--                                --><?//= $itm['card'] ?><!--<br/>-->
                                <div>
                                <?if ($itm['bankName'] == 1):?><img src="/<?= $theme ?>images/bank_SB.png" title="Сбербанк" style="width: 24px; height: 24px;"><?endif;?>
                                <?if ($itm['bankName'] == 2):?><img src="/<?= $theme ?>images/bank_VTB.png" title="ВТБ" style="width: 20px; height: 20px;"><?endif;?>
                                <?if ($itm['bankName'] == 3):?><img src="/<?= $theme ?>images/bank_AEB.png" title="АлмазЭргиэнБанк" style="width: 20px; height: 20px;"><?endif;?>
                                <?if ($itm['bankName'] == 4):?><img src="/<?= $theme ?>images/Cash.png" title="АлмазЭргиэнБанк" style="width: 20px; height: 20px;"><?endif;?>
                                </div>
                                <div>
                                <?= $itm['whoPay'] ?>
                                </div>
                            </td>
                            <td class="td2">
                    <?if ($itm['user']):?>
                                <? if ($itm['status'] == 0):?>
                                    <a href="/com/org/addpay/<?= $itm['id'] ?>"
                                       onclick="return confirm_approve(this)"><img src="/<?=$theme?>images/Confirm.png" class="payment_confirmation_yes" title="Подтвердить"></a>
                                    <a href="/com/org/delpay/<?= $itm['id'] ?>"
                                       onclick="return confirm_delete(this)"><img src="/<?=$theme?>images/Remove.png" class="payment_confirmation_no" title="Удалить"></a>
                                <? else:?>
                                    <span style="font: 14px arial; color: green;">Подтверждено</span>
                                    <br/>
                                    <? if ($itm['status'] != 4):?>
                                        <form action="/com/org/addpayplus/<?= $itm['id'] ?>/<?= $_GET['value'] ?>/"
                                              method="get">
                                            <input type="text" name="summ" value="<?= $itm['doplata'] ?>"
                                                   class="inputbox" style="width:40px"/>р.
                                            <input type="submit" value="+Доплата"/>
                                        </form>
                                    <? else:?>
                                        <small>+ Ждет доплату <?= $itm['doplata'] ?>р.</small>
                                    <? endif ?>
                                <? endif ?>
                                <? if ($itm['status'] == 4):?>
                                    <br/> Ожидается уведомление об оплате.
                                <? endif ?>
                    <?else:?>
                        <span style="font-size: 15px; color: red;">Не оплачено! Не уведомлено!</span>
                    <?endif;?>
                            </td>
                        </tr>
                        <? $itog = $itog + $itm['summ']; ?>    <!-- Сколько собрана оплаты -->
                    <? endforeach ?>
                </table>

                Собрано : <span style="font:bold 16px tahoma; color:#797979;"><?= $itog ?>р.</span>
				(учитываются и подтверждённые и НЕподтверждённые оплтаты)
                <br>

                Оплативших пользователей - <span
                        style="font:bold 16px tahoma; color:#757575;"><? echo count($counter_confirm_user) ?></span> <!-- Счётчик числа оплативших пользователей-->
				(учитываются только подтверждённые оплтаты)
            <? else:?>
                Оплаты не поступало
            <? endif ?>

            <br>
            Пользователей, участвующих в закупке - <span
                    style="font:bold 16px tahoma; color:#757575;"><? echo count($counter_all_user); ?></span>  <!-- Счётчик числа ВСЕХ пользователей-->
        <? endif ?>
        <? unset ($counter_all_user); ?>
        <br>

        <? if (($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')) AND
            ($openzakup[0]['status'] == 8 OR ($openzakup[0]['status'] == 9 OR $openzakup[0]['status'] == 10) OR $openzakup[0]['status'] == 7)):?>
            <h2>Оплата за доставку</h2>
            <div><a class="btn" href="/com/org/transPay/<?= $openzakup[0]['id'] ?>">Доставочка</a></div>
        <? endif; ?>

        <? if (($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')) AND
            ($openzakup[0]['status'] == 6)):?>
            <h2>Уведомление за пользователя</h2>
            <div><a class="btn" href="/com/org/notification/<?= $openzakup[0]['id'] ?>">Уведомления</a></div>
        <? endif; ?>
        <p>&nbsp;</p>

        <br>
        <div id="assort">
            <?
            //---------------------------------------------
            if (isset($_GET['s'])) {
                $stext = PHP_slashes(strip_tags($_GET['s']));
                if ($stext) {
                    $sqls = "and (sp_ryad.title LIKE '%$stext%'||sp_ryad.articul LIKE '%$stext%'||sp_ryad.message LIKE '%$stext%')";
                }
            }
            if (isset($_GET['c'])) {
                $stext = PHP_slashes(strip_tags(trim($_GET['c'])));
                if ($stext) {
                    $sqls = "and (sp_ryad.cat = '$stext')";
                }
            }
            $page = intval($_GET['page']);
            // Переменная хранит число сообщений выводимых на станице
            $num = 24;
            // Извлекаем из URL текущую страницу
            if ($page == 0) $page = 1;
            // Определяем общее число сообщений в базе данных
            $posts = $DB->getOne("SELECT count(sp_ryad.id) FROM sp_ryad 
			WHERE `id_zp` = " . intval($_GET['value']) . " AND `spec`='1' AND `lock` = '0' $sqls $wh_s");
            // Находим общее число страниц
            $total = intval(($posts - 1) / $num) + 1;
            // Определяем начало сообщений для текущей страницы
            $page = intval($page);
            // Если значение $page меньше единицы или отрицательно
            // переходим на первую страницу
            // А если слишком большое, то переходим на последнюю
            if (empty($page) or $page < 0) $page = 1;
            if ($page > $total) $page = $total;
            // Вычисляем начиная к какого номера
            // следует выводить сообщения
            $start = $page * $num - $num;
            // Проверяем нужны ли стрелки назад
            $link_url = '?';
            if (isset($_GET['s'])) $link_url .= "s={$_GET['s']}&";
            if (isset($_GET['c'])) $link_url .= "c={$_GET['c']}&";
            if ($page != 1) $pervpage = '<a href="' . $link_url . 'page=-1"><<</a> 
                               <a href="' . $link_url . 'page=' . ($page - 1) . '"><</a> ';
            // Проверяем нужны ли стрелки вперед
            if ($page != $total) $nextpage = '  <a href="' . $link_url . 'page=' . ($page + 1) . '">></a>
                                   <a href="' . $link_url . 'page=' . $total . '">>></a> ';
            // Находим две ближайшие станицы с обоих краев, если они есть
            if ($page - 4 > 0) $page4left = '<a href="' . $link_url . 'page=' . ($page - 4) . '">' . ($page - 4) . '</a> ';
            if ($page - 3 > 0) $page3left = '<a href="' . $link_url . 'page=' . ($page - 3) . '">' . ($page - 3) . '</a> ';
            if ($page - 2 > 0) $page2left = '<a href="' . $link_url . 'page=' . ($page - 2) . '">' . ($page - 2) . '</a> ';
            if ($page - 1 > 0) $page1left = '<a href="' . $link_url . 'page=' . ($page - 1) . '">' . ($page - 1) . '</a>  ';
            if ($page + 4 <= $total) $page4right = ' <a href="' . $link_url . 'page=' . ($page + 4) . '">' . ($page + 4) . '</a>';
            if ($page + 3 <= $total) $page3right = ' <a href="' . $link_url . 'page=' . ($page + 3) . '">' . ($page + 3) . '</a>';
            if ($page + 2 <= $total) $page2right = ' <a href="' . $link_url . 'page=' . ($page + 2) . '">' . ($page + 2) . '</a>';
            if ($page + 1 <= $total) $page1right = ' <a href="' . $link_url . 'page=' . ($page + 1) . '">' . ($page + 1) . '</a>';
            $query = "SELECT `sp_ryad`.* FROM `sp_ryad` 
		WHERE `id_zp` = " . intval($_GET['value']) . " AND `spec`='1' AND `lock` = '0' $sqls $wh_s
		ORDER BY $sort
		LIMIT $start, $num";
            $items_ryad = $DB->getAll($query);
            $out = '';
            $i = 0;
            foreach ($items_ryad as $item) {
                $i++;
                $out .= $item['id'];
                if ($i < count($items_ryad)) $out .= ',';
            }
            $query = "SELECT `sp_size`.*, IF(punbb_users.display_name != '', punbb_users.display_name, punbb_users.username) AS username
			  FROM `sp_size` 
			  LEFT JOIN `punbb_users` ON `sp_size`.`user`=`punbb_users`.`id`
			  WHERE `id_ryad` IN ($out) 
			  ORDER BY LENGTH(`sp_size`.`name`), CONVERT( `sp_size`.`name` , CHAR )";
            $itemsSZ = $DB->getAll($query);
            $items_size_name = [];
            $items_sizeTovar = [];
            foreach ($itemsSZ as $item) {
                $itemssize[$item['id_ryad']][] = $item;
                if ($item['name'] && $item['duble'] == 1) $items_size_name[trim($item['name'])] = $item['name'];
                $items_sizeTovar[$item['id_ryad']][$item['duble']][] = $item;
            }
            array_unique($items_size_name);
            asort($items_size_name);
            $query = "SELECT cat FROM `sp_ryad` WHERE `id_zp` = " . intval($_GET['value']) . " AND `spec`='1' AND `lock` = '0' and cat!='' GROUP BY cat";
            $cats1 = $DB->getAll($query);
            ?>

            <form action="" method="post" style="margin-top: -30px; background: #DADADA;border-radius: 2px;">
                <table>
                    <tr>
                        <? if (count($cats1) > 0):?>
                            <td>
                                <b>Категории:</b><br/>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $(".selcatzp").change(function () {
                                            var text = $(".selcatzp option:selected").val();
                                            window.location = "/com/org/open/<?=$_GET['value']?>/?c=" + text;
                                        });
                                    });
                                </script>

                                <select class="selcatzp inputbox2">
                                    <option value="">Все</option>
                                    <? foreach ($cats1 as $catit):?>
                                        <option value="<?= $catit['cat'] ?>"
                                                <? if ($_GET['c'] == $catit['cat']): ?>selected<? endif ?>><?= $catit['cat'] ?></option>
                                    <? endforeach; ?>
                                </select>
                                <!--  <div id="sidebar">
	<a href="/com/org/open/<?= $_GET['value'] ?>/?c=" <? if ($_GET['c'] == ''):?>class="active"<? endif; ?>>Все</a>
	<? foreach ($cats as $catit):?>
		<a href="/com/org/open/<?= $_GET['value'] ?>/?c=<?= $catit['cat'] ?>" <? if ($_GET['c'] == $catit['cat']):?>class="active"<? endif; ?>><?= $catit['cat'] ?></a>
	<? endforeach; ?>
  </div>
-->

                            </td>
                        <? endif ?>

                        <td>
                            Размеры
                            <select onchange="submit();" name="typeSize">
                                <option value="0"
                                        <? if ($registry['typeSize'] == ''): ?>selected<? endif ?>>Все</option>
                                <? foreach ($items_size_name as $item):?>
                                    <option value="<?= $item['name'] ?>"
                                            <? if ($registry['typeSize'] == $item['name']): ?>selected<? endif ?>><?= $item['name'] ?></option>
                                <? endforeach ?>
                            </select>
                        </td>

                        <td>
                            Цены
                            <select onchange="submit();" name="typePrice">
                                <option value="3"
                                        <? if ($registry['typePrice'] == 3): ?>selected<? endif ?>>По умолчанию</option>
                                <option value="1"
                                        <? if ($registry['typePrice'] == 1): ?>selected<? endif ?>>По возрастанию</option>
                                <option value="2"
                                        <? if ($registry['typePrice'] == 2): ?>selected<? endif ?>>По убыванию</option>
                            </select>
                        </td>

                        <td>
                            Тип отображения
                            <select onchange="submit();" name="typeView">
                                <option value="0"
                                        <? if ($registry['short'] == 0): ?>selected<? endif ?>>Расширенный</option>
                                <option value="1"
                                        <? if ($registry['short'] == 1): ?>selected<? endif ?>>Краткий</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </form>
        <div style="margin-top: 20px; padding: 5px 0 5px 0; background-color: #dadada;">
            <form method="get" action="">
                Поиск по товарам:
                <input type="text" class="inputbox" name="s" value="<?= $_GET['s'] ?>">
                <input type="submit" value="Искать"> <a href="?s=" class="link4">Сброс</a>
            </form>
        </div>
            <? if ($total > 1) echo '<div class="pagenation" align="center" style="margin-top: 25px; margin-bottom: -10px;">'
                . $pervpage . $page4left . $page3left . $page2left . $page1left . '<span>' . $page . '</span>' . $page1right . $page2right . $page3right . $page4right . $nextpage . '</div>'; ?>


            <? // все размеры
            $query = "SELECT `sp_size`.`name`
			  FROM `sp_size` 
			  LEFT JOIN `sp_ryad` ON `sp_ryad`.`id` = `sp_size`.`id_ryad`
			  WHERE `sp_ryad`.`id_zp` = " . intval($_GET['value']) . " AND `sp_size`.`duble`=1 AND `sp_size`.`name`!=''
			  GROUP BY `sp_size`.`name`
			  ORDER BY LENGTH(`sp_size`.`name`), CONVERT( `sp_size`.`name` , CHAR )
			  ";
            $items_size_name = $DB->getAll($query);
            ?>

            <div class="clearfix"></div>

            <br/><br/>

            <!--Формирование каталога внутри закупки-->
            <?
            if ($openzakup[0]['curs'] == 0) $openzakup[0]['curs'] = 1;
            if (count($items_ryad) > 0):
                $i = 0;
                if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')) //todo админы и орги видят все ряды в каталоге (в т.ч. выключенные tempOff)
                    foreach ($items_ryad as $item_r): $i++;
                        if($registry['short'] == 1) include('xshort.php');
                        else include ('xfull.php');
                    endforeach;
                else
                    foreach ($items_ryad as $item_r): $i++;
                        if ($item_r['tempOff'] == 0)                                                         //todo Пользователи не видят выключенные ряды. Если временно нет в наличии
                            if ($registry['short'] == 1) include('xshort.php');
                            else include('xfull.php');
                    endforeach;
                ?>

                <? if ($total > 1) echo '<div class="pagenation" align="center" >'
                . $pervpage . $page4left . $page3left . $page2left . $page1left . '<span>' . $page . '</span>' . $page1right . $page2right . $page3right . $page4right . $nextpage . '</div>'; ?>

            <? endif; ?>

            <div class="clearfix"></div>

            <? if (count($items_ryad) > 9):?>
                <!--<div style="float:right">Тип отображения
<form action="" method="post">
<select class="selView inputbox" onchange="submit();" name="typeView">
	<option value="0" <? if ($registry['short'] == 0):?>selected<? endif ?>>Расширенный</option>
	<option value="1" <? if ($registry['short'] == 1):?>selected<? endif ?>>Краткий</option>
</select>
</form>
</div>-->
            <? endif ?>

            <div class="clearfix"></div>

        </div>

        <? if (($openzakup[0]['status'] == 3 or $openzakup[0]['status'] == 5) and $openzakup[0]['type'] != 2):?>
            <a href="/com/org/addo/<?= $openzakup[0]['id'] ?>" class="addrorder"
               title="Добавить заказ, указав данные из прайса или с сайта поставщика">Добавить заказ</a>
            <p>*чтобы добавить в корзину товары не представленные в данном альбоме или заказать свой размер</p>
<!--        <a href="/com/org/addo/<?= $openzakup[0]['id'] ?>" class="addorder_menu"></a> <!-- Floating basket -->
        <? endif ?>


        <div class="line3"></div>
        <? if (floatval($openzakup[0]['curs']) == 0) $openzakup[0]['curs'] = 1;
        if (count($allsize) > 0):?>
            <span class="newstitle2">Уже заказали: <small>(отображаются последние 10 заказов)</small></span>

            <div style="width: 195px; margin-left: 440px;">
                <? if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')):?>
                    <div><a href="/com/org/vieworder/<?= $openzakup[0]['id'] ?>" class="link4 tbotd"
                            style="font:bold 14px arial; text-decoration: none">
                    </div>
                    <div><img src="/<?= $theme ?>images/moderate.png"></div>
                    <div style="text-indent: 22px;margin-top: -24px;">Модерировать заказы</div></a>
                <? endif ?>
            </div>

            <table class="tab_order" width="100%" style="margin-top:23px">
                <tr>

                <tr class="tab_order_name">
                    <td width="16%">Пользователь</td>
                    <td width="50%">Название</td>
                    <td width="40">Кол-во</td>
                    <td width="50"><span title="цена товара без орг процента" class="green">Цена заказа</span></td>
                    <? if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')):?>
                        <td width="50"><span title="цена товара с орг процентом">Цена+Орг</span></td>
                        <? if ($openzakup[0]['dost'] > 0 and $openzakup[0]['status'] >= 3):?>
                            <td width="50"><span title="Доставка">Доставка</span></td>
                            <td width="50"><span title="Цена + Орг + Доставка" class="blue">Итого</span></td>
                        <? endif; ?>
                    <? endif; ?>
                </tr>

                <? if ($openzakup[0]['dost'] > 0 and $openzakup[0]['status'] >= 3):
                    $query = "SELECT `sp_ryad`.`price`,`sp_order`.`kolvo`,`sp_order`.`status`
                                FROM `sp_order` 
                                LEFT JOIN `sp_ryad` ON `sp_order`.`id_ryad`=`sp_ryad`.`id`
                                WHERE `sp_order`.`id_zp` = '{$openzakup[0]['id']}' and `sp_order`.`status` != '2'";
                    $tp = $DB->getAll($query);
                    $totalprice = 0;
                    foreach ($tp as $t) {
                        //if($t['status']==2)continue;
                        $totalprice = $totalprice + ($t['kolvo'] * $t['price']);
                    }
                endif; ?>

                <? foreach ($allsize as $itm):?>
                    <?
                    $timee = explode(':', $itm[1]);
                    $datee = explode('.', $timee[0]);
                    $datee = $datee[2] . '/' . $datee[1];
                    $timee = explode('.', $timee[1]);
                    $timee = $timee[0] . ' ч, ' . $timee[1] . ' мин.';
                    if ($itm[9] == 2 and $itm[9] == 7) continue;
                    if (/*$itm[3] == 1 OR*/ $user->get_property('gid') == 25 OR $itm[4] == $user->get_property('userID') OR $openzakup[0]['user'] == $user->get_property('userID'))
                        $linku = '<a href="/com/profile/default/' . $itm[4] . '" class="link4">' . $itm[5] . '</a>';
                    else $linku = 'Аноним';
                    ?>

                    <tr class="<? if ($itm[9] == 1 || $itm[9] == 3 || $itm[9] == 4 || $itm[9] == 5):?>is_yes<? endif ?>
                        <? if ($itm[9] == 2):?>is_deny<? endif ?>
                        <? if ($itm[9] == 7):?>is_no<? endif ?>
                        <? if ($itm[9] == 8):?>is_accept<? endif ?>">


                        <td width="120" class="tab_order_date td1"><?= $linku ?><br/>
                            <span>дата: <?= $datee ?></span>
                            <? if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')):?>
                                <br/><a class="news_body_a4" href="/com/org/delrz/<?= $itm[8]; ?>/"
                                        onclick="return confirm_delete_ryad(this)">удалить</a>
                            <? endif ?>
                        </td>


                        <td class="td1">
                            <?= $itm[0]['title'] ?><br/>
                            <? if (!empty($itm[0]['articul'])):?><b>Артикул:</b> <?= $itm[0]['articul'] ?><? endif; ?>
                            <br/>
                            <? if (!empty($itm[6])):?><b>Размер:</b> <?= $itm[6] ?><? endif; ?><br/>
                            <? if (!empty($itm[7])):?><b>Цвет:</b> <?= $itm[7] ?><? endif; ?>
                            <? if (!empty($itm[10])  and $user->get_property('gid') == 25
                                    OR $itm[4] == $user->get_property('userID')
                                    OR $openzakup[0]['user'] == $user->get_property('userID')):                  //Доступ к полям "доп. инфо" только админам и оргам ):?>
                                <b>Доп.инфо:</b> <?= $itm[10] ?>
                            <? endif; ?>
                            <br/>

                            Статус заказа: <? if ($itm[9] == 0):?>Новый
                            <? elseif ($itm[9] == 1):?>Включен в счет
                            <? elseif ($itm[9] == 2):?>Отказано
                            <? elseif ($itm[9] == 3):?>Не оплачен
                            <? elseif ($itm[9] == 4):?>Оплачен
                            <? elseif ($itm[9] == 5):?>Раздача
                            <? elseif ($itm[9] == 6):?>Архив
                            <? elseif ($itm[9] == 7):?>Нет в наличии
                            <? elseif ($itm[9] == 8):?>В обработке
                            <? endif ?>


                        </td>

                        <td class="td1"><?= $itm[2] ?> шт.</td>

                        <td class="td1"><span title="цена товара без орг процента"
                                              class="green"><?= $itm[2] * $itm[0]['price'] * $openzakup[0]['curs'] ?>
                                р</span></td>

                        <? if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')):?>

                            <td class="td1"><span title="цена товара с орг процентом"
                                                  class="blue"><?= round(($itm[0]['price'] * $openzakup[0]['curs'] * $itm[2]) + ($itm[0]['price'] * $itm[2] * $openzakup[0]['curs']) / 100 * $openzakup[0]['proc'], 2) ?>
                                    р</span>
                            </td>

                            <? if ($openzakup[0]['dost'] > 0 and $openzakup[0]['status'] >= 3):?>
                                <td class="td1">
                                    <span title="Доставка"><? if ($itm[9] != 2 and $itm[9] != 7):?><?= $userdost = round(($openzakup[0]['dost'] / 100) * (($itm[0]['price'] * $openzakup[0]['curs'] * $itm[2]) / ($totalprice / 100)), 2); ?>р<? else: $userdost = 0; ?>--<? endif ?></span>
                                </td>
                                <td class="td1">
                                <span title="общая цена с учетом оргпроцента"><?= round(($itm[0]['price'] * $openzakup[0]['curs'] * $itm[2]) + ($itm[0]['price'] * $itm[2] * $openzakup[0]['curs']) / 100 * $openzakup[0]['proc'] + $userdost, 2) ?>
                                    р</span>
                                </td>
                            <? endif; ?>
                        <? endif; ?>
                    </tr>
                    <? if ($itm[9] != 2 and $itm[9] != 7) $totalp = $totalp + ($itm[0]['price'] * $openzakup[0]['curs'] * $itm[2]);endforeach ?>
            </table>

<!--            <?/* if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')):*/?>
                <p align="right">
                    Общая сумма заказа:<span style="font: bold 14px arial; color: blue;"> <?/*= $totalp */?> р. </span><br/>
                    Сумма заказа с учетом оргпроцента: <span
                            style="font: bold 14px arial; color: blue;"> <?/*= $totalp + round($totalp / 100 * $openzakup[0]['proc'], 2) */?>
                        р. </span><br/>
                    <?/* if ($openzakup[0]['dost'] > 0 and $openzakup[0]['status'] >= 3):*/?>
                        Сумма доставки: <?/*= $openzakup[0]['dost'] * $openzakup[0]['curs'] */?> р.<br/>
                    <?/* endif */?>
                </p>
            --><?/* endif; */?>
        <? endif ?>
        <br>
        <form method="get" action="" style="padding: 6px 0 6px 0; background-color: #dadada;">
            Поиск по товарам:
            <input type="text" class="inputbox" name="s" value="<?= $_GET['s'] ?>">
            <input type="submit" value="Искать"> <a href="?s=" class="link4">Сброс</a>
        </form>

        <div id="comment" class="line3"></div>
        <? @include('comments.php'); ?>
        <div class="line3"></div>
    </div>


<? else: ?>
    <div class="menu-top5">Ошибка</div>

    <div class="menu-body5">
        <h1>Такой закупки не существует</h1>

        <p><a href="/" class="link4">На главную.</a></p>

        <p>Возможные причины ошибки:</p>
        <ul>
            <li>Закупка была удалена</li>
            <li>Вы набрали неверный адрес страницы</li>
        </ul>
    </div>

<? endif; ?>

<?$temp = str_replace("'", "",$itm[0]['title']) ;?> <!--боремся с кавычками! заменяем их, чтобы sweetalert не гнал дуру!-->

<script src="/<?=$theme?>js/sweetalert2/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" type="text/css" href="/<?=$theme?>js/sweetalert2/dist/sweetalert2.css">
<script type="text/javascript">
    function confirm_approve(ln) {
        var link = ln.href; // Получаем значение тега href
        swal({
            title: 'Деньги поступили ?', // Заголовок окна
            type: "question", // Тип окна
            showCancelButton: true, // Показывать кнопку отмены
        }).then(function () {
            window.location.href = link;
        })
        return false;
    }
    function confirm_delete(ln) {
        var link = ln.href; // Получаем значение тега href
        var text = " Отмена невозможна!";
        swal({
            title: 'Удалить уведомление ?', // Заголовок окна
            type: "question", // Тип окна
            text: text,
            showCancelButton: true, // Показывать кнопку отмены
        }).then(function () {
            window.location.href = link;
        })
        return false;
    }
    function confirm_delete_ryad(ln) {
        var link = ln.href; // Получаем значение тега href
        var text = " Отмена невозможна!";
        swal({
            title: 'Вы действительно хотите удалить <?= $temp ?> ?', // Заголовок окна
            type: "question", // Тип окна
            text: text,
            showCancelButton: true, // Показывать кнопку отмены
        }).then(function () {
            window.location.href = link;
        })
        return false;
    }
	    function next_level(ln) {
        var link = ln.href; // Получаем значение тега href
        var text = "Без уведомления пользователи потеряют свои заказы на следующих уровнях закупки!";
        swal({
            title: 'Внимание! Не все пользователи уведомили!!! ', // Заголовок окна
            type: "warning", // Тип окна
            text: text,
            showCancelButton: true, // Показывать кнопку отмены
        }).then(function () {
            window.location.href = link;
        })
        return false;
    }
</script>

<script>
    $(document).ready(function(){
        $(".tempOffChangeStatus").change(function(){

            var rel = $(this).attr("rel");
            var offStatus = $(this).val();

            $.post("/theme/sp12/component/org/ajax.php", {
                    id: rel,
                    id_zp: <?=$openzakup[0]['id']?>,
                    offStatus: offStatus,
                    event: "tempOffStatus"
                },
                function(j_data){

                var new_data = $.parseJSON(j_data);
                var id_zp = 'item ' + new_data;


                $(new_data).css('border' , '2px solid red');
                    console.log(id_zp);
                })
        })
    })
</script>