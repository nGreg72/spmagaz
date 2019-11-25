<? defined('_JEXEC') or die('Restricted access'); ?>
<? if ($user->get_property('gid') >= 23): ?>
    <? if (empty($message)): ?>

        <style>
            .ok-button {
                cursor: pointer;
                width: 110px;
                border-radius: 12px;
            }

            .cancel-button {
                cursor: pointer;
                background-color: rgb(237, 231, 231);
                padding: 3px 10px 3px 10px;
                border: 2px outset grey;
                border-radius: 12px;
            }
        </style>

        <script
                src="/<?= $theme ?>js/tinymce4/tinymce.min.js">
        </script>
        <script>
            tinymce.init({
                selector: "#textarea1", language: "ru",
                font_formats:
                "Andale Mono=andale mono,times;" +
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
                plugins: "smileys autolink autoresize textcolor link image media jbimages lists table charmap pagebreak preview code",
                toolbar: "undo redo | forecolor backcolor | smileys | link image media pagebreak | | fontselect | fontsizeselect",
                contextmenu: "link image media insertable|| cell row column deletable",
                pagebreak_separator: "<!-- my page break -->",
                image_advtab: true,
                relative_urls: false, remove_script_host: true,
            });

        </script>


        <div class="menu-top5">Организаторская: Редактировать заказ</div>
        <div class="menu-body5">
            <form action="" method="post" enctype="multipart/form-data" id="sendeform" name="sendeform">
                <input type="hidden" name="action" value="edito">
                <input type="hidden" name="idpost" value="<?= intval($_GET['value']) ?>">
                <table summary="">
                    <tr>
                        <td valign="top" width="100"><b>Название</b></td>
                        <td><input type="text" class="inputbox" style="width:400px;" name="title"
                                   value="<?= str_replace('"', '\'', $items[0]['title']); ?>" disabled>
                            <br/>Именно это будет в корзине у участника. Пишите так чтобы ему было понятно!
                        </td>
                    </tr>
                    <!--<tr>
                        <td valign="top">Артикул</td>
                        <td><input type="text" class="inputbox" style="width:400px;" name="articul"
                                   value="<?/*= $items[0]['articul']; */?>" disabled>
                        </td>
                    </tr>-->
                    <!--<tr>
                        <td valign="top"><b>Описание товара</b></td>
                        <td>
                            <textarea disabled><?/*= strip_tags($items[0]['message']) */?></textarea>
                        </td>
                    </tr>-->
                    <!--<tr>
                        <td class="title option"><b>Цена</b></td>
                        <td><input type="text" class="inputbox" name="price" value="<?/*= $items[0]['price']; */?>"
                                   disabled> р.
                        </td>
                    </tr>-->
                    <!--<tr>
                        <td valign="top"><b>Размеры или количество</b></td>
                        <td><input type="text" class="inputbox" style="width:100px;" name="size"
                                   value="<?/*= $items[0]['size']; */?>" disabled>
                            <a href="/com/org/edpos/<?/*= $_GET['value'] */?>/<?/*= $_GET['value2'] */?>" class="link4">редактировать</a><br/>
                            Пример размеров: 40,41,41,42<br/>
                            Пример количества: 3
                        </td>
                    </tr>-->
                    <!--<tr>
                        <td class="title option">Категория</td>
                        <td><input type="text" class="inputbox" name="cat" value="<?/*= $items[0]['cat']; */?>" disabled
                                   title="разбивка товаров на категории внутри закупки"> <br/>(внутри закупки будет
                            создана данная категория, для товаров)
                        </td>
                    </tr>-->


                    <tr>
                        <td class="title option"><b>Заказано кол-во</b></td>
                        <td><input type="text" class="inputbox" name="kolvo" value="<?= $order[0]['kolvo']; ?>"></td>
                    </tr>

                    <tr>
                        <td valign="top"><b>Комментарий пользователя к заказу</b></td>
                        <td>
                            <textarea name="message" class="tinymce" id="textarea1"
                                      readonly><?= $order[0]['message'] ?></textarea>
                        </td>
                    </tr>


                </table>
                <br/>

            </form>

            <table border="0" width="100%">
                <tr class="last">
                    <td>
                        <a class="cancel-button" href="/com/org/open/<?= intval($_GET['value2']) ?>">Отмена</a>
                    </td>
                    <td align="right">
                        <input type="submit" class="ok-button" value="Сохраница" onclick="document.sendeform.submit()">
                    </td>
                </tr>
            </table>

        </div>
    <? else: ?>
        <div class="menu-top5">Ошибка</div>
        <div class="menu-body5">
            <h1>Вы не можете редактировать данную закупку (рядок)</h1>
            <div style="display:block" class="message"><?= $message; ?></div>
            <p><a href="/" class="link4">...На главную.</a></p>
            <p>Возможные причины ошибки:</p>
            <ul>
                <li>Время сессии авторизации истекло</li>
                <li>Вы пытаетесь попасть в раздел только для зарегистрированных пользователей</li>
                <li>Вы пытаетесь редактировать чужую закупку</li>
                <li>Такой закупки не существует</li>
            </ul>
        </div>
    <? endif ?>

<? else: ?>
    <? @include('.access.php'); ?>
<? endif ?>