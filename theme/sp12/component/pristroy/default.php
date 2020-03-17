<? defined('_JEXEC') or die('Restricted access'); ?>
<style>
    .filter {
        height: 30px;
        border-bottom: 1px solid #D1D1D1;
    }

    .filter a {
        float: left;
        margin: 0 15px;
        color: #0669AB;
        text-decoration: underline;
    }

    .filter b {
        float: left;
    }

    .filter a:hover {
        text-decoration: none;
    }

    .add-hv a {
        float: right;
        margin: 0 15px;
        color: #0669AB;
        text-decoration: underline;
    }

    .abs {
        position: absolute;
        right: 20px;
        width: 150px;
        height: 30px;
        top: 10px;
    }
</style>
<div class="menu-top5">Пристрой</div>
<div class="menu-body5" style="margin:0">
    <div class="filter">
        <b>Фильтр:</b>
        <a href="/com/pristroy/" <? if (empty($_GET['value'])): ?>style="font:bold 12px arial"<? endif ?>>все</a>
        <? if ($user->get_property('userID') > 0 OR $user->get_property('gid') >= 18): ?>
            <a href="/com/pristroy/default/my"
               <? if ($_GET['value'] == 'my'): ?>style="font:bold 12px arial"<? endif ?>>мои</a>
        <? endif; ?>
        <a href="">участник</a>
        <a href="">название</a>
    </div>
    <? if ($user->get_property('userID') > 0 OR $user->get_property('gid') >= 18): ?>
        <div class="open-navi abs">
            <a href="/com/pristroy/add" class="link7 addr">добавить пристрой</a>
            <!--<a href="/com/org/editzp/<?= $openzakup[0]['id']; ?>" class="link7 editzp">редактировать закупку</a>-->
        </div>
    <? endif; ?>
</div>
<div id="sidebar">
    <? foreach ($cat_zp as $catz): ?>
        <a href="/com/pristroy/default/<?= $catz[0]['id'] ?>"
           <? if ($catz[0]['id'] == $sort_catz_scroll): ?>class="active"<? endif; ?>><?= $catz[0]['name'] ?></a>
    <? endforeach; ?>
</div>
<? if ($sort_catz > 0): ?>
    <div id="sidebar2" <? if (count($cat_zp[$sort_catz_scroll]) > 7): ?>style="height:60px"<? endif ?>>
        <? $i = 0;
        foreach ($cat_zp[$sort_catz_scroll] as $catz):$i++;
            if ($i == 1) continue; ?>
            <a href="/com/pristroy/default/<?= $catz['id'] ?>"
               <? if ($catz['id'] == $sort_catz): ?>class="active"<? endif; ?>><?= $catz['name'] ?></a>
        <? endforeach; ?>
    </div>
<? endif; ?>

<div id="assort" class="assort_740">
    <? if (count($hvastics) > 0): ?>
        <? $i = 0;
        foreach ($hvastics as $zak):$i++;
            if (($zak['off'] != 1) OR ($user->get_property('gid') == 25)):
                $pth = 'img/uploads/pristroy/' . $zak['title'];
                $extension = ['.jpg', '.jpeg', '.gif', '.png'];
                $img_path = '';
                foreach ($extension as $ext) {
                    if (file_exists($pth . $ext)) {
                        $img_path = '/img/uploads/pristroy/' . $zak['title'] . $ext;
                        break;
                    }
                }
                if ($img_path == '') {
                    preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $zak['text'], $media);
                    unset($data);
                    $data = preg_replace('/(img|src)("|\'|="|=\')(.*)/i', "$3", $media[0]);
                    $images = [];
                    foreach ($data as $url) {
                        $info = pathinfo($url);

                        if (isset($info['extension']) and !strpos($url, '/sp12/js/tiny_mce/plugins/emotions')) {
                            if (($info['extension'] == 'jpg') ||
                                ($info['extension'] == 'jpeg') ||
                                ($info['extension'] == 'gif') ||
                                ($info['extension'] == 'png'))
                                $images[] = $url;
                        }
                    }

                    if (count($images) > 0) $img_path = $images[0];
                }
                if ($img_path == '') $img_path = '/' . $theme . 'images/no_photo229x190.png';
                ?>


                <div class="item ">
                    <div style="text-align: center"><a href="/com/pristroy/open/<?= $zak['id'] ?>"><img
                                    src="<?= $img_path ?>" border="0"
                                    height="190" alt="<?= $zak['title'] ?>"
                                    title="<?= $zak['title'] ?>"/></a></div>
                    <span class="slash"></span>
                    <div class="item-cont">
                        <span class="status"><b>Цена: </b><?= $zak['price'] ?> <?= $registry['valut_name'] ?></span>
                        <? if (!empty($zak['size'])): ?><span class="status"><b>Размер: </b><?= $zak['size'] ?>
                            </span><? endif; ?>

                        <div style="padding-top: 16px;"><a href="/com/pristroy/open/<?= $zak['id'] ?>"
                                                           class="link3"><?= utf8_substr($zak['title'], 0, 100) ?>
                                ...</a>
                        </div>
                        <div style="padding-top: 25px;"><?= date('d.m.Y', $zak['date']) ?>
                            <a href="/com/profile/default/<?= $zak['user'] ?>" class="link2"><?= $zak['username'] ?></a>
                        </div>
                        <? if ($user->get_property('gid') == 25): ?>  <!--todo выбор tempOff виден только огам и админам-->
                            <div>
                                <form action="tempOffStatus" method="post">
                                    <select class="tempOffChangeStatus" rel="<?= $zak['id']; ?>"
                                            style="position: absolute; bottom: 5px;">
                                        <option value="0" <? if ($zak['off'] == 0): ?> selected <? endif; ?>> есть в
                                            наличии
                                        </option>
                                        <option value="1" <? if ($zak['off'] == 1): ?> selected <? endif; ?>> нет в
                                            наличии
                                        </option>
                                    </select>
                                </form>
                            </div>
                        <? endif; ?>
                    </div>
                </div>
            <? endif; ?>
        <? endforeach; ?>
    <? else: ?>
        <p>В выбранной вами категории нет товаров</p>
    <? endif; ?>

    <? if ($total > 1) echo '<div class="pagenation" align="center" style="margin-bottom:10px; margin-top:10px;">'
        . $pervpage . $page2left . $page1left . '<span>' . $page . '</span>' . $page1right . $page2right
        . $nextpage . '</div>'; ?>

</div>

<script>
    $(document).ready(function () {
        $(".tempOffChangeStatus").change(function () {

            var rel = $(this).attr("rel");
            var offStatus = $(this).val();

            $.post("/theme/sp12/component/pristroy/ajax.php", {
                id: rel,
                offStatus: offStatus,
                event: "tempOffStatus"
            })
        })
    })
</script>