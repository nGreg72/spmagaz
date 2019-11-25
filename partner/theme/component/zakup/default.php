<? if ($user->get_property('userID') == 3 OR $user->get_property('userID') == 7): ?>
    <h1><big>Управление закупками</big></h1>
    <?
    $query = "SELECT count(id) FROM `sp_url_ckeck`";
    $colvo = $DB->getOne($query);
    $query = "SELECT count(`sp_zakup`.`id`) FROM `sp_zakup` 
		  LEFT JOIN `sp_url_ckeck` ON `sp_zakup`.`id_check`=`sp_url_ckeck`.`id`
		WHERE `sp_zakup`.`status`=3";
    $colvo2 = $DB->getOne($query);
    ?>

    <a href="?component=org&section=viewcheck&id='.$item['id_city'].'&city=1"><? echo $colvo; ?></a>
    <a href="?component=org&section=viewopen&id='.$item['id_city'].'&city=1"><? echo $colvo2; ?></a>


    <style>
        .filtr-all {
            font-size: large;
            background-color: rgb(230, 230, 230);
            border-radius: 3px;
            padding: 3px;
            line-height: 30px;
        }

        .filtr {
            background-color: rgb(225, 225, 225);
            border-radius: 3px;
            padding: 3px;
            line-height: 30px;
        }
    </style>

    <div id="sort" style="border-bottom: 4px double gray"><b>Фильтр:</b>
        <a href="index.php?component=zakup" <? if (empty($_GET['status']) and empty($_GET['check'])): ?><? endif ?>
           class="filtr-all"> <big>Все</big> </a>
        <div>
            <? foreach ($statuslist as $it): ?>
                <a href="index.php?component=zakup&status=<?= $it['id'] ?>" <? if ($it['id'] == $_GET['status']): ?><? endif ?>
                   class="filtr"><?= $it['name']; ?> </a>
            <? endforeach ?>
        </div>
    </div>

    <!-- Search of purchase -->
    <form action="" method="post" style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 4px double gray">
        Поиск закупки:
        <input type="hidden" name="filter" value="1"/>
        <input type="text" class="inputbox" name="search" value=""/>
        <input type="submit" style="border-radius:12px" value="ок"/>
    </form>

    <!-- Верхние кнопки страниц -->
    <div><? if ($total > 1) echo '<p><div class="pagenation" align="center" style="margin-bottom:10px; margin-top:10px;">'
            . $pervpage . $page2left . $page1left . '<span>' . $page . '</span>' . $page1right . $page2right
            . $nextpage . '</div></p>'; ?>
    </div>

    <table style="background-color: grey;">
        <tr>
            <td>Закупка</td>
            <td style="width:100px" valign="top">Регион, Город</td>
            <td>Действие</td>
        </tr>

        <? if (count($items) == 0): ?>
            <tr>
                <td>С данным статусом закупок нет</td>
            </tr><? endif ?>

        <? foreach ($items as $item):
            $query = "SELECT IF(punbb_users.display_name != '', punbb_users.display_name, punbb_users.username) AS username,`cities`.`city_name_ru`,`regions`.`region_name_ru` 
		FROM `punbb_users` LEFT
		JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
		JOIN `regions` ON `cities`.`id_region`= `regions`.`id_region`
		WHERE punbb_users.id='{$item['user']}'";
            $data = $DB->getAll($query);
            if (empty($_GET['check'])) {
                if (!empty($item['foto'])) {
                    $split = explode('/', $item['foto']);
                    $img_path = '/images/' . $split[2] . '/125/100/1/' . $split[3];
                } else $img_path = '/' . $theme . 'images/no_photo125x100.png';
                ?>

                <tr style="background-color: rgb(239, 236, 236); border-bottom: 2px solid grey;">
                    <td width="350">
                        <table border="0">
                            <tr>
                                <td><img src="<?= $img_path ?>" alt=""/></td>
                                <td>
                                    <b><a href="/com/org/open/<?= $item['id'] ?>"><?= $item['title'] ?></a></b><br/><br/>
                                    Минималка: <?= $item['min'] ?><br/>
                                    Орг. проц.: <?= $item['proc'] ?><br/>
                                    Организатор: <a
                                            href="/com/profile/default/<?= $item['user'] ?>"><?= $data[0]['username'] ?></a>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td><?= $data[0]['region_name_ru'] ?>, <?= $data[0]['city_name_ru'] ?></td>


                    <td valign="top" id="zp<?= $item['id'] ?>">
                        <table border="0">
                            <tr>
                                <td valign="top" width="130">
                                    <ul style="margin-left:-10px;">
                                        <? if ($item['status'] == 2): ?>
                                            <li><a href="?component=zakup&section=add&id=<?= $item['id'] ?>"
                                                   class="news_body_a">Принять</a></li>
                                            <li><a href="?component=zakup&section=del&id=<?= $item['id'] ?>"
                                                   class="news_body_a">Удалить</a></li>
                                        <? endif ?>
                                        <li><a href="/com/message/new/<?= $item['user'] ?>" class="news_body_a">Написать
                                                Оргию</a></li>
                                    </ul>
                                <td valign="top">
                                    <form action="?section=default&component=zakup&page=<?= $_GET['page'] ?>&status=<?= $_GET['status'] ?>&check=<?= $_GET['check'] ?>#zp<?= $item['id'] ?>"
                                          method="post">
                                        <input type="hidden" name="zakup" value="<?= $item['id'] ?>"/>
                                        <input type="hidden" name="change" value="1"/>

                                        <div>
                                            <div>Статус<br/>
                                                <select name="status" class="inputbox zakupStatus" rel="<?=$item['id']?>">
                                                    <? foreach ($statuslist as $it): ?>
                                                        <option value="<?= $it['id'] ?>"
                                                                <? if ($it['id'] == $item['status']): ?>selected<? endif ?>><?= $it['name']; ?>
                                                        </option>
                                                    <? endforeach ?>
                                                </select>
                                            </div>

                                            <div style="margin-top: 10px;">Организатор<br/>
                                                <select name="user" class="inputbox">
                                                    <? foreach ($orglist as $it): ?>
                                                        <option value="<?= $it['id'] ?>"
                                                                <? if ($it['id'] == $item['user']): ?>selected<? endif ?>><?= $it['username']; ?>
                                                        </option>
                                                    <? endforeach ?>
                                                </select>
                                            </div>
                                            <div style="margin-top: 10px; margin-left: 177px;"><input type="submit"
                                                 value="Сохранить" class="mini"/>
                                            </div>
                                        </div>
                                        <!--			<a href="?component=zakup&section=del&id=<?= $item['id'] ?>&check=1" class="news_body_a">удалить из закрепленных</a><br/>
			<a href="?component=zakup&section=del&id=<?= $item['id'] ?>&check=2" class="news_body_a">удалить с закупкой</a>-->

                                    </form>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
            <? } else { ?>
                <tr>
                    <td class="tab-td-1" width="500">
                        <b>Организатор:</b> <a
                                href="/com/profile/default/<?= $data[0]['user'] ?>"><?= $data[0]['username'] ?></a><br/><br/>

                        <b>Поставщик:</b> <?= $item['url'] ?><br/>
                        <b>Бренд:</b> <?= $item['brend'] ?><br/>
                        <b>Описание:</b> <?= $item['desc'] ?>
                    </td>
                    <td class="tab-td-1"><?= $data[0]['region_name_ru'] ?>, <?= $data[0]['city_name_ru'] ?></td>
                    <td class="tab-td-1" valign="top">
                        <!--			<a href="?component=zakup&section=del&id=<?= $item['id'] ?>&check=1" class="news_body_a">удалить из закрепленных</a><br/>-->
                        <a href="?component=zakup&section=del&id=<?= $item['id'] ?>&check=2" class="news_body_a">удалить
                            из закрепленных, с закупкой</a>
                    </td>
                </tr>
            <? } ?>
        <? endforeach ?>
    </table>
    <br/>

    <!-- Нижние кнопки страниц -->
    <div><? if ($total > 1) echo '<p>	<div class="pagenation" align="center" style="margin-bottom:10px; margin-top:10px;
								width: 800px; border-bottom: 4px double gray;"">'
            . $pervpage . $page2left . $page1left . '<span>' . $page . '</span>' . $page1right . $page2right
            . $nextpage . '</div></p>'; ?>
    </div>
    <br/>
    <div class="sort">
        <a href="index.php?component=zakup&check=all" <? if (!empty($_GET['check'])): ?>class="selected"<? endif ?>>Смотреть
            список закрепленых закрепленых поставщиков за оргами</a>
    </div>
<? else: ?>if ($user->get_property('gID') == 27 OR $user->get_property('gID') == 18) OR get_access($_GET['component'], 'view', false:
<? endif ?>

<script type="text/javascript">
    $(document).ready(function(){
        $(".zakupStatus").change(function () {
            let rel = $(this).attr("rel");
            let zakupStatus = $(this).val();

            $.post("/partner/theme/component/zakup/ajax.php",{
                    id: rel,
                    newZakupStatus: zakupStatus,
                    event: "changeZakupStatus"
                },
            )
        });
    });
</script>
