<? defined('_JEXEC') or die('Resdivicted access'); ?>
<? if ($user->get_property('userID') == 1 OR $user->get_property('gid') >= 22): ?>

                <? if ($user->get_property('gid') == 25): ?>
        <div class="admin-grid">
                    <div><a href="/forum/admin/index.php"><img src="/img/Site_forum.png"/><br>Админка форума</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=settings"><img src="/img/Site_settings.png"/><br>Настройки сайта</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=users"><img src="/img/Site_users.png"/><br>Пользователи</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=org"><img src="/img/Site_organizators.png"/><br>Организаторы</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=office"><img src="/img/Site_office.png"/><br>Офисы ЦР</a></div>
                    <!--<td valign="top"><a href="http://mysp.shop/partner/index.php"><img src="../img/Site_purchase.png"/><br/>Закупки</a><br/></td>-->
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=zakup"><img src="/img/Site_purchase.png"/><br/>Закупки</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=report"><img src="/img/Site_reports.png"/><br/>Отчеты</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=orgorders"><img src="/img/Site_orgorders.png"/><br>Управление счетами</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=static"><img src="/img/Site_statistic.png"/><br>Статистика</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=article"><img src="/img/Site_articles.png"/><br>Записи</a></div>
                    <!-- <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=blog"><img src="../img/Site_blogs.png"/><br/>Записи в блогах</a> -->
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=concurs"><img src="/img/Site_competitions.png"/><br>Конкурсы</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=comment"><img src="/img/Site_comments.png"/><br>Комментарии</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=reviews"><img src="/img/Site_reviews.png"/><br>Отзывы</a></div>
                    <!-- <td valign="top"><a href="<?= $_SERVER['PHP_SELF'] ?>?component=votes"><img src="../img/Site_interviews.png"/><br/>Опросы</a> -->
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=mail"><img src="/img/Site_mail.png"/><br>Рассылки e-mail</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=mailSMS"><img src="/img/Site_sms.png"/><br>Рассылки SMS</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=mailSMS"><img src="/img/Site_sms.png"/><br>Рассылки на сайте</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=users&section=history"><img src="/img/Site_history.png"/><br>История счетов</a></div>
                    <div><a href="/index.php?component=users&section=edit&edit=<?= $user->get_property('userID') ?>&page="><img src="/img/Site_profile.png"/><br/>Мои данные</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=Files"><img src="/img/Site_mail.png"/><br>Файлы</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=clean"><img src="/img/Site_mail.png"/><br>Чистка таблицы <br> sp_size</a></div>
                    <div><a href="<?= $_SERVER['PHP_SELF'] ?>?component=insertsize"><img src="/img/Site_mail.png"/><br>Isert Size</a></div>

                    <!-- <td valign="top"><a href="http://mysp.shop/partner/index.php" style="float:left;margin:5px 20px;"><img src="../img/Site_admin.png"/><br/>Админская зона</a></a> -->
                    <!-- <td valign="top"><a href="../index.php" style="float:left;margin:5px 20px;"><img src="../img/Site_site.png"/>На сайт</a> -->

                    <div ><a href="/index.php?component=setup"><img src="/img/profile.png" style="width: 60px; height: 60px;"/><br>Мои данные</a></div>
                    <div ><a href="/index.php?component=article"><img src="/img/article.png" style="width: 60px; height: 60px;"/><br>Опубликовать новость</a></div>
<!--                    <div ><a href="../../../../cgi-bin/Hello.py"><img src="/img/article.png" style="width: 60px; height: 60px;"/><br>Python test</a></div>-->
                    <div ><a href="/partner/theme/component/python/Hello.py"><img src="/img/article.png" style="width: 60px; height: 60px;"/><br>Python test</a></div>
        </div>
                <? endif; ?>
<? endif ?>