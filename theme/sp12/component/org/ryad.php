<? defined('_JEXEC') or die('Restricted access'); ?>

<? if (count($openzakup) > 0):
    $openzakup[0]['russia'] = unserialize($openzakup[0]['russia']); ?>
    <div class="menu-top5"><?= $openzakup[0]['title'] . ' - ' . $openzakup[0]['city_name_ru']; ?></div>
    <div class="menu-body5">
        <div style="display:block" class="message"><?= $message; ?></div>

        <? if ($openzakup[0]['user'] == $user->get_property('userID') OR $user->get_property('gid') >= 23):?>
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
                <a href="/com/org/addr/<?= $_GET['value']; ?>" class="link7 addr">Добавить товар</a>
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
                            <table>
                                <tr>
                                    <td width="250">
                                        <b>Статус</b>:
                                        <? if ($openzakup[0]['user'] == $user->get_property('userID')):?>
                                            <a href="#" onclick="openClose('statuslist');"><img
                                                        src="/<?= $theme ?>images/2downarrow.png" border="0" alt=""
                                                        height="16" width="16" title="изменить статус"></a>
                                            <div id="statuslist">
                                                <b>Изменить статус:</b><br/>
                                                <? if ($openzakup[0]['status'] == 0 or $openzakup[0]['status'] == 1) { ?>
                                                    <a href="/com/org/status/<?= intval($_GET['value']) ?>/1"><?= $statuslist[0]['name'] ?></a><? } ?>
                                                <? if ($openzakup[0]['status'] <= 2) { ?><a href="/com/org/status/<?= intval($_GET['value']) ?>/2"><?= $statuslist[1]['name'] ?></a><? } ?>
                                                <? if ($openzakup[0]['status'] == 3) { ?><a href="/com/org/status/<?= intval($_GET['value']) ?>/3"><?= $statuslist[2]['name'] ?></a><? } ?>
                                                <? if ($openzakup[0]['status'] >= 3 AND $openzakup[0]['status'] <= 5) { ?>
                                                    <a href="/com/org/status/<?= intval($_GET['value']) ?>/4"><?= $statuslist[3]['name'] ?></a><? } ?>
                                                <? if ($openzakup[0]['status'] >= 4 AND $openzakup[0]['status'] <= 5) { ?>
                                                    <a href="/com/org/status/<?= intval($_GET['value']) ?>/5"><?= $statuslist[4]['name'] ?></a><? } ?>
                                                <? if ($openzakup[0]['status'] == 4 OR $openzakup[0]['status'] == 6) {
                                                    ?><a href="/com/org/status/<?= intval($_GET['value']) ?>/6"><?= $statuslist[5]['name'] ?></a><? } ?>
                                                <? if ($openzakup[0]['status'] == 6 OR $openzakup[0]['status'] == 7) {
                                                    ?><a href="/com/org/status/<?= intval($_GET['value']) ?>/7"><?= $statuslist[6]['name'] ?></a><? } ?>
                                                <? if ($openzakup[0]['status'] == 7 OR $openzakup[0]['status'] == 8) {
                                                    ?><a href="/com/org/status/<?= intval($_GET['value']) ?>/8"><?= $statuslist[7]['name'] ?></a><? } ?>
                                                <? if ($openzakup[0]['status'] == 8 OR $openzakup[0]['status'] == 9) {
                                                    ?><a href="/com/org/status/<?= intval($_GET['value']) ?>/9"><?= $statuslist[8]['name'] ?></a><? } ?>
                                            </div>
                                        <? endif ?>
                                        <?= $openzakup[0]['name']; ?>, <?= $total_order_zp; ?> заказов<br/>

                                        <b>Организатор</b>:
                                        <a href="/com/profile/default/<?= $openzakup[0]['user'] ?>" class="link4"><?= $openzakup[0]['username'] ?>
                                        </a><br>

                                        <? if ($openzakup[0]['card']):?><b>Номер карты для оплаты</b>: <?= $openzakup[0]['card'] ?><br>
                                        <? endif ?>

                                        <b>Телефон</b>: <span style="font-size: 15px;"><?= $openzakup[0]['phone'] ?></span><br/>
