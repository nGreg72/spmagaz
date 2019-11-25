<? defined('_JEXEC') or die('Restricted access'); ?>
<? if ($user->get_property('gid') >= 23): ?>

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

    <div class="menu-top5">Организаторская: Создать закупку</div>
    <div class="menu-body5">
        <div style="display:block" class="message"><?= $message; ?></div>
        <? if ($checkcity > 0): ?>
            <? if (count($total_zp) > -1): ?>

                <style>
                    #russiaicon {
                        display: none;
                    }
                </style>
                <script type="text/javascript">
                    function showOption(el) {
                        disState = el.options[el.selectedIndex].value;
                        if (disState == '0') {
                            CloseClose('russiaicon');
                        }
                        if (disState == '1') {
                            OpenOpen('russiaicon');
                        }
                    }
                </script>

                <script
                        src="/<?= $theme ?>js/tinymce4/tinymce.min.js">
                </script>

                <script>
                    tinymce.init({
                        theme: "inlite",
                        height : "400",
                        selector:"#textarea1", theme:"modern", language:"ru",
                        font_formats:
                        "Andale Mono=andale mono,times;"+
                        "Arial=arial,helvetica,sans-serif;"+
                        "Arial Black=arial black,avant garde;"+
                        "Book Antiqua=book antiqua,palatino;"+
                        "Comic Sans MS=comic sans ms,sans-serif;"+
                        "Courier New=courier new,courier;"+
                        "Georgia=georgia,palatino;"+
                        "Helvetica=helvetica;"+
                        "Impact=impact,chicago;"+
                        "Symbol=symbol;"+
                        "Tahoma=tahoma,arial,helvetica,sans-serif;"+
                        "Terminal=terminal,monaco;"+
                        "Times New Roman=times new roman,times;"+
                        "Trebuchet MS=trebuchet ms,geneva;"+
                        "Verdana=verdana,geneva;"+
                        "Webdings=webdings;"+
                        "Wingdings=wingdings,zapf dingbats",
                        fontsize_formats : "8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 26pt",
                        plugins : "image imagetools colorpicker paste smileys autolink contextmenu textcolor link lists table pagebreak preview code responsivefilemanager",
                        toolbar : "undo redo | forecolor backcolor | smileys | link responsivefilemanager pagebreak | | fontselect | fontsizeselect | ",
                        contextmenu : "link responsivefilemanager insertable|| textcolor || code paste copy ",
                        pagebreak_separator: "<!-- my page break -->",
                        image_advtab: true,
                        relative_urls: false, remove_script_host: true,

                        external_filemanager_path:"/filemanager/",
                        filemanager_title:"Мои файлы" ,
                        filemanager_access_key:"myPrivateKey",
                        external_plugins: { "filemanager" : "/<?=$theme?>/js/tinymce4/plugins/responsivefilemanager/plugin.min.js"}
                    });

                </script>



                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="addzp">
                    <table summary="">
                        <tr>
                            <td valign="top"><b>Закупка</b> <span class="red">*</span></td>
                            <td><select name="id_check" class="inputbox" style="width:430px;">
                                    <option value="0">-Выберите поставщика/закупку-</option>
                                    <? foreach ($total_zp as $zp): ?>
                                        <option value="<?= $zp['id'] ?>"
                                                <? if (!empty($zp['id_check']) and $zp['status'] < 9): ?>disabled<? endif ?>
                                                <? if ($zp['id'] == $_POST['id_check']): ?>selected<? endif ?>><?= $zp['brend'] ?>
                                            (<?= $zp['url'] ?>
                                            ) <? if (!empty($zp['id_check']) and $zp['status'] < 9): ?>-- в обороте<? endif ?></option>
                                    <? endforeach; ?>
                                </select> <a href="/com/org/fixed" class="btn">Закрепить закупку</a>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><b>Название</b> <span class="red">*</span></td>
                            <td><input type="text" class="inputbox" style="width:614px;" name="title"
                                       value="<?= $_POST['title'] ?>"/></td>
                        </tr>
                        <tr>
                            <td valign="top" style="width: 114px;"><b>Описание</b> <span class="red">*</span></td>
                            <td><textarea name="textarea1" style="height: 300px;" class="tinymce"
                                          id="textarea1"><?= $_POST['textarea1'] ?></textarea></td>
                        </tr>
                        <tr>
                            <td valign="top">Информация</td>
                            <td>
                                <textarea type="text" name="inform"
                                          style="width: 445px; height: 80px; word-break:break-word;"><?= $_POST['inform'] ?></textarea>
                                <br/>Новости, дата стопа, движение товара и т.д.
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">Уровень доступа</td>
                            <td><select name="level" class="inputbox" style="width:130px;">
                                    <? foreach ($level as $lev): ?>
                                        <option value="<?= $lev['id'] ?>"
                                                <? if ($lev['id'] = $_POST['level']): ?>selected<? endif ?>><?= $lev['name'] ?></option>
                                    <? endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><b>Категория</b> <span class="red">*</span></td>
                            <td><? foreach ($cat_zp as $cat): ?>
                                    <? foreach ($cat as $ca): ?>
                                        <? if ($ca['podcat'] == 0): ?>
                                            <b><u><?= $ca['name'] ?></u></b>
                                        <? else: ?>
                                            <input type="checkbox" name="cat_zp[]" value="<?= $ca['id'] ?>"
                                                   <? if (in_array($ca['id'], $_POST['cat_zp'])): ?>checked<? endif ?>> <?= $ca['name'] ?>
                                        <? endif; ?><br/>
                                    <? endforeach; ?>
                                <? endforeach; ?>
                            </td>
                        </tr>
                        <tr style="background-color: #dedede;">
                            <td class="title"><b>Оргпроцент</b> <span class="red">*</span></td>
                            <td><input type="text" class="inputbox" name="orgperc" value="<?= $_POST['orgperc'] ?>"/> %
                            </td>
                        </tr>
                        <tr>
                            <td class="title option">Минималка</td>
                            <!--		<td><input type="text" class="inputbox" name="minimum" value="<?= $_POST['minimum'] ?>"/> р.</td>-->
                            <td><input type="text" class="inputbox" name="minimum" value="<?= $_POST['minimum'] ?>"/>
                                <span style="margin-left: 40px;">Тип минималки</span>
                                <select name="minType" class="inputbox" style="width: 100px;">
                                    <option value="0"
                                            <? if ($_POST['minType'] == 0): ?>selected<? endif ?>>рубли</option>
                                    <option value="1"
                                            <? if ($_POST['minType'] == 1): ?>selected<? endif ?>>штуки</option>
                                    <option value="2" <? if ($_POST['minType'] == 2): ?>selected<? endif ?>>кг</option>
                                </select>
                            </td>
                        </tr>
                        <tr style="background-color: #dedede;">
                            <td class="title">Курс валюты</td>
                            <td><input type="text" class="inputbox" name="curs"
                                       value="<? if (!empty($_POST['curs'])): ?><?= $_POST['curs'] ?><? else: ?>1<? endif ?>">
                                x
                            </td>
                        </tr>
                        <!--        <tr>
                <td class="title">Скидка</td>
		<td><input type="text" class="inputbox" name="bonus" value="<?= $_POST['bonus'] ?>"> %</td>
            </tr>
            <tr>
                <td class="title">Способ приема платежей</td>
		<td><select name="paytype" class="inputbox">
                        <option value="1">На карту</option>
                        <option value="2">На кошелек</option>
			<option value="3">На кошелек и на карту</option>
                    </select> <br><i>На карту - это перевод на личную карту организатора. На кошелек - онлайн оплата.</i></td>
		</tr>
