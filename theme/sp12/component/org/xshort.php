<? if (!empty($item_r['photo'])) {
    if (strpos($item_r['photo'], 'ttp://') || strpos($item_r['photo'], 'ttps://')) {
        $img_path = $item_r['photo'];
    } else {
        $split = explode('/', $item_r['photo']);
        $img_path = '/images/' . $split[2] . '/229/190/1/' . $split[3];
    }
} else {

    $images = array();
    preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $item_r['message'], $media);
    unset($data);
    $data = preg_replace('/(img|src)("|\'|="|=\')(.*)/i', "$3", $media[0]);
    foreach ($data as $url) {
        $info = pathinfo($url);
        if (isset($info['extension']) and !strpos($url, '/sp12/js/tiny_mce/plugins/emotions')) {
            if (($info['extension'] == 'jpg') ||
                ($info['extension'] == 'jpeg') ||
                ($info['extension'] == 'gif') ||
                ($info['extension'] == 'png'))
                array_push($images, $url);
        }
    }
    if (count($images) > 0) $img_path = $images[0]; else $img_path = '';

}
if (!$img_path) {
    $filelist = array();
    $path = "fmanager/uploads/zakup/{$item_r['id_zp']}/ryad/{$item_r['id']}/";
    if ($handle = opendir($path)) {
        while ($entry = readdir($handle)) {
            if (is_file($path . $entry)) {
                $filelist[] = $path . $entry;
            }
        }
        closedir($handle);
    }
    if ($filelist[0] != '') {
        $img_path = '/' . $filelist[0];
    } else $img_path = '/' . $theme . 'images/no_photo229x190.png';
}
/*	$query = "SELECT `sp_size`.*,IF(punbb_users.display_name != '', punbb_users.display_name, punbb_users.username) AS username
          FROM `sp_size`
          LEFT JOIN `punbb_users` ON `sp_size`.`user`=`punbb_users`.`id`
          WHERE `id_ryad` = ".$item_r['id']." AND `duble`=1
          ORDER BY LENGTH(`sp_size`.`name`), CONVERT( `sp_size`.`name` , CHAR )";
        $items_size = $DB->getAll($query);
*/
?>

<? $raz = '';
foreach ($itemssize[$item_r['id']] as $item_s): ?>
    <? if (!empty($item_s['name']) && !$raz) {
        $raz .= '' . $item_s['name'];
    } ?>
    <? //if ($a<count($itemssize[$item_r['id']]) and !empty($item_s['name'])) $raz.=', ';?>
<? endforeach ?>

<!--    Общее количество сделанных заказов в ряде    -->
<?
$sql = "SELECT sp_order.kolvo FROM sp_order WHERE sp_order.id_ryad = " .$item_r['id'];
$quantity = $DB->getAll($sql);

 $ordered = 0;
 foreach ($quantity as $qnt):
    $ordered = $ordered + $qnt['kolvo'];
 endforeach;

$compare = $ordered / $item_r['size'];
$remains = ($item_r['size'] * $item_r['duble']) - $ordered;                                                        /*выводим остаток товара в коробке на главную страницу*/
?>

<!--<div --><?// if ($i != 3): ?><!--style="margin:0 10px 10px 0; border: 1px solid red"--><?// endif ?><!-- class="item --><?// if ($i == 3):$i = 0; ?><!--closing--><?// endif ?><!--">-->
<div style="margin:0 10px 10px 0;" class="item <?=$item_r['id'];?>">
    <a href="/com/org/ryad/<?= $_GET['value'] ?>/<?= $item_r['id'] ?>">
        <div>
            <? if (($item_r['size']) > 1 AND ($ordered) != 0) { ?>                        <!-- todo условие для закупок, у которых есть минималка в рядах AND кол-во заказов было больше нуля-->
                <? if (is_int($compare)): ?>
                    <div style="width: 231px; height: 5px; background-color: #ec7c7f;"></div>    <!-- todo если число целое - коробка собрана. Можно добавить цифры, количетсво собранных шоколадок ($ordered)-->
                <? else: ?>
                    <div style="width: 231px; height: 5px; background-color: #63dd51;"></div>
                <? endif; ?>
            <? } ?>
        </div>
        <img src="<?= $img_path ?>"
             border="0"
             style="max-width:229px; max-height:190px;"
             alt="<?= $item_r['title'] ?>"
             title="<?= $item_r['articul'] ?>"/></a>

    <? if (($item_r['tempOff']) == 1) : ?>    <span
            style="font: bold 14px Arial; color: #ff0927;">Временно в отключке</span> <? endif; ?>
    <!--todo Сообщение о временном отключении-->

    <span class="slash"></span>
    <div class="item-cont">
        <a href="/com/org/ryad/<?= $_GET['value'] ?>/<?= $item_r['id'] ?>" class="link3"><?= $item_r['title'] ?></a>    <!--Имена позиций в закупке-->

        <? if ($raz > 1): ?><b>Количество в упаковке</b>: <?= $raz ?><br/>
            <? if ($remains ==! 0 ): ?><b>Осталось набрать</b>: <?= $remains ?>
                <?if ($user->get_property('gid') == 25) :?>
                    / <?=$remains * $item_r['price']?>р         <!--сумма за недостающий товар-->
                <?endif;?>
                <?else:?> <b style="color: red">Ряд набран</b>
            <? endif ?>
        <? endif ?>

        <br/><br/>

        <div class="pricesh"><strong>Цена:</strong> <?= round($item_r['price'] * $openzakup[0]['curs'], 1) ?>
            руб.<strong>
                (+орг<?= $openzakup[0]['proc'] ?>%
                = <?= round(($item_r['price'] * $openzakup[0]['curs']) + ($item_r['price'] * $openzakup[0]['curs'] / 100 * $openzakup[0]['proc']), 1); ?>
                руб)
            </strong></div>
    </div>
    <?if ($user->get_property('gid') == 25 OR $openzakup[0]['user'] == $user->get_property('userID')):?>  <!--todo выбор tempOff виден только огам и админам-->
    <div>
        <form action="tempOffStatus" method="post">
            <select class="tempOffChangeStatus" rel="<?=$item_r['id'];?>" style="position: absolute; bottom: 45px;">
                <option value="0" <? if($item_r['tempOff'] == 0): ?> selected <?endif;?>> есть в наличии </option>
                <option value="1" <? if($item_r['tempOff'] == 1): ?> selected <?endif;?>> нет в наличии </option>
            </select>
        </form>
    </div>
    <?endif;?>
</div>
