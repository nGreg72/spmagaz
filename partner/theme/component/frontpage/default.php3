<? defined('_JEXEC') or die('Restricted access'); ?>
<? if ($user->get_property('userID') == 1 OR $user->get_property('gid') >= 22): ?>

    <div class="content-frontpage">
        <table class="tab-frontpage">
            <tr>
                <? if ($user->get_property('gid') == 25): ?>
                <td valign="top"><a href="/forum/admin/index.php"><img src="../img/Site_forum.png"/><br/>Админка форума</a><br/>
                </td>
                <td valign="top"><a href="index.php?component=settings"><img src="../img/Site_settings.png"/><br/>Настройки
                        сайта</a><br/></td>
                <td valign="top"><a href="index.php?component=users"><img src="../img/Site_users.png"/><br/>Пользователи</a><br/>
                </td>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=org"><img
                                src="../img/Site_organizators.png"/><br/>Организаторы</a><br/></td>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=office"><img
                                src="../img/Site_office.png"/><br/>Офисы ЦР</a><br/></td>
                <!--<td valign="top"><a href="http://mysp.shop/partner/index.php"><img src="../img/Site_purchase.png"/><br/>Закупки</a><br/></td>-->
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=zakup"><img
                                src="../img/Site_purchase.png"/><br/>Закупки</a><br/></td>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=report"><img
                                src="../img/Site_reports.png"/><br/>Отчеты</a></td>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=orgorders"><img
                                src="../img/Site_orgorders.png"/><br/>Управление счетами</a></td>
            </tr>
            <tr>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=static"><img
                                src="../img/Site_statistic.png"/><br/>Статистика</a></td>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=article"><img
                                src="../img/Site_articles.png"/>Записи</a><br/></td>
                <!-- <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=blog"><img src="../img/Site_blogs.png"/><br/>Записи в блогах</a> -->
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=concurs"><img
                                src="../img/Site_competitions.png"/><br/>Конкурсы</a></td>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=comment"><img
                                src="../img/Site_comments.png"/><br/>Комментарии</a></td>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=reviews"><img
                                src="../img/Site_reviews.png"/><br/>Отзывы</a></td>
                <!-- <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=votes"><img src="../img/Site_interviews.png"/><br/>Опросы</a> -->
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=mail"><img
                                src="../img/Site_mail.png"/><br/>Рассылки e-mail</a></td>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=mailSMS"><img
                                src="../img/Site_sms.png"/><br/>Рассылки SMS</a></td>
            <tr>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=users&section=history"><img
                                src="../img/Site_history.png"/><br/>История счетов</a></td>
                <td valign="top"><a
                            href="index.php?component=users&section=edit&edit=<?= $user->get_property('userID') ?>&page="><img
                                src="../img/Site_profile.png"/><br/>Мои данные</a><br/></td>
                <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=Files"><img
                                src="../img/Site_mail.png"/><br/>Файлы</a></td>
            </tr>
            <tr>
                <!-- <td valign="top"><a href="http://mysp.shop/partner/index.php" style="float:left;margin:5px 20px;"><img src="../img/Site_admin.png"/><br/>Админская зона</a></a> -->
                <!-- <td valign="top"><a href="../index.php" style="float:left;margin:5px 20px;"><img src="../img/Site_site.png"/>На сайт</a> -->
            </tr>
            <? endif ?>

            <? if ($user->get_property('gid') >= 22 and $user->get_property('gid') < 25): ?>
                <td valign="top"><a href="index.php?component=setup"><img src="../img/profile.png"/><br/>Мои
                        данные</a><br/></td>
                <td valign="top"><a href="index.php?component=article"><img src="../img/article.png"/><br/>Опубликовать
                        новость</a><br/></td>
                <td valign="top"><a href="index.php?logout=1"><img src="../img/exit.png"/><br/>Выход</a><br/></td>
            <? endif; ?>
            </tr></table>
        </td></tr></table>
    </div>
<? else: ?>
    <script language="JavaScript">
        setTimeout('location.replace("/")', 0);
    </script>
    У вас недостаточно прав для доступа к данному разделу...
<? endif ?>