<!--                                        <b>Город</b>: --><?//= $openzakup[0]['city_name_ru'] ?><!--<br/>-->

                                        <b>Уровень доступа</b>: <?= $openzakup[0]['levname']; ?><br/>
                        </div>
                    </td>
                    <td valign="top">
                        <b>Оргсбор</b>: <?= $openzakup[0]['proc'] ?>%<br/>
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
                        <? endif ?>
                        <? if ($openzakup[0]['stop']):?><b>Дата стопа</b>: <?= $openzakup[0]['stop']; ?><? endif ?>
                    </td>
                </tr>
            </table>
            <b style="color:#F4A422">Информация</b>: <? if (!empty($openzakup[0]['inform'])):?><?= $openzakup[0]['inform']; ?><? else:?> <?= $openzakup[0]['name']; ?><? endif ?>
            <br/>
            <? if ($openzakup[0]['russia'][0] > 0):?>
                &nbsp;<br/>
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
            <? endif; ?>
            </td></tr></table>
        </div>

        <div class="text-post">
            <h2><big>Описание закупки:</big></h2>
            <a href="javascript://" onclick="$('.details').slideToggle('slow');">
                <input type="button" style="width:150px; font:bold 14px tahoma; color:blue; border-radius:5px;"
                       value="Нажми меня"></input></a>

            <div class="line3"></div>
            <div style="padding-top: 20px" class="details">
                <? echo
                str_replace("\"https://", '"http://' . $_SERVER['HTTP_HOST'] . '/redirect.php?ssh=1&url=',
                    str_replace("\"http://", //"(<a.*?href=\"?'?)([^ \"'>]+)(\"?'?.*? >)
                        '"http://' . $_SERVER['HTTP_HOST'] . '/redirect.php?url=',
                        $openzakup[0]['text'])); ?>
            </div>
        </div>


        <?
        if (floatval($openzakup[0]['curs']) == 0) $openzakup[0]['curs'] = 1;
        /*if(count($allsize)>0):?>
            <span class="newstitle2">Уже заказали:</span>
            <div align="right">
            <?if ($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID')):?>
                <a href="/com/org/vieworder/<?=$openzakup[0]['id']?>" class="link4 tbotd" style="font:bold 14px arial">Модерировать заказы</a>
                <?endif?>
                </div>
            <table class="tab_order" width="100%"><tr>
            <tr class="tab_order_name"><td width="120">Пользователь</td><td>Название</td>
            <td width="40">Кол-во</td>
            <td width="50">
                <span title="цена товара без орг процента" class="green">Цена заказа</span>
            </td>
            <?if($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID')):?>
            <td width="50">
                <span title="цена товара с орг процентом">Цена+Орг</span>
            </td>
              <?if($openzakup[0]['dost']>0 and $openzakup[0]['status']>=3):?>
                <td width="50">
                    <span title="Доставка">Доставка</span>
                </td>
              <?endif;?>
            <td width="50">
                <span title="Цена + Орг + Доставка" class="blue">Итого</span>
            </td>
            <?endif;?>

            </tr>
              <?if($openzakup[0]['dost']>0 and $openzakup[0]['status']>=3):
                  $query = "SELECT `sp_ryad`.`price`,`sp_order`.`kolvo`,`sp_order`.`status`
                    FROM `sp_order`
                    LEFT JOIN `sp_ryad` ON `sp_order`.`id_ryad`=`sp_ryad`.`id`
                    WHERE `sp_order`.`id_zp` = '{$openzakup[0]['id']}' and `sp_order`.`status` != '2' and `sp_order`.`status` != '7'
                    and `sp_ryad`.`rnone` != '1'";
                  $tp=$DB->getAll($query);
                $totalprice=0;
                foreach($tp as $t){
                //if($t['status']==2)continue;
                $totalprice=$totalprice+($t['kolvo']*$t['price']);
                }


               endif;?>

            <?foreach ($allsize as $itm):?>
                <?
                $timee=explode(':',$itm[1]);
                $datee=explode('.',$timee[0]);
                $datee=$datee[2].'/'.$datee[1];
                $timee=explode('.',$timee[1]);
                $timee=$timee[0].' ч, '.$timee[1].' мин.';
                if($itm[9]==2)continue;
                if ($itm[3]==0 OR $user->get_property('gid')==25 OR $itm[4]==$user->get_property('userID') OR $openzakup[0]['user']==$user->get_property('userID'))
                $linku='<a href="/com/profile/default/'.$itm[4].'" class="link4">'.$itm[5].'</a>'; else $linku='Аноним';
                ?>

                <tr class="<?if(($itm[9]==1||$itm[9]==3||$itm[9]==4||$itm[9]==5) and $itm[0]['rnone']==0):?>):?>is_yes<?endif?><?if($itm[9]==2||$itm[9]==7||$itm[0]['rnone']==1):?>is_no<?endif?>">
                <td width="120" class="tab_order_date td1"><?=$linku?></a>
                    <br/><span>дата: <?=$datee?></span>
                    <?if ($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID')):?>
                        <br/><a class="news_body_a4" href="/com/org/delrz/<?=$itm[8];?>/" onclick="if (!confirm('Вы подтверждаете удаление заказа?')) return false;">удалить</a>
                    <?endif?>
                    </td><td class="td1">
                    <?=$itm[0]['title']?><br/>

                    <?if(!empty($itm[0]['articul'])):?><b>Артикул:</b> <?=$itm[0]['articul']?><?endif;?><br/>
                    <?if(!empty($itm[6])):?><b>Размер:</b> <?=$itm[6]?><?endif;?><br/>
                    <?if(!empty($itm[7])):?><b>Цвет:</b> <?=$itm[7]?><?endif;?>
                    <?if(!empty($itm[10])&&($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID'))):?><b>Доп.инфо:</b> <?=$itm[10]?><?endif;?><br/>

                Статус заказа:
                <?if($itm[0]['rnone']==0):?>
                 <?if($itm[9]==0):?>Новый
                 <?elseif($itm[9]==1):?>Включен в счет
                 <?elseif($itm[9]==2):?>Отказано
                 <?elseif($itm[9]==3):?>Не оплачен
                 <?elseif($itm[9]==4):?>Оплачен
                 <?elseif($itm[9]==5):?>Раздача
                 <?elseif($itm[9]==6):?>Архив
                 <?elseif($itm[9]==7):?>Нет в наличии
                 <?endif?>
                <?else:?>Нет в наличии<?endif?>


                    </td>
                    <td class="td1"><?=$itm[2]?> шт.</td>

                    <td class="td1"><span title="цена товара без орг процента" class="green"><?=$itm[2]*$itm[0]['price']*$openzakup[0]['curs']?>р</span> </td>

                    <?if($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID')):?>
                    <td class="td1"><span title="цена товара с орг процентом" class="blue"><?=($itm[0]['price']*$openzakup[0]['curs']*$itm[2])+round(($itm[0]['price']*$itm[2]*$openzakup[0]['curs'])/100*$openzakup[0]['proc'])?>р</span> </td>
                      <?if($openzakup[0]['dost']>0 and $openzakup[0]['status']>=3):?>
                        <td class="td1">
                          <span title="Доставка"><?=$userdost=round(($openzakup[0]['dost']/100)*(($itm[0]['price']*$openzakup[0]['curs']*$itm[2])/($totalprice/100)),1);?>р</span>
                        </td>
                      <?endif;?>
                    <td class="td1">
                        <span title="общая цена с учетом оргпроцента"><?=($itm[0]['price']*$openzakup[0]['curs']*$itm[2])+round(($itm[0]['price']*$itm[2]*$openzakup[0]['curs'])/100*$openzakup[0]['proc'])+$userdost?>р</span>
                    </td>
                    <?endif;?>

                    </tr>
            <?if($itm[9]!=2 and $itm[9]!=7 and $itm[0]['rnone']!=1)$totalp=$totalp+($itm[0]['price']*$openzakup[0]['curs']*$itm[2]);endforeach?>
            </table>
            <?if($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID')):?>
              <p align="right">
               Общая сумма заказа: <?=$totalp?> р.<br/>
               Сумма заказа с учетом оргпроцента: <?=$totalp+round($totalp/100*$openzakup[0]['proc'])?> р.
              </p>
            <?endif;?>
            <div class="line3"></div>
          <?endif*/
        ?>


        <?
        $query = "SELECT * FROM `sp_ryad` WHERE `id` = " . intval($_GET['value2']) . " AND `spec`='1' ORDER BY id ASC";
        $items_ryad = $DB->getAll($query);
        if ($openzakup[0]['curs'] == 0) $openzakup[0]['curs'] = 1;
        if (count($items_ryad) > 0):
            foreach ($items_ryad as $item_r):
                if (!empty($item_r['photo'])) {
                    if (strpos($item_r['photo'], 'ttp://') || strpos($item_r['photo'], 'ttps://')) {
                        $img_path = $item_r['photo'];
                    } else {
                        $split = explode('/', $item_r['photo']);
                        $img_path = '/images/' . $split[2] . '/400/400/1/' . $split[3];
                        $img_path2 = '/images/' . $split[2] . '/0/0/1/' . $split[3];
                    }
                } else {
                    $img_path = '';
                }
                $filelist = array();
                $filelist2 = array();
                $path = "fmanager/uploads/zakup/{$item_r['id_zp']}/ryad/{$item_r['id']}/";
                if ($handle = opendir($path)) {
                    while ($entry = readdir($handle)) {
                        if (is_file($path . $entry)) {
                            $filelist[] = $path . $entry;
                            $filelist2[] = $path . 'thumbnail/' . $entry;
                        }
                    }
                    closedir($handle);
                }
                if ($img_path == '' and $filelist[0] != '') {
                    $img_path = $img_path2 = '/' . $filelist[0];
                }
                ?>


                <span class="ryadtitle" id="ryad <?= $item_r['id'] ?> ">
	<b>Название</b>: <big><b><? echo $item_r['title']; ?></b></big> <? if ($item_r['rnone'] == 1):?><span>(Нет в наличии)</span><? endif ?></span>

                <? if ($img_path):?>
                <div align="center"> <a href="<?= $img_path2 ?>" rel="lightbox"><img src="<?= $img_path ?>"
                                                                                     border="0" height="400"
                                                                                     alt="<?= $item_r['title'] ?>"
                                                                                     title="<?= $item_r['title'] ?>"
                                                                                     style="margin:10px 0"/></a>
                </div><? endif ?>

                <? if (count($filelist) > 0):?>
                <div>
                <? $i = 0;
                foreach ($filelist as $file): $i++;
                    if ($i == 1 and $img_path == '') continue; ?>
                    <a href="/<?= $file ?>" rel="lightbox"><img src="/<?= $filelist2[$i - 1] ?>"
                                                                border="0" alt="<?= $item_r['title'] ?>"
                                                                title="<?= $item_r['title'] ?>"
                                                                style="border:1px solid #eee;margin:0 10px 5px 0;"/></a>
                <? endforeach ?>
                </div>
            <? endif ?>

                <div class="ryadtext"><?
                    echo str_replace("\"http://", //"(<a.*?href=\"?'?)([^ \"'>]+)(\"?'?.*? >)
                        '"http://' . $_SERVER['HTTP_HOST'] . '/redirect.php?url=',
                        $item_r['message']); ?>
                </div>

                <div class="line2"></div>

                <table>
                    <tbody>
                    <tr>
                        <td class="info-title">Артикул:</td>
                        <td style="font: bold 16px Arial;"><? echo $item_r['articul']; ?></td>
                    </tr>

                    <tr>
                        <td class="info-title">Цена:</td>