-->
                        <tr>
                            <td class="title option">Реквизиты для оплаты</td>
                            <td><textarea type="text" name="rekviz"
                                          style="width: 445px;height: 60px; word-break:break-word;"><?= $editzp[0]['rekviz'] ?></textarea>
                                <br><i>(Будут отображены в корзине пользователя при статусе закупки <b>Оплата</b>)</i>
                            </td>
                        </tr>
                        <tr style="background-color: #dedede;">
                            <td class="title option">Цена доставки</td>
                            <td><input type="text" class="inputbox" name="delivery" value="<?= $_POST['delivery'] ?>">
                                р. <br><i>(От поставщика до Организатора)</i></td>
                        </tr>
                        <tr>
                            <td class="title" valign="top">Доставка по России</td>
                            <td valign="top">
                                <select name="checkr" class="inputbox" onchange="showOption(this)">
                                    <option value="0">нет</option>
                                    <option value="1">да</option>
                                </select> <i><br/>(Возможна ли отправка почтой)</i><br/>
                                <div id="russiaicon">
                                    <table>
                                        <tr>
                                            <? $i = 0;
                                            foreach ($delivery as $del):
                                            $i++; ?>
                                            <td>
                                                <input type="checkbox" name="russia[]" value="<?= $del['id'] ?>"
                                                       <? if ($del['id'] == 1): ?>checked<? endif; ?>/>
                                                <img src="/<?= $theme ?>images/delivery/<?= $del['img'] ?>" width="60"
                                                     width="32" alt="<?= $del['name'] ?>" title="<?= $del['name'] ?>"
                                                     border="1"/>
                                            </td>
                                            <? if ($i == 4):
                                            $i = 0; ?>
                                        </tr>
                                        <tr>
                                            <? endif; ?>
                                            <? endforeach; ?>
                                        </tr>
                                    </table>
                                    < /div>
                            </td>
                        </tr>

                        <? if ($user->get_property('gid') == 25): ?>
                            <tr style="background-color: #dedede;">
                                <td class="title">Горячая закупка</td>
                                <td><select name="hot" class="inputbox">
                                        <option value="0">Нет</option>
                                        <option value="0">Да</option>
                                        <!--  <option value="1">Да</option>  -->
                                    </select>
                                </td>
                            </tr>
                        <? endif ?>

                        <? if ($user->get_property('gid') == 25): ?>
                            <tr>
                                <td class="title">Прикрепить закупку</td>
                                <td><select name="top" class="inputbox">
                                        <option value="0">нет</option>
                                        <option value="1">да</option>
                                    </select> <br>(Вывод закупки вверху списка, поднятие в топку:)
                                </td>
                            </tr>
                        <? endif ?>

                        <tr style="background-color: #dedede;">
                            <td class="title">Фото</td>
                            <td><input class="inputbox" type="file" name="photo">
                            </td>
                        </tr>

                        <!-- Пустышка, дабы раздвинуть кнопы-->
                        <td style="height: 10px;"></td>

                        <tr>
                            <td class="title">Файл 1</td>
                            <td><input class="inputbox" type="file" name="file1">
                                <br><label>Название прайса1<br>
                                    <textarea style="width: 250px;height: 21px; background-color: #FBE4E4;"
                                              placeholder="Введи название прайса1, если есть"
                                              name="price_name1"><?= $addzp[0]['price_name1'] ?></textarea></td>
                        </tr>

                        <tr style="background-color: #dedede;">
                            <td class="title">Файл 2</td>
                            <td><input class="inputbox" style="margin-top: 20px;" type="file" name="file2">
                                <br><label>Название прайса2<br>
                                    <textarea style="width: 250px;height: 21px; background-color: #E4FBE9;"
                                              placeholder="Введи название прайса2, если есть"
                                              name="price_name2"><?= $addzp[0]['price_name2'] ?></textarea></td>
                        </tr>

                        <tr>
                            <td class="title">Файл 3</td>
                            <td><input class="inputbox" style="margin-top: 20px;" type="file" name="file3">
                                <br><label>Название прайса3<br>
                                    <textarea style="width: 250px;height: 21px; background-color: #E4E9FB;"
                                              placeholder="Введи название прайса3, если есть"
                                              name="price_name3"><?= $addzp[0]['price_name3'] ?></textarea></td>
                        </tr>

                        <tr style="background-color: #dedede;">
                            <td class="title">Тип закупки</td>
                            <td><select name="type" class="inputbox">
                                    <option value="0">Классический</option>
                                    <option value="1">Магазин</option>
                                    <option value="2">Закупка рядами</option>
                                </select>
                                <br>В "Закупке рядами" кнопка "Добавить заказ не видна". Заказ возможен только при
                                помощи корзинки !!!
                            </td>
                            </select></td>
                        </tr>

                        <? if (count($office) > 0): ?>
                            <tr>
                                <td valign="top" class="title">Офисы раздач</td>
                                <td valign="top">
                                    <input type="checkbox" name="office[]" class="notify" value="9999"
                                           <? if (in_array(9999, $_POST['office'])): ?>checked<? endif ?>>Все<br/>
                                    <? foreach ($office as $off): ?>
                                        <input type="checkbox" name="office[]" class="notify"
                                               value="<?= $off['id'] ?>" <? if (in_array($off['id'], $_POST['office'])): ?>checked<? endif ?>><?= $off['name'] ?>
                                        <br/>
                                    <? endforeach ?><br/><br/>
                                </td>
                            </tr>
                        <? endif ?>


                        <tr style="background-color: #dedede;">
                            <td><b>Статус</b></td>
                            <td><select name="status" class="inputbox">
                                    <option value="1">Редактирование</option>
                                    <option value="2">Готова к открытию</option>
                                    <? if ($registry['premoder'] == 1): ?>
                                        <option value="3">Открыта</option>
                                    <? endif ?><br/>
                                </select></td>
                        </tr>

                        <tr style="height:50px;">
                            <td class="title option"></td>
                            <td><input type="checkbox" name="alertnews" class="notify" value="1"
                                       <? if (!empty($_POST['alertnews'])): ?>checked<? elseif (empty($_POST['action'])): ?>checked<? endif ?>>Уведомлять
                                о новом заказе<br/>
                                <input type="checkbox" name="alertcomm" class="notify" value="1"
                                       <? if (!empty($_POST['alertcomm'])): ?>checked<? endif ?>>Уведомлять о новом
                                комментарии
                            </td>
                            <br/>
                        </tr>
                    </table>

                    <table border="0" width="100%">
                        <tr class="last">
                            <td><a class="btn" href="/com/org/">Отмена</a></td>
                            <td align="right"><input type="submit" class="btn" value="Сохранить"></td>
                        </tr>
                    </table>
                </form><br/><br/>

                <p>Поля отмеченные звёздочкой (<span class="red">*</span>) обязательны для заполнения</p>
                <h1>Информация</h1>
                <p><b>Уровень доступа</b>:<br/>
                    Кому будет видна ваша закупка.<br/>
                <ul>
                    <li><b>Зеленый</b> - закупка будет видна, только проверенным пользователям, имеющим репутацию на
                        сайте - как идеальный покупатель.
                    </li>
                    <li><b>Желтый</b> - Закупка будет видна как пользователям с репутацией, так и пользователям
                        относительно проверенным. Тем, с которыми можно работать.
                    </li>
                    <li><b>Красный</b> - Закупка будет видна всем пользователям, в том числе и новеньким.</li>
                </ul>
                <p><b>Статус</b>:<br/>
                    Если Вы хотите создать новую закупку, но пока не хотите отправлять ее на проверку администрации, и
                    не хотите, чтобы она была опубликована, установите статус "редактирование". Как только Вы будите,
                    готовы к ее открытию, Вы в любое время можете установить статус "готова к открытию".</p>
                <p><b>Цена доставки от фирмы до Орга</b>:<br/>
                    Будет разделана на участников, в процентном соотношении в зависимости от суммы заказа

                <p><b>Типы закупки</b>:<br/>
                <ul>
                    <li><b>Классический</b> - Закупка собирается по принципу наполнения рядов и достижения минимальной
                        суммы выкупа. После чего организатор ставит её в статус СТОП и так далее.
                    </li>
                    <li><b>Магазин</b> - Закупка не переводится в статус СТОП, ряды не собираются, вместо этого
                        пользователь просто добавляет товар себе в корзину, как в обычном магазине. При этом у каждого
                        заказа появляются статусы: неоплаченный, оплаченный, раздача
                    </li>
                </ul>
            <? else: ?>
                <p>За вами нет закрепленных закупок. <a href="/com/org/fixed" class="link4">Закрепить закупку</a>.
                <p><a class="link4" href="/com/org/">...Назад</a></p>
            <? endif; ?>
        <? else: ?>
            <? @include('.city.php'); ?>
        <? endif; ?>
    </div>
<? else: ?>
    <? @include('.access.php'); ?>
<? endif ?>