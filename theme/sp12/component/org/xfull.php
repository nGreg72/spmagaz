<span class="ryadtitle">
    <? if ($item_r['position'] > 0 and ($user->get_property('gid') == 25 || $user->get_property('userID') == $item_r['user'])): ?>
        Ряд №<?= $item_r['position']; ?><br/>
    <? endif ?>

    <b>Название</b>:
<!--    --><?// echo $item_r['title']; ?><!-- -->                                                                                       <!--Просто название. Без ссылки-->
    <a href="/com/org/ryad/<?= $_GET['value'] ?>/<?= $item_r['id'] ?>" style="font: bold 19px Arial"><?= $item_r['title'] ?></a>        <!--Название ряда со ссылкой-->
    <? if ($item_r['rnone'] == 1): ?>
        <span>(Нет в наличии)</span>
    <? endif ?>
</span>

<div class="ryadok" id="ryad<?= $item_r['id'] ?>">
    <div class="ryadtext">

        <? if (!empty($item_r['photo'])) {

            if (strpos($item_r['photo'], 'ttp://') || strpos($item_r['photo'], 'ttps://')) {
                $img_path = $item_r['photo'];
            } else {
                $split = explode('/', $item_r['photo']);
                $img_path = '/images/' . $split[2] . '/229/190/1/' . $split[3];
                $img_path2 = '/images/' . $split[2] . '/0/0/1/' . $split[3];
            }
        } else {
            $img_path = $img_path2 = '';
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

        <? if ($img_path2) { ?>
            <a href="<?= $img_path2 ?>" rel="lightbox"><img src="<?= $img_path ?>"
                                                            border="0" width="229" height="190"
                                                            alt="<?= $item_r['title'] ?>"
                                                            title="<?= $item_r['title'] ?>"/></a>
        <? } ?>


        <? if (count($filelist) > 1): ?>
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

        <? echo str_replace("\"http://", //"(<a.*?href=\"?'?)([^ \"'>]+)(\"?'?.*? >)
            '"http://' . $_SERVER['HTTP_HOST'] . '/redirect.php?url=',
            $item_r['message']); ?>
    </div>
</div>

<div class="line2"></div>

<table>
    <tbody>
    <tr>
        <td class="info-title">Артикул:</td>
        <td><? echo $item_r['articul']; ?></td>
    </tr>
    <tr>
        <td class="info-title">Цена:</td>
<!--        <td style="font: bold 16px Arial;">--><?// echo $item_r['price'] * $openzakup[0]['curs']; ?><!-- --><?//= $registry['valut_name'] ?><!-----</td>-->
        <td style="font: bold 16px Arial;">
            <?= round(($item_r['price'] * $openzakup[0]['curs']) + ($item_r['price'] * $openzakup[0]['curs'] / 100 * $openzakup[0]['proc']), 1); ?>
            <?= $registry['valut_name'] ?></td>
    </tr>
    </tbody>
</table>

<? for ($count_duble = 1; $count_duble <= $item_r['duble']; $count_duble++): ?>
    <?
    /*		$query = "SELECT `sp_size`.*,IF(punbb_users.display_name != '', punbb_users.display_name, punbb_users.username) AS username
                  FROM `sp_size`
                  LEFT JOIN `punbb_users` ON `sp_size`.`user`=`punbb_users`.`id`
                  WHERE `id_ryad` = ".$item_r['id']." AND `duble`=$count_duble
                  ORDER BY LENGTH(`sp_size`.`name`), CONVERT( `sp_size`.`name` , CHAR )";
                $items_size = $DB->getAll($query);
    */
    $items_size = $itemssize[$item_r['id']];
    ?>

    <? $a = 0;
    foreach ($items_size as $item_s): ?>
        <? if (!empty($item_s['name'])) $a++; ?>
    <? endforeach ?>

    &nbsp;<br/>
    <? if ($a > 0): ?>
            <span class="info-title">Размеры:</span> <span style="font: bold 20px Arial; color: black;"> <?=$item_r['size']?> </span>
        <? else:?>
            <span class="info-title">Позиции:</span><span style="font: bold 20px Arial; color: black;">тута должно быть позиции заказов </span>
    <? endif ?>

    <table class="table-sizes" summary="">
        <tbody>

<!--Количество позиций в закупке. Не нужно!-->
        <!--<tr class="sizes">
            <?/* $iu = 0;
            foreach ($items_size as $item_s):
            $iu++; */?>

            <?/* if (!empty($item_s['name'])): */?>
                <td><?/*= $item_s['name'] */?>
                </td>
            <?/* endif */?>

            <?/* if ($iu == 12): */?>
            <?/* endif */?>
            <?/* endforeach */?>
        </tr>-->

        <tr class="<? if ($item_r['rnone'] == 1): ?>is_no<? endif ?>">
            <? $i = 0;
    foreach ($items_size as $item_s):
                $i++; ?>

        <? if ($item_s['user'] == 0):
                    if (($openzakup[0]['status'] == 3 or $openzakup[0]['status'] == 5) and $user->get_property('gid') != 27):?>
                        <td>
<!--                        <?/* if (!empty($item_s['name'])):*/?><?/*= $item_s['name'] */?><br/><?/* endif */?>
                        <?/* if ($item_r['rnone'] == 0):*/?>
                            <a href="/com/org/order/<?/*= $item_s['id'] */?>" title="добавить заказ">
                        <?/* endif */?>
                        <img src="/<?/*= $theme */?>images/cart.png" alt="заказать" border="0" height="16" width="16"/>
                        <?/* if ($item_r['rnone'] == 0):*/?>
                            <br/>
                            <small>заказать</small>
                            </a>
                        <?/* endif */?>
                    <?/* elseif ($user->get_property('gid') == 27):*/?>
                        <td><span title="Вы в черном списке и не можете делать заказ!">X</span></td>-->
                    <? endif ?>

                    <? else: ?>

                    <? if ($item_s['anonim'] == 0): ?>
                        <td>
                            <? if (!empty($item_s['name'])): ?><?= $item_s['name'] ?><br/>
                            <? endif ?>
                            <img src="/<?= $theme ?>images/check.png" alt="" border="0" title="заказ принят" height="16"
                                 width="16"/><br/>
                            <a href="/com/profile/default/<?= $item_s['user'] ?>" class="link4">
                                <?= $item_s['username'] ?></a>
                        </td>
                    <?elseif ( $item_s['anonim'] == 1 AND ($user->get_property('gid') == (25 or 23))):?>
                    <!--  анонимных пользователей видят админ и организаторы-->
                        <td>
                            <? if (!empty($item_s['name'])): ?><?= $item_s['name'] ?><br/>
                            <? endif ?>
                            <img src="/<?= $theme ?>images/check.png" alt="" border="0" title="заказ принят" height="16"
                                 width="16"/><br/>
                            <a href="/com/profile/default/<?= $item_s['user'] ?>" class="link4">
                                <?= $item_s['username'] ?></a>
                        </td>
                    <? else: ?>
                        <td>
                            <img src="/<?= $theme ?>images/check.png" alt="" border="0" title="заказ принят" height="16"
                                 width="16"/><br/>
                            Аноним
                        </td>
                    <? endif ?>
                <? endif ?>
            <? if ($i == 9):
            $i = 0; ?>
        <? endif ?>
    <? endforeach; ?>
    </table>
<? endfor; ?>

<br>
<!--                        --><?// if (!empty($item_s['name'])):?><!----><?//= $item_s['name'] ?><!--<br/>--><?// endif ?>
<!--                        --><?// if ($item_r['rnone'] == 0):?>

<div class="make_order_full">
    <a href="/com/org/order/<?= $item_s['id'] ?>" title="добавить заказ" >
        <div style="padding-left: 11px;">
    <!--                        --><?// endif ?>
            <img src="/<?= $theme ?>images/cart.png" alt="заказать" border="0" height="23" width="23"/>
    <!--                        --><?// if ($item_r['rnone'] == 0):?>
        </div>
        <div>
            <span>заказать</span>
        </div>
    </a>
</div>

<!--                        --><?// endif ?>
<!--                    --><?// elseif ($user->get_property('gid') == 27):?>
<!--                            <span title="Вы в черном списке и не можете делать заказ!">X</span>-->
<!--                    --><?// endif ?>

<? if ($openzakup[0]['user'] == $user->get_property('userID') OR $user->get_property('gid') == 25): ?>
    <p align="right">
        Ряд: <a class="news_body_a1" href="/com/org/double/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>">дублировать</a>
        - <a class="news_body_a2" href="/com/org/editr/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>">редактировать</a>
        - <a class="news_body_a3" href="/com/org/edpos/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>">изменить
            позиции</a>
        <!--		- <a class="news_body_a5" href="/com/org/rnone/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>/<? if ($registry['short'] == 0): ?>?short=1<? endif ?>">
		<? if ($item_r['rnone'] == 0): ?>нет в наличии<? else: ?>есть в наличии<? endif ?>
		</a>-->

        - <a class="news_body_a4" href="/com/org/delr/<?= $item_r['id']; ?>/<?= intval($_GET['value']); ?>"
             onclick="if (!confirm('Вы подтверждаете удаление ряда?')) return false;">удалить</a>
    </p>
<? endif; ?>
<div class="line3"></div>