<!--                        <td style="font: bold 16px Arial;">--><?// echo $item_r['price'] * $openzakup[0]['curs']; ?><!-- руб.</td>-->
                        <td style="font: bold 16px Arial;">
                            <?= round(($item_r['price'] * $openzakup[0]['curs']) + ($item_r['price'] * $openzakup[0]['curs'] / 100 * $openzakup[0]['proc']), 1); ?>
                            руб.</td>
                    </tr>
                    </tbody>
                </table>
                <? for ($count_duble = 1; $count_duble <= $item_r['duble']; $count_duble++):?>
                <?
                $query = "SELECT `sp_size`.*,
				IF(punbb_users.display_name != '', punbb_users.display_name, punbb_users.username) AS username

			  FROM `sp_size` 
			  LEFT JOIN `punbb_users` ON `sp_size`.`user`=`punbb_users`.`id`
			  WHERE `id_ryad` = " . $item_r['id'] . " AND `duble`=$count_duble 
			  ORDER BY LENGTH(`sp_size`.`name`), CONVERT( `sp_size`.`name` , CHAR )";
                $items_size = $DB->getAll($query); ?>

                <? $a = 0;
                foreach ($items_size as $item_s):?>
                    <? if (!empty($item_s['name'])) $a++; ?>
                <? endforeach ?>

                &nbsp;<br/>

                <!--    Общее количество заказов в ряде    -->
                <? $sql = "SELECT kolvo FROM sp_order WHERE id_ryad = " . intval($_GET['value2']);
                $quantity = $DB->getAll($sql); ?>

                <? $cycle = 0;
                foreach ($quantity as $qnt):
                    $cycle = $cycle + $qnt['kolvo'];
                endforeach;?>

            <? $remain = $item_s['name'] - $cycle?>

            <?if ($item_r['size'] > 1):?>
                <span class="info-title"><? if ($a > 0):?>Количество в упаковке:<? else:?>Позиции<? endif ?></span>
            <?endif;?>

                <table class="table-sizes" summary="">
                    <tbody>
                    <tr class="sizes">
                        <? if (!empty($item_s['name']) AND $item_r['size'] > 1):?>
                            <span style='font: bold 18px arial;color: black;'><?= $item_s['name']?></span>
                        <? endif ?>
                    </tr>
                    <tr class="ryad-grid">

                     <? foreach ($items_size AS $item_s):?>
                            <? if ($item_s['user'] == 0 and $item_r['allblock'] != 1):
                                if (($openzakup[0]['status'] == 3 or $openzakup[0]['status'] == 5) and $user->get_property('gid') != 7):?>
                                    <td>
    <!--                                --><?// if (!empty($item_s['name'])):?><!----><?//= $item_s['name'] ?><!--<br/>--><?// endif ?><!--                Количество позиций в ряде в квадратике "заказать"-->
                                        <a href="/com/org/order/<?= $item_s['id'] ?>" title="добавить заказ">
                                            <img src="/<?= $theme ?>images/cart.png" alt="заказать" border="0" height="30" width="30"/>
                                            <br/>
                                            <small>заказать</small>
                                        </a>
                                    <? elseif ($user->get_property('gid') == 7):?>
                                    <td><span title="Вы в черном списке и не можете делать заказ!">X</span>
                                    </td>
                                  <? endif;

                                  else:
                                     $sql = "SELECT kolvo FROM `sp_order` WHERE `id_order` = '{$item_s['id']}' LIMIT 1";
                                    $kolvo = $DB->getOne($sql);
                               if ($item_s['anonim'] == 0):
                                   for ($ic = 0; $ic < $kolvo; $ic++): if ($kolvo > 1)?>
                                        <td>
                                            <img src="/<?= $theme ?>images/check.png" alt="" border="0" title="заказ принят" height="16" width="16"/><br/>
                                            <!--<a class="link4"><? /*=$item_s['username']*/ ?></a>-->
                                            <a class="link4" title="<?= $item_s['username'] ?>"><?= mb_substr($item_s['username'], 0, 10, 'UTF-8') ?></a>
                                            <!--Имя пользователя в квадратике заказа-->
                                            <!--    <a href="/com/profile/default/<?= $item_s['user'] ?>" class="link4"><?= $item_s['username'] ?></a> -->
                                            <!--Заказавшие пользователи с ссылками на профиль-->
                                        </td>
                                    <? endfor;
                               elseif ( $item_s['anonim'] == 1 AND ($user->get_property('gid') == (25 or 23))):?>
