<? defined('_JEXEC') or die('Restricted access'); ?>
<? if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')): ?>

<?include "lib/db_requests.php"?>

    <style>
        #user-name {
            color: #0C6FBB;
            text-decoration: none;
            font: bold 17px arial;
        }
    </style>
    <div class="menu-top5">Все заказы - <b><?= $openzakup[0]['title'] ?></b> - <?= $openzakup[0]['city_name_ru']; ?>
    </div>
    <div class="menu-body5">
        <div style="display:block" class="message"><?= $message; ?></div>
        <? if (count($allsize) > 0 and count($openzakup) > 0): ?>
            <span class="newstitle2">Все заказы  <? if ($_GET['u']): ?> <b><?= $allsize[0][5] ?></b>
        <? endif ?>:
            </span>
        <? if ($_GET['u']): ?><br/><a href="?u=<?= $itm =0; $itm[4] ?>" class="link4"><< все заказы</a>
        <? endif ?>

        <!-- Рассылка отказникам -->
        <div><a href="/com/org/sendRefuse/<?= $_GET['value']; ?>" class="link7 omail">Рассылка пользовательям со
                статусом "Отказно"</a></div>


        <!-- Экспорты всякие -->
        <div class="line3"></div>
        <div align="left" class="exports-moderate"><!--<b>Экспорт в Excel:</b>-->
            <a class="org-status" href="/com/org/export/<?= $openzakup[0]['id'] ?>/1/" class="link4 tbotd">поставщику</a>

            <a class="org-status" href="/com/org/export/<?= $openzakup[0]['id'] ?>/7/" class="link4 tbotd">по группам</a>

            <a class="org-status" href="/com/org/export/<?= $openzakup[0]['id'] ?>/6/" class="link4 tbotd">дозаказ NEW</a> |

            <a class="org-status" href="/com/org/export/<?= $openzakup[0]['id'] ?>/2/" class="link4 tbotd">по
                пользователям</a> |
            <a class="org-status" href="/com/org/export/<?= $openzakup[0]['id'] ?>/3/" class="link4 tbotd">печать
                этикеток</a> |
            <!--<a class="org-status" href="/com/org/export/<?= $openzakup[0]['id'] ?>/4/" class="link4 tbotd">склад</a> |	-->
            <!--Пользователя/телефон/закупка-->
            <a class="org-status" href="/com/org/export/<?= $openzakup[0]['id'] ?>/5/" class="link4 tbotd">участники</a>
        </div>
        <div class="line3"></div>

        <table class="tab_order" width="100%">
            <tr>
            <tr class="tab_order_name">
                <td width="16%">Пользователь</td>
                <td>Название закупки</td>
                <td width="5%">Кол-во</td>
                <td width="10%"><span title="цена товара без орг процента" class="green">Цена заказа</span></td>
                <? if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')): ?>
                    <td width="9%"><span title="цена товара с орг процентом">Цена +Орг</span></td>
                    <? if ($openzakup[0]['dost'] > 0 and $openzakup[0]['status'] >= 3): ?>
                        <td width="50"><span title="Доставка">Доставка</span></td>
                        <td width="9%"><span title="Цена + Орг + Доставка" class="blue">Итого</span></td>
                    <? endif; ?>
                <? endif; ?>
                <td>Статус</td>
            </tr>

            <?
            //print_r($openzakup);exit;

            if ($openzakup[0]['dost'] > 0 and $openzakup[0]['status'] >= 3):
                $query = "SELECT `sp_ryad`.`price`,`sp_order`.`kolvo`,`sp_order`.`status`
		            FROM `sp_order` 
		            LEFT JOIN `sp_ryad` ON `sp_order`.`id_ryad`=`sp_ryad`.`id`
		            WHERE `sp_order`.`id_zp` = '{$openzakup[0]['id']}' and `sp_order`.`status` != '2' and `sp_order`.`status` != '7'";
                $tp = $DB->getAll($query);

                $totalprice = 0;
                foreach ($tp as $t) {
                    //if($t['status']==2)continue;
                    $totalprice = $totalprice + ($t['kolvo'] * $t['price']);
                }
            endif; ?>

            <? if (floatval($openzakup[0]['curs']) == 0) $openzakup[0]['curs'] = 1; ?>
            <? $totaldost = 0;
            $summExtraCharge = 0;                                                                      // Объявляем переменную общей наценки
            foreach ($allsize as $itm):

                $timee = explode(':', $itm[1]);
                $datee = explode('.', $timee[0]);
                $datee = $datee[2] . '/' . $datee[1];
                $timee = explode('.', $timee[1]);
                $timee = $timee[0] . ' ч, ' . $timee[1] . ' мин.';

