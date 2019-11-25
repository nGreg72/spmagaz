<? defined('_JEXEC') or die('Restricted access'); ?>
<?// if ($user->get_property('id') == 1 OR $user->get_property('gid') == 25): ?>
<?if ($user->get_property('userID') == 3 OR $user->get_property('userID') == 7):?>
    <div class="message"><?= $message ?></div>
    <h1>Пользователи</h1>

    <style>
        tr:nth-child(2n) {
            background: #f0f0f0;
        }

        tr:nth-child(1) {
            background: #666;
            color: #fff;
        }
    </style>

    <form action="" method="post" style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 4px double gray">
        Логин / ФИО / Почта / Телефон:
        <input type="hidden" name="filter" value="1"/>
        <input id="autocomplit" type="text" class="inputbox" name="realname" value="<?= $_POST['realname']?>"/>
        <input type="submit" style="border-radius:12px" value="ок"/>
    </form>

<!--    Создаю фильтр по группам пользователей (админы, пользователи, чёрный список...)-->
    <div style="font-size: 16px;">
        <a href="index.php?component=users&group=0"> Неподтверждённые </a>
        <a href="index.php?component=users&group=1"> Администраторы </a>
        <a href="index.php?component=users&group=2"> Хранители </a>
        <a href="index.php?component=users&group=3"> Пользователи </a>
        <a href="index.php?component=users&group=5"> Организаторы </a>
        <a href="index.php?component=users&group=7"> Чёрный список </a>
    </div>
    <!-- Верхние кнопки страниц -->
    <div>
        <? if ($total > 1) echo '<p><div class="pagenation" align="center" style="margin-bottom:10px; margin-top:10px;">'
            . $pervpage . $page5left . $page4left . $page3left . $page2left . $page1left . '<span>' . $page . '</span>' . $page1right . $page2right . $page3right . $page4right . $page5right . $nextpage . '</div></p>'; ?>
    </div>

    <? if (count($all) > 0): ?>
        <table style="margin-left: 100px; line-height: 15px; background-color:#C7C7C7;">
            <tr>
                <td width="40">ID</td>
                <td width="220">Никнейм</td>
                <td width="220">Телефон</td>
                <td width="150">Группа</td>
                <td width="60"><span title="Изничтожить целиком и полностью. На корню!!!">Удалить</span></td>
                <td width="60"><span>Закупки </span></td>
                <td width="60"><span title="Отказ по вацапке от общения. / Без ответа.">Отказ </span></td>
            </tr>
            <? foreach ($all as $num): ?>
                <tr <?if ($num['no_answer'] == 1) :?> style="background-color: #a5a4a4" <?endif;?> >
                    <td class="userid">
                        <?= $num['id'] ?>
                    </td>
                    <td><b>
                            <a href="?component=users&section=edit&edit=<?= $num['id'] ?>&page=<?= $num['page'] ?>"
                               class="news-url"><?= $num['username'] ?></a></b>
                    </td>
                    <td>
                        <?=$num['phone']?>
                    </td>
                    <td>
                        <img src="images/<? if ($num['gid'] == 25): ?>useradmin<? else: ?>user<? endif; ?>.png"
                             width="16" height="16" border="0" alt="<?= $num['name'] ?>" title="<?= $num['name'] ?>"
                             style="margin-right:10px;"/><?= $num['name'] ?>
                    </td>
                    <td style="text-align: center;">
                        <!--<a href="?component=users&section=edit&edit=<?= $num['id'] ?>&page=<?= $num['page'] ?>"><img src="images/edit.png" width="16" height="16" border="0" alt="edit" title="редактировать"/></a>-->
                        <? if ($num['id'] != 2): ?><a href="?component=users&delete=<?= $num['id'] ?>&page=<?= $num['page'] ?>"
                            onclick="if (!confirm('Натурально хочите удалить юзера       <?= $num['username'] ?>')) return false;">
                                <img src="images/cross.png" width="16" height="16" border="0" alt="del"
                                     title="удалить"/></a>
                        <? endif ?>
                    </td>
                    <td style="text-align: center">         <!--todo количество закупок у пользователя-->
                        <?$sql = "SELECT `sp_order`.`id`, `sp_order`.`id_zp`, sp_zakup.id, sp_zakup.user FROM `sp_order`
                                    LEFT JOIN sp_zakup ON sp_zakup.id = sp_order.id_zp                                   /*Учитываются только мои закупки*/
                                    where `sp_order`.`user` = '{$num['id']}' AND sp_zakup.user = 3                       /*Учитываются только мои закупки*/
                                    GROUP by `sp_order`.`id_zp` ";
                        $zakup = $DB->getAll($sql);?>
                        <?=count($zakup)?>
                    </td>
                    <td>
                        <input type="checkbox" style="margin-left: 20px;" class="no_answer" rel="<?=$num['id']?>"
                            <?if ($num['no_answer'] == 1) :?> value="0"  checked
                            <?else:?> value="1"
                            <?endif;?>

                        >
                    </td>
                </tr>
            <? endforeach; ?>
        </table>

        <!-- Нижние кнопки страниц -->
        <div>
            <? if ($total > 1) echo '<p><div class="pagenation" align="center" style="margin-bottom:10px; margin-top:10px;">'
                . $pervpage . $page5left . $page4left . $page3left . $page2left . $page1left . '<span>' . $page . '</span>' . $page1right . $page2right . $page3right . $page4right . $page5right . $nextpage . '</div></p>'; ?>
        </div>
        <div style="padding-top: 11px; border-top: 4px double gray;"><p>
                <!--<img src="images/userplus.png" style="margin-right:10px;" width="16" height="16" border="0" alt="add" title="Добавить пользователя"/><a href="?component=users&section=add">Добавить пользователя</a><br/>-->
                <img src="images/anket.png" style="margin-right:10px;" width="16" height="16" border="0" alt="del"
                     title="Настроить анкету"/><a href="?component=profile">Настроить анкету</a>
            </p>
        </div>
    <? else: ?>Пользователи отсутствуют.
    <? endif; ?>
<? else: ?>if ($user->get_property('gID') == 27 OR $user->get_property('gID') == 18) OR get_access($_GET['component'], 'view', false:<? endif; ?>

<script>
    $(document).ready(function () {
        $('.no_answer').change(function () {
            $(this).parent().parent().css('backgroundColor', "#a5a4a4")

            let status = $(this).val();
            let rel = $(this).attr("rel");
            console.log(rel + status)

            $.post('theme/component/users/ajax.php', {id: rel, status: status} )
        })
    })

</script>