<!--                               анонимных пользователей видят админ и организаторы-->
                                    <td>
                                        <img src="/<?= $theme ?>images/check.png" alt="" border="0" title="заказ принят" height="16" width="16"/><br/>
                                        <a class="link4" title="<?= $item_s['username'] ?>"><?= mb_substr($item_s['username'], 0, 10, 'UTF-8') ?></a>
                                    </td>
                               <?else:?>
                                    <td>
                                        <img src="/<?= $theme ?>images/check.png" alt="" border="0" title="заказ принят" height="16" width="16"/><br/>
                                        Аноним
                                    </td>
                               <? endif;
                            endif;
                        endforeach;?>
                    </tr>
                    </tbody>
                </table>
                                    <? endfor; ?>
                <b>Всего заказано <?= $cycle; ?>
            <?if ($item_r['size'] > 1):?>
                                          из <?= $item_r['size'] * $item_r['duble'] ?></b>
                <br><span class="info-title">Осталось набрать: <?= ($item_s['name'] * $item_r['duble']) - $cycle?> шт.</span>
            <?endif;?>

                <? if ($openzakup[0]['user'] == $user->get_property('userID') OR $user->get_property('gid') == 5):?>
                <p align="right">
                    Ряд: <a class="news_body_a1"
                            href="/com/org/double/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>">дублировать</a>
                    - <a class="news_body_a2"
                         href="/com/org/editr/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>">редактировать</a>
                    - <a class="news_body_a3"
                         href="/com/org/edpos/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>">изменить позиции</a>
                    - <a class="news_body_a5"
                         href="/com/org/rnone/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>">
                        <? if ($item_r['rnone'] == 0):?>нет в наличии<? else:?>есть в наличии<? endif ?>
                    </a>
                    - <a class="news_body_a4" href="/com/org/delr/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>"
                         onclick="if (!confirm('Вы подтверждаете удаление ряда?  --  <?= $item_r['title'] ?>)')) return false;">удалить</a>
                </p>

                <a class="ribbon1" href="/com/org/double/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>"></a>
                <a class="ribbon2" href="/com/org/editr/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>"></a>
                <a class="ribbon3" href="/com/org/edpos/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>"></a>
                <a class="ribbon4" href="/com/org/rnone/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>">
                    <? if ($item_r['rnone'] == 0):?><? else:?><? endif ?></a>
                <a class="ribbon5" href="/com/org/delr/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>"
                   onclick="if (!confirm('Вы подтверждаете удаление ряда?  --  <?= $item_r['title'] ?>)'))
                       return false;"></a>

            <? endif; ?>
                <div class="line3"></div>

            <? endforeach ?>
        <? endif; ?>

        <a href="/com/org/open/<?= $_GET['value'] ?>/" class="link4"><< Вернуться к списку всех товаров</a>

        <? if (($openzakup[0]['status'] == 3 or $openzakup[0]['status'] == 5) and $openzakup[0]['type'] != 2):?>
            <a href="/com/org/addo/<?= $openzakup[0]['id'] ?>" class="addrorder"
               title="Добавить заказ, указав данные из прайса или с сайта поставщика">добавить заказ</a>
            <p>*чтобы добавить в корзину товары не представленные в данном альбоме или заказать свой размер</p>
        <? endif ?>

        <div class="line3"></div>

<!--        --><?// @include('comments.php'); ?>
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