//  row completion request ------------------------------------------------------------------------------------------
                $temp = new dbrequests();
                $mark = $temp->is_row_complite($itm[0]['id'], $itm['current_row']);
//  row completion request-------------------------------------------------------------------------------------------

               if ($itm[3] == 0 OR $user->get_property('gid') == 25 OR $itm[4] == $user->get_property('userID') or $openzakup[0]['user'] == $user->get_property('userID'))
                    $linku = '<a href="/com/profile/default/' . $itm[4] . '" id="user-name">' . $itm[5] . '</a>'; else $linku = 'Аноним';
                ?>
                <tr id="item<?= $itm[10] ?>"
                    class="<? if ($itm[9] == 1 || $itm[9] == 3 || $itm[9] == 4 || $itm[9] == 5):?>is_yes<? endif ?>
			<? if ($itm[16] == 1):?>is_tempOff<? endif ?>
			<? if ($itm[9] == 2):?>is_deny<? endif ?>
			<? if ($itm[9] == 7):?>is_no<? endif ?>
			<? if ($itm[9] == 8):?>is_accept<? endif ?>
			<? if ($itm[9] == 9):?>is_arrived<? endif ?>">
                    <? //print_r($allsize[13]); die;
                    ?>
                    <td width="100" class="tab_order_date td1"><?= $linku ?>

                        <!-- <? if ($addpayN[$itm[4]]['status'] > 0):?> (<span style="color:blue">Оплачен</span>)<? endif ?> -->
                        <br>
                        <? if ($addpayN[$itm[4]]['status'] > 0):?><img src="/theme/sp12/images/Coin2.gif"
                                                                       style="width:25px;"><? endif ?>
                        <? if ($itm[13] == 7):?><img src="/theme/sp12/images/banUser.png"
                                                     style="width:25px;"><? endif ?>
                        <? if ($itm[15] == 1):?><img src="/theme/sp12/images/WA_small_logo.png"
                                                     style="width:25px;"><? endif ?>
                        <br/><span>дата: <?= $datee ?></span><br/><br/>

                        <div>
                            <div><a href="/com/org/editr/<?= $itm[0]['id']; ?>/<?= intval($_GET['value']); ?>"
                                    style="font:16px tahoma; color:green;">Ред. товар</a></div>
                            <div style="padding-top:7px;"><a
                                        href="/com/org/edito/<?= $itm[10]; ?>/<?= intval($_GET['value']); ?>"
                                        style="font:16px tahoma; color:green;">Ред. заказ</a></div>
                        </div>

                        <? if (!empty($itm[12])):?><br/><b>ЦР:</b> <?= $itm[12] ?><? endif; ?><br/>
                    </td>
                    <td class="td1">
                        <a href="/com/org/ryad/<?= $itm[0]['id_zp']; ?>/<?= $itm[0]['id']; ?>">
                        <span <?if ($mark):?> style="background-color: khaki;" ><?endif;?>
                            <u><?= $itm[0]['title'] ?></u>
                        </span>
                        </a>
                        <br/>

                        <? if (!empty($itm[0]['articul'])):?><b>Артикул:</b> <?= $itm[0]['articul'] ?><br/><? endif; ?>
                        <? if (!empty($itm[7])):?><b>Размер:</b> <?= $itm[7] ?><br/><? endif; ?>
                        <? if (!empty($itm[8])):?><b>Цвет:</b> <?= $itm[8] ?><br/><? endif; ?>
                        <? if (!empty($itm[11]) && ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID'))):?>
                            <b>Доп.инфо:</b> <i><?= htmlspecialchars_decode($itm[6]) ?></i>
                        <? endif; ?>
                    </td>
                    <td class="td1"><?= $itm[2] ?> шт.</td>

                    <td class="td1">
                        <? if ($openzakup[0]['curs'] > 1):?>
                            <span style="color: #2c4c9e"
                                  title="цена товара без орг процента в исходной валюте"><b><?= ($itm[0]['price']) * $itm[2] ?></b> у.е.</span>
                            <br/>
                        <? endif; ?>
                        <? if ($itm[2] > 1) :?><span style="font-size: 12px;" title="цена за еденицу товара">
                            <b><?= $itm[0]['price'] * $openzakup[0]['curs'] ?></b> р.</span><br/><br/><? endif; ?>
                        <span title="цена товара без орг процента"><b><?= $itm[2] * $itm[0]['price'] * $openzakup[0]['curs'] ?></b> р.</span><br/><br/>

                        <!-- todo Ввод организатором наценки-->
                        <form action="/com/org/addextraCharge/<?= $itm[10] ?>/<?= $_GET['value'] ?>/" method="get">
                            <input type="text" name="extraCharge" value="<?= $itm[14] ?>"
                                   style="font: 12px Arial; width:40px;"/>р.
                            <input type="submit" class="smallRoundBtn" value="Наценка"/>
                        </form>
                    </td>

                    <? if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')):?>
                        <td class="td1"><span title="цена товара с орг процентом"
                                              class="blue"><?= round(($itm[0]['price'] * $openzakup[0]['curs'] * $itm[2]) + ($itm[0]['price'] * $itm[2] * $openzakup[0]['curs']) / 100 * $openzakup[0]['proc'], 2) ?>
                                р</span></td>

                        <? if ($openzakup[0]['dost'] > 0 and $openzakup[0]['status'] >= 3):?>
                            <td class="td1">
				  <span title="Доставка"><? if ($itm[9] != 2 and $itm[9] != 7):?>
                          <?= $userdost = round(($openzakup[0]['dost'] / 100) * (($itm[0]['price'] * $openzakup[0]['curs'] * $itm[2]) / ($totalprice / 100)), 2); ?>р
                      <? else: $userdost = 0; ?>
                      <? endif ?></span>
                            </td>
                            <td class="td1">
                                <span title="общая цена с учетом оргпроцента"><?= round(($itm[0]['price'] * $openzakup[0]['curs'] * $itm[2]) + ($itm[0]['price'] * $itm[2] * $openzakup[0]['curs']) / 100 * $openzakup[0]['proc'] + $userdost, 2) ?>
                                    р</span>
                            </td>

                        <? endif; ?>


                    <? endif; ?>
                    <td class="td2">

                        <form action="" method="post">
                            <div class="styled-select-order-status">
                                <select name="moderate<?= $itm[10] ?>" class="selmod inputbox" style="width:130px" rel="<?= $itm[10] ?>">
                                    <option value="0" <? if ($itm[9] == 0): ?>selected<? endif ?>>Новый</option>
                                    <option value="8" <? if ($itm[9] == 8): ?>selected<? endif ?>>В обработке</option>
                                    <option value="1" <? if ($itm[9] == 1): ?>selected<? endif ?>>Включено в счет</option>
                                    <option value="2" <? if ($itm[9] == 2): ?>selected<? endif ?>>Отказано</option>
                                    <option value="7" <? if ($itm[9] == 7): ?>selected<? endif ?>>Нет в наличии</option>
                                    <option value="9" <? if ($itm[9] == 9): ?>selected<? endif ?>>Прибыл</option>
                                    <? if ($openzakup[0]['type'] == 1):?>
                                        <option value="3" <? if ($itm[9] == 3): ?>selected<? endif ?>>Не оплачен</option>
                                        <option value="4" <? if ($itm[9] == 4): ?>selected<? endif ?>>Оплачен</option>
                                        <option value="5" <? if ($itm[9] == 5): ?>selected<? endif ?>>Раздача</option>
                                        <option value="6" <? if ($itm[9] == 6): ?>selected<? endif ?>>Архив</option>
                                    <? endif ?>
                                </select><br/>
                            </div>
                            <div>
                                <? if (!$_GET['u']): ?><br/>
                                <div><a href="?u=<?= $itm[4] ?>" style="font: 18px tahoma;">Все заказы
                                        <!--  User Name disconnected <?= $itm[5] ?>> -->  </a><? endif ?></div>
                                <div style="padding-top: 9px;"><a href="/com/org/move/<?= $_GET['value'] ?>/<?= $itm[10] ?>/"
                                            title="Перенести заказ в другой выкуп">Перенести заказ</a></div>
                                <div style="padding-top: 9px;"><a href="/com/org/delrz/<?= $itm[10]; ?>/" style="color:red;"
                                                                  onclick="return confirm_delete_ryad(this)">Удалить заказ</a></div>
                            </div>
                        </form>
                    </td>
                </tr>


                <!--Подсчёт оуммы с учётом курса рубля И в оригинальной валюте . Статус "В обработке"-->
                <? if ($itm[9] == 8): ?><? $obr = $obr + ($itm[0]['price'] * $itm[2] * $openzakup[0]['curs']) ?><? endif ?>
                <? if (($itm[9] == 8) AND $openzakup[0]['curs'] > 1): ?><? $obrOriginalPrice = $obrOriginalPrice + ($itm[0]['price'] * $itm[2]) ?><? endif ?>
                <!--Подсчёт оуммы с учётом курса рубля И в оригинальной валюте . Статус "Включено в счёт "-->
                <? if ($itm[9] == 1): ?><? $inc = $inc + ($itm[0]['price'] * $itm[2] * $openzakup[0]['curs']) ?><? endif ?>
                <? if (($itm[9] == 1) AND $openzakup[0]['curs'] > 1): ?><? $incOriginalPrice = $incOriginalPrice + ($itm[0]['price'] * $itm[2]) ?><? endif ?>

                <? if ($itm[9] == 2): ?><? $den = $den + ($itm[0]['price'] * $itm[2] * $openzakup[0]['curs']) ?><? endif ?>
                <? if ($itm[9] == 7): ?><? $noavil = $noavil + ($itm[0]['price'] * $itm[2] * $openzakup[0]['curs']) ?><? endif ?>

                <? if ($itm[9] == 9):?><? $arrived = $arrived + ($itm[0]['price'] * $itm[2] * $openzakup[0]['curs']) ?><? endif; ?>


                <? if ($itm[9] != 2 and $itm[9] != 7 and $itm[16] != 1) $totalp = $totalp + ($itm[0]['price'] * $itm[2] * $openzakup[0]['curs']);
                $totaldost += $userdost;
                $summExtraCharge = $summExtraCharge + $itm[14];                                                    //Подсчитываем общую наценку
            endforeach ?>


        </table>
        <? if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')): ?>
            <p align="right">
                <!--Вывод сбоку оуммы в оригинальной валюте И с учётом курса. Статус "В обработке"-->
                <? if (!empty($obrOriginalPrice)): ?><span class="inProcessOriginalPrice-side"> <?= $obrOriginalPrice ?>
                    у.е.</span><br/><? endif; ?>
                <!--                --><? // if (!empty($obr)): ?><!--<span class="process-side"> -->
                <? //= $obr ?><!-- р</span><br/>--><? // endif; ?>
                <span id="side-element-1" class="process-side"> <?= $obr ?> р</span><br/>
                <!--Вывод сбоку оуммы в оригинальной валюте И с учётом курса. Статус "Включено в счёт"-->
                <? if (!empty($incOriginalPrice)): ?><span class="includeOriginalPrice-side"><?= $incOriginalPrice ?>
                    у.е.</span><br/><? endif; ?>
                <!--                --><? // if (!empty($inc)): ?><!--<span class="include-side">-->
                <? //= $inc ?><!-- р</span><br/>--><? // endif; ?>
                <span id="side-element-2" class="include-side"><?= $inc ?> р</span><br/>

                <!--                --><? // if (!empty($den)): ?><!--<span class="deny-side">-->
                <? //= $den ?><!-- р</span><br/>--><? // endif; ?>
                <span id="side-element-3" class="deny-side"><?= $den ?> р</span><br/>
                <!--                --><? // if (!empty($noavil)): ?><!--<span class="noavil-side">-->
                <? //= $noavil ?><!-- р</span><br/>--><? // endif; ?>
                <span id="side-element-4" class="noavil-side"><?= $noavil ?> р</span><br/>

                <!--                --><? //if (!empty($arrived)):?><!--<span class="arrived-side">-->
                <? //=$arrived?><!--</span><br>--><? //endif;?>
                <span id="side-element-5" class="arrived-side"><?= $arrived ?> р.</span><br>

                <a href="/com/org/open/<?= intval($_GET['value']) ?>" class="backToOpen-side">Вернуться к закупке</a>

                Общая сумма заказа: <?= $totalp ?> р.<br/>
                Орг процент: <?= round($totalp / 100 * $openzakup[0]['proc'], 2) ?> р.<br/>
                Наценка: <?= $summExtraCharge ?> р.<br/><!-- Выводим общую наценку -->
                Сумма заказа + оргпроцент: <?= round($totalp + $totalp / 100 * $openzakup[0]['proc'], 2) ?> р.<br/>
                Доставка: <?= $totaldost ?> р.<br/>
                Итого: <?= round($totalp + $totalp / 100 * $openzakup[0]['proc'] + $totaldost + $summExtraCharge, 2) ?>
                р.   <!-- Добавляем наценку и выводим общую сумму -->
            </p>
        <? endif; ?>

        <div class="line3"></div>
        <p><a href="/com/org/open/<?= intval($_GET['value']) ?>" class="btn">Вернуться к закупке</a></p>
    </div>
<? else: ?>

    <h1>Пока заказов нет</h1>
    <p><a href="/" class="link4">На главную.</a></p>

<? endif ?>


    <script type="text/javascript">

        $(document).ready(function () {
            $(".selmod").change(function () {
                var rel = $(this).attr("rel");
                var id = $("select[name='moderate" + rel + "'] option:selected").val();
                $("#item" + rel).removeClass("is_new");
                $("#item" + rel).removeClass("is_no");
                $("#item" + rel).removeClass("is_deny");
                $("#item" + rel).removeClass("is_yes");
                $("#item" + rel).removeClass("is_accept");
                $("#item" + rel).removeClass("is_arrived");
                if (id == 0 || id == 6) var clss = "is_new";
                if (id == 1 || id == 3 || id == 4 || id == 5) var clss = "is_yes";
                if (id == 2) var clss = "is_deny";
                if (id == 7) var clss = "is_no";
                if (id == 8) var clss = "is_accept";
                if (id == 9) var clss = "is_arrived";
                $("#item" + rel).addClass(clss);

                $.post("/index.php?component=org&section=ajax", {
                    sect: "<?=$_GET['section']?>",
                    value: id,
                    rel: rel,
                    key: "<?=$registry['license']?>",
                    id_zp:<?=$openzakup[0]['id']?>,
                    event: "changemoder"
                }, function (j_data) {
                    var new_data = $.parseJSON(j_data);
                    var data1 = new_data[0];
                    var data2 = new_data[1];
                    var data3 = new_data[2];
                    var data4 = new_data[3];
                    var data5 = new_data[4];

                    var count1 = new_data[5];
                    var count2 = new_data[6];
                    var count3 = new_data[7];
                    var count4 = new_data[8];
                    var count5 = new_data[9];

                    $('#side-element-1').html(data1 + "р. / " + count1);
                    $('#side-element-2').html(data2 + "р. / " + count2);
                    $('#side-element-3').html(data3 + "р. / " + count3);
                    $('#side-element-4').html(data4 + "р. / " + count4);
                    $('#side-element-5').html(data5 + "р. / " + count5);
                });
            });
        });
    </script>
<? endif ?>

<? $clear = urldecode() ?>

<script src="/<?= $theme ?>js/sweetalert2/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" type="text/css" href="/<?= $theme ?>js/sweetalert2/dist/sweetalert2.css">
<script type="text/javascript">
    function confirm_delete_ryad(ln) {
        var link = ln.href; // Получаем значение тега hrefvar text = " Удалить?";
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

