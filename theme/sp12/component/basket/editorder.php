<? defined('_JEXEC') or die('Restricted access'); ?>
<? if ($user->get_property('userID') > 0): ?>
    <? if (count($items) > 0): ?>
        <? if (empty($message) and empty($oke)): ?>

            <!-- Load jQuery -->
            <script type="text/javascript" src="http://www.google.com/jsapi"></script>
            <script type="text/javascript">
                google.load("jquery", "1");
            </script>

            <script
                    src="/<?= $theme ?>js/tinymce4/tinymce.min.js">
            </script>
            <script>

                tinymce.init
                ({
                    selector: "#textarea1", language: "ru",
                    font_formats: "Andale Mono=andale mono,times;" +
                    "Arial=arial,helvetica,sans-serif;" +
                    "Arial Black=arial black,avant garde;" +
                    "Book Antiqua=book antiqua,palatino;" +
                    "Comic Sans MS=comic sans ms,sans-serif;" +
                    "Courier New=courier new,courier;" +
                    "Georgia=georgia,palatino;" +
                    "Helvetica=helvetica;" +
                    "Impact=impact,chicago;" +
                    "Symbol=symbol;" +
                    "Tahoma=tahoma,arial,helvetica,sans-serif;" +
                    "Terminal=terminal,monaco;" +
                    "Times New Roman=times new roman,times;" +
                    "Trebuchet MS=trebuchet ms,geneva;" +
                    "Verdana=verdana,geneva;" +
                    "Webdings=webdings;" +
                    "Wingdings=wingdings,zapf dingbats",
                    fontsize_formats: "8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt",
                    plugins: "autolink autoresize smileys textcolor link image jbimages lists charmap pagebreak preview placeholder code",
                    toolbar: "undo redo | forecolor backcolor smileys link image pagebreak |  | fontselect | fontsizeselect",
                    contextmenu: "link image insertable | cell row column deletable",
                    pagebreak_separator: "<!-- my page break -->",
                    image_advtab: true,
                    relative_urls: false, remove_script_host: true,
                });
            </script>

            <!-- todo вытаскиваем тип закупки из БД -->
            <? $sql = "SELECT `sp_zakup`.`type` FROM sp_zakup WHERE `sp_zakup`.`id` = " . $items[0]['id_zp']; ?>
            <? $zakType = $DB->getAll($sql) ?>

            <div class="menu-top5">Редактирование заказа</div>
            <div class="menu-body5">
                <div style="display:block" class="message"><?= $message; ?></div>
                <form action="" method="post">
                    <input type="hidden" name="action" value="editorder">
                    <input type="hidden" name="idd" value="<?= intval($_GET['value']); ?>">
                    <table summary="" style="line-height: 1.8;">
                        <tr>
                            <td valign="top" width="100"><b>Название</b></td>
                            <td><input type="text" class="inputbox" style="width:400px;" name="title"
                                       value="<?= $items[0]['title']; ?>"
                                    <? if ($zakType[0]['type'] == 2): ?> readonly <? endif; ?>/>
                                <? if ($zakType[0]['type'] == 2): ?>
                                <img src="/theme/sp12/images/Closed.png"
                                     style="width: 18px; height: 18px; margin-bottom: -7px;"/>
                                <p>Название товара из закупки с каталогом! Редактирование невозможно!<? endif; ?>
                                    <!--todo   блокируем поле, если закупка рядами (type=2), оставляем, если классическая (type=0)-->
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">Артикул</td>
                            <td><input type="text" class="inputbox" style="width:200px;" name="articul"
                                       value="<?= $items[0]['articul']; ?>"
                                    <? if ($zakType[0]['type'] == 2): ?> readonly <? endif; ?>/>
                                <? if ($zakType[0]['type'] == 2): ?>  <img src="/theme/sp12/images/Closed.png"
                                                                           style="width: 18px; height: 18px; margin-bottom: -7px;"/><? endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><b>Цена</b></td>
                            <td><input type="text" class="inputbox" style="width:200px;" name="price"
                                       value="<?= $items[0]['price']; ?> "
                                    <? if ($zakType[0]['type'] == 2): ?> readonly <? endif; ?>/>
                                <? if ($zakType[0]['type'] == 2): ?>  <img src="/theme/sp12/images/Closed.png"
                                                                           style="width: 18px; height: 18px; margin-bottom: -7px;"/><? endif; ?>
                                р.
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">Размер</td>
                            <td><input type="text" class="inputbox" style="width:200px;" name="size"
                                       value="<?= $items[0]['sizename']; ?>"
                                    <? if ($zakType[0]['type'] == 2): ?> readonly <? endif; ?>/>
                                <? if ($zakType[0]['type'] == 2): ?>  <img src="/theme/sp12/images/Closed.png"
                                                                           style="width: 18px; height: 18px; margin-bottom: -7px;"/><? endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">Цвет</td>
                            <td><input type="text" class="inputbox" style="width:200px;" name="color"
                                       value="<?= $items[0]['color']; ?>"
                                    <? if ($zakType[0]['type'] == 2): ?> readonly <? endif; ?>/>
                                <? if ($zakType[0]['type'] == 2): ?>  <img src="/theme/sp12/images/Closed.png"
                                                                           style="width: 18px; height: 18px; margin-bottom: -7px;"/><? endif; ?>
                                <br/>пример: красный
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><b>Количество</b></td>
                            <td><input type="text" class="inputbox" style="width:200px;" name="kolvo"
                                       value="<?= $items[0]['kolvo']; ?>">
                                <br/>пример: 3
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">Дополнительно</td>
                            <td style="width: 600px;">
                                <textarea id="textarea1" class="tinymce"
                                          name="textarea1"><?= $items[0]['message']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input name="isAnonim" type="checkbox" value="1"
                                       <? if ($items[0]['anonim'] == 1): ?>checked<? endif; ?>>Анонимно (Ваше имя в
                                заказе сможет видеть только Организатор данной закупки)
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <table border="0" width="100%">
                        <tr>
                            <td>
                                <a href="/com/basket/?status=1" class="btn" style="margin-left: 105px;">Отмена</a>
                            </td>
                            <td align="right">
                                <input type="submit" class="btn" style="margin-right: 30px; cursor: pointer"
                                       value="Сохранить">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        <? elseif (!empty($message)): ?>
            <div class="menu-top5">Ошибка</div>
            <div class="menu-body5">
                <h1>Ошибка редактирования заказа</h1>

                <div style="display:block" class="message"><?= $message; ?></div>

                <p><a href="/com/basket/?status=3" class="link4">Вернуться в корзину</a></p>

            </div>
        <? elseif (!empty($oke)): ?>
            <div class="menu-top5">Заказ успешно отредактирован.</div>
            <div class="menu-body5">
                <h1>Ваш заказ отредактирован.</h1>

                <p><a href="/com/basket/?status=3" class="btn">Вернуться в корзину</a></p>

                <h1>Информация</h1>
                <ul>
                    <li>Заказ может быть отредактирован до тех пока закупка в статусе "Открыта".</li>
                    <li>Организатор будет уведомлен о изменении заказа.</li>
                </ul>

            </div>
        <? endif ?>

    <? else: ?>
        <div class="menu-top5">Ошибка.</div>
        <div class="menu-body5">
            <h1>Ошибка редактирования заказа.</h1>

            <div style="display:block" class="message"><?= $message; ?></div>

            <p><a href="/com/basket/?status=1" class="link4">Вернуться в корзину</a></p>

            <h1>Возможные причины.</h1>
            <ul>
                <li>Вы пытаетесь редактировать чужой заказ.</li>
                <li>Закупка переведена в статус "Стоп".</li>
                <li>Данного заказа не существует.</li>
            </ul>
        </div>
    <? endif; ?>


<? else: ?>
    <? @include('.access.php'); ?>
<? endif ?>