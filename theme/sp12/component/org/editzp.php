<? defined('_JEXEC') or die('Restricted access'); ?>
<? if ($user->get_property('userID') > 0 and $user->get_property('gid') >= 23): ?>

    <div class="menu-top5">Организаторская: редактировать закупку</div>
    <div class="menu-body5">
        <div style="display:block" class="message"><?= $message; ?></div>
        <? if (count($editzp) > 0):
            $editzp[0]['russia'] = unserialize($editzp[0]['russia']);
            ?>


            <script src="/<?= $theme ?>js/tinymce4/tinymce.min.js"></script>

            <script>
                tinymce.init({
                    height: "500",
                    selector: "#textarea1", theme: "modern", language: "ru",
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
                    fontsize_formats: "8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 26pt",
                    plugins: "image imagetools colorpicker paste smileys autolink contextmenu textcolor link lists table pagebreak preview code responsivefilemanager",
                    toolbar: "undo redo | forecolor backcolor | smileys | link responsivefilemanager pagebreak | | fontselect | fontsizeselect | ",
                    contextmenu: "link responsivefilemanager insertable|| textcolor || code paste copy ",
                    pagebreak_separator: "<!-- my page break -->",
                    image_advtab: true,
                    relative_urls: false, remove_script_host: true,

                    external_filemanager_path: "/filemanager/",
                    filemanager_title: "Мои файлы",
                    filemanager_access_key: "myPrivateKey",
                    external_plugins: {"filemanager": "/<?=$theme?>/js/tinymce4/plugins/responsivefilemanager/plugin.min.js"}
                });

            </script>

            <script>
                tinymce.init({
                    height: "200px",
                    selector: "#textarea2", language: "ru",
                    font_formats: "Arial=arial,helvetica,sans-serif;" +
                    "Arial Black=arial black,avant garde;",
                    fontsize_formats: "8pt 10pt 12pt 14pt",
                    plugins: "textcolor paste autolink",
                    toolbar: "undo redo | forecolor backcolor | | fontselect | fontsizeselect",
                    relative_urls: false, remove_script_host: true
                });
            </script>


            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="editzp">
                <input type="hidden" name="idzp" value="<?= $editzp[0]['id'] ?>">
                <table summary="" class="table-org">
                    <tr>
                        <td valign="top"><b>Закупка</b> <span class="red">*</span></td>
                        <td><input type="text" class="inputbox" style="width:600px;" name="zp"
                                   value="<?= $editzp[0]['brend'] ?> - <?= $editzp[0]['url'] ?>" disabled/>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><b>Название</b> <span class="red">*</span></td>
                        <td><input type="text" class="inputbox" style="width:600px;" name="title"
                                   value="<?= $editzp[0]['title'] ?>"/></td>
                    </tr>
                    <tr>
                        <td valign="top" style="width: 114px;"><b>Описание</b> <span class="red">*</span></td>
                        <td><textarea style="height:300px;" name="textarea1" class="tinymce" id="textarea1">
			<?= $editzp[0]['text'] ?>
                    </textarea>
                            <!--                <textarea name="textarea" id="textarea" class="tinymce" style="width: 300px;height:600px;"></textarea> -->
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Информация</td>
                        <td><textarea type="tinymce" id="textarea2" name="inform"
                                      style="width: 445px; height: 180px; word-break:break-word;"><?= $editzp[0]['inform'] ?></textarea>
                            <br/>Новости, дата стопа, движение товара и т.д.
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Уровень доступа</td>
                        <td><select name="level" class="inputbox" style="width:130px;">
                                <? foreach ($level as $lev):?>
                                    <option value="<?= $lev['id'] ?>"
                                            <? if ($lev['id'] == $editzp[0]['level']): ?>selected<? endif ?>><?= $lev['name'] ?></option>
                                <? endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><b>Категория</b> <span class="red">*</span></td>
                        <td>    <? foreach ($cat_zp as $cat):?>
                                <? foreach ($cat as $ca):?>
                                    <? if ($ca['podcat'] == 0):?>
                                        <b><u><?= $ca['name'] ?></u></b>
                                    <? else:?>
                                        <input type="checkbox" name="cat_zp[]" value="<?= $ca['id'] ?>"
                                               <? if (in_array($ca['id'], $editzp[0]['cat_zp'])): ?>checked<? endif ?>> <?= $ca['name'] ?>
                                    <? endif; ?>
                                    <br/>
                                <? endforeach; ?>
                            <? endforeach; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="title_org"><b>Оргпроцент</b> <span class="red">*</span></td>
                        <td><input type="text" class="inputbox-org" name="orgperc" value="<?= $editzp[0]['proc'] ?>"/> %
                        </td>
                    </tr>
                    </tr>
                    <tr style="background-color: #dedede;">
                        <td class="title_org option">Минималка</td>
                        <td><input type="text" class="inputbox-org" name="minimum" value="<?= $editzp[0]['min'] ?>"/>
                            <span style="margin-left: 130px;">Тип минималки</span>
                            <select name="minType" class="inputbox-org" style="width: 100px; border: 1px solid saddlebrown">
                                <option value="0"
                                        <? if ($editzp[0]['minType'] == 0): ?>selected<? endif ?>>рубли</option>
                                <option value="1"
                                        <? if ($editzp[0]['minType'] == 1): ?>selected<? endif ?>>штуки</option>
                                <option value="2" <? if ($editzp[0]['minType'] == 2): ?>selected<? endif ?>>кг.</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="title_org">Курс валюты</td>
                        <td><input type="text" class="inputbox-org" name="curs"
                                   value="<? if (!empty($editzp[0]['curs'])):?><?= $editzp[0]['curs'] ?><? else:?>1<? endif ?>">
                            x
                        </td>
                    </tr>

                    <!--<tr><td class="title_org">Скидка</td> /* Скрыто */
			<td><input type="text" class="inputbox" name="bonus" value="<?= $editzp[0]['bonus'] ?>"> %</td>
			</tr>-->

                    <!--<tr><td class="title_org">Способ приема платежей</td>
			<td><select name="paytype" class="inputbox">
				<option value="1" <? if ($editzp[0]['paytype'] == 1):?>selected<? endif ?>>На карту</option>
				<option value="2" <? if ($editzp[0]['paytype'] == 2):?>selected<? endif ?>>На кошелек</option>
				<option value="3" <? if ($editzp[0]['paytype'] == 3):?>selected<? endif ?>>На кошелек и на карту</option>
			</select><br><i>(На карту - это платеж оффлайн, например, на карту Сбербанка. На кошелек - Онлайн оплата.)</i></td>
			</tr> -->

                    <tr style="background-color: #dedede;">
                        <td class="title_org option">Реквизиты для оплаты</td>
                        <td><textarea type="text" name="rekviz"
                                      style="width: 445px;height: 60px; word-break:break-word;"><?= $editzp[0]['rekviz'] ?></textarea>
                            <br><i>(Будут отображены в корзине пользователя при статусе закупки <b>Оплата</b>)</i>
                        </td>
                    </tr>
                    <tr style="background-color: #f0f0f0;">
                        <td class="title_org option">Цена доставки</td>
                        <td><input type="text" class="inputbox-org" name="delivery" value="<?= $editzp[0]['dost'] ?>"> р.
                            <br><i>(От поставщика до Организатора)</i></td>
                    </tr>
                    <tr style="background-color: #dedede;">
                        <td class="title_org" valign="top">Доставка по России</td>
                        <td valign="top">
                            <select name="checkr" class="inputbox-org" onchange="showOption(this)">
                                <option value="0">нет</option>
                                <option value="1"
                                        <? if (count($editzp[0]['russia']) > 0 and $editzp[0]['russia'][0] > 0): ?>selected<? endif ?>>да</option>
                            </select> <br><i>(Возможна ли отправка почтой)</i><br/>
                            <div id="russiaicon">
                                <table>
                                    <tr>
                                        <? $i = 0;
                                        foreach ($delivery as $del):
                                        $i++; ?>
                                        <td>
                                            <input type="checkbox" name="russia[]" value="<?= $del['id'] ?>"
                                                   <? if (in_array($del['id'], $editzp[0]['russia'])): ?>checked<? endif;
                                            ?>/>
                                            <img src="/<?= $theme ?>images/delivery/<?= $del['img'] ?>" width="60"
                                                 width="32" alt="<?= $del['name'] ?>" title_org="<?= $del['name'] ?>"
                                                 border="1"/>
                                        </td>
                                        <? if ($i == 4):
                                        $i = 0; ?></tr>
                                    <tr><? endif;
                                        ?>
                                        <? endforeach;
                                        ?>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <? if ($user->get_property('gid') == 25):?>
                        <tr style="background-color: #f0f0f0;">
                            <td class="title_org">Горячая закупка</td>
                            <td><select name="hot" class="inputbox-org">
                                    <option value="0" <? if ($editzp[0]['hot'] == 0): ?>selected<? endif ?>>нет</option>
                                    <option value="1" <? if ($editzp[0]['hot'] == 1): ?>selected<? endif ?>>да</option>
                                </select></td>
                        </tr>
                    <? endif ?>

                    <? if ($user->get_property('gid') == 25):?>
                        <tr style="background-color: #f0f0f0;">
                            <td class="title_org">Прикрепить закупку</td>
                            <td><select name="top" class="inputbox-org">
                                    <option value="0" <? if ($editzp[0]['top'] == 0): ?>selected<? endif ?>>нет</option>
                                    <option value="1" <? if ($editzp[0]['top'] == 1): ?>selected<? endif ?>>да</option>
                                </select> <br>(Вывод закупки вверху списка, поднятие в топку:)
                            </td>
                        </tr>
                    <? endif ?>


                    <tr style="background-color: #f0f0f0;">
                        <td>Скоро СТОП</td>
                        <td><select name="soonStop" style="width: 100px">
                                <option value="0"
                                        <? if ($editzp[0]['soonStop'] == 0): ?>selected<? endif ?>>нет</option>
                                <option value="1" <? if ($editzp[0]['soonStop'] == 1): ?>selected<? endif ?>>да</option>
                            </select>

                            <span style="margin-left: 230px;">Дата стопа</span>
                            <!--todo Добавляем дату стопа, если она известна-->
                            <span><input id="dateStop" name="dateStop" value="<?= $editzp[0]['dateStop'] ?>"></span>
                        </td>
                    </tr>


                    <tr style="background-color: #dedede;">
                        <td class="title_org">Фото</td>
                        <td><input class="inputbox" type="file" name="photo"><br/>
                            <? if ($img_path2 > ''):?>
                                <img src="<?= $img_path2 ?>" width="125" height="100" alt="" border="0" align="left"
                                     class="photo"/><? endif ?>
                        </td>
                    </tr>

                    <!-- Пустышка, дабы раздвинуть кнопы-->
                    <td style="height: 10px;"></td>

                    <tr style="background-color: #f0f0f0;">
                        <td class="title_org">Файл 1</td>
                        <td><input class="inputbox" type="file" name="file1">
                            <? if ($editzp[0]['file1']):?><?= $editzp[0]['file1'] ?><input type="checkbox" value="1"
                                                                                           name="delfile1"/> Удалить<? endif ?>
                            <br><label>Название прайса1<br>
                                <textarea style="width: 250px;height: 21px; background-color: #FBE4E4;"
                                          name="price_name1"><?= $editzp[0]['price_name1'] ?></textarea>
                        </td>
                    </tr>

                    <tr style="background-color: #dedede;">
                        <td class="title_org">Файл 2</td>
                        <td><input class="inputbox" style="margin-top: 20px;" type="file" name="file2">
                            <? if ($editzp[0]['file2']):?><?= $editzp[0]['file2'] ?> <input type="checkbox" value="1"
                                                                                            name="delfile2"/> Удалить<? endif ?>
                            <br><label>Название прайса2<br>
                                <textarea style="width: 250px;height: 21px; background-color: #E4FBEB;"
                                          name="price_name2"><?= $editzp[0]['price_name2'] ?></textarea>
                        </td>
                    </tr>

                    <tr style="background-color: #f0f0f0;">
                        <td class="title_org">Файл 3
                        <td><input class="inputbox" style="margin-top: 20px;" type="file" name="file3">
                            <? if ($editzp[0]['file3']):?><?= $editzp[0]['file3'] ?> <input type="checkbox" value="1"
                                                                                            name="delfile3"/> Удалить<? endif ?>
                            <br><label>Название прайса3<br>
                                <textarea style="width: 250px;height: 21px; background-color: #E4E8FB;"
                                          name="price_name3"><?= $editzp[0]['price_name3'] ?></textarea>
                        </td>
                    </tr>

                    <tr style="background-color: #dedede;">
                        <td class="title_org">Тип закупки</td>
                        <td><select name="type" class="inputbox-org">
                                <option value="0"
                                        <? if ($editzp[0]['type'] == 0): ?>selected<? endif ?>>Классический</option>
                                <option value="1"
                                        <? if ($editzp[0]['type'] == 1): ?>selected<? endif ?>>Магазин</option>
                                <option value="2"
                                        <? if ($editzp[0]['type'] == 2): ?>selected<? endif ?>>Закупка рядами</option>
                            </select><br>
                            <span class="title_org">В "Закупке рядами" кнопка "Добавить заказ не видна". Заказ возможен только при помощи корзинки !!!</span>
                        </td>
                    </tr>

                    <? if (count($office) > 0):?>
                        <tr style="background-color: #f0f0f0;">
                            <td valign="top" class="title_org">Офисы раздач</td>
                            <td valign="top">
                                <input type="checkbox" name="office[]" class="notify" value="9999"
                                       <? if (in_array(9999, $editzp[0]['office'])): ?>checked<? endif ?>>Все<br/>
                                <? foreach ($office as $off):?>
                                    <input type="checkbox" name="office[]" class="notify"
                                           value="<?= $off['id'] ?>" <? if (in_array($off['id'], $editzp[0]['office'])): ?>checked<? endif ?>><?= $off['name'] ?>
                                    <br/>
                                <? endforeach ?><br/><br/>
                            </td>
                        </tr>
                    <? endif ?>

                    <tr style="height:25px; background-color:#dedede" ;class="title_org" ;>
                        <td>Статус</td>
                        <td><select name="status" class="inputbox-org">
                                <? $stsel[$editzp[0]['status']] = 'selected'; ?>
                                <? if ($editzp[0]['status'] == 0 or $editzp[0]['status'] == 1) { ?>
                                    <option value="1" <?= $stsel[1]; ?>><?= $statuslist[0]['name']; ?></option><? } ?>
                                <? if ($editzp[0]['status'] <= 2) { ?>
                                    <option
                                    value="2" <? echo $stsel[2]; ?>><?= $statuslist[1]['name']; ?></option><? } ?>
                                <? if ($editzp[0]['status'] == 3 || ($registry['premoder'] == 1 and $openzakup[0]['status'] < 5)) { ?>
                                    <option
                                    value="3" <? echo $stsel[3]; ?>><?= $statuslist[2]['name']; ?></option><? } ?>
                                <? if ($editzp[0]['type'] == 0 || $editzp[0]['type'] == 2):?>
                                    <? if ($editzp[0]['status'] >= 3 AND $editzp[0]['status'] <= 5) { ?>
                                        <option
                                        value="4" <?= $stsel[4]; ?>><?= $statuslist[3]['name']; ?></option><? } ?>
                                    <? if ($editzp[0]['status'] >= 4 AND $editzp[0]['status'] <= 5) { ?>
                                        <option
                                        value="5" <?= $stsel[5]; ?>><?= $statuslist[4]['name']; ?></option><? } ?>
                                    <? if ($editzp[0]['status'] == 4 OR $editzp[0]['status'] == 6) {
                                        ?>
                                        <option
                                        value="6" <?= $stsel[6]; ?>><?= $statuslist[5]['name']; ?></option><? } ?>
                                    <? if ($editzp[0]['status'] == 6 OR $editzp[0]['status'] == 7) {
                                        ?>
                                        <option
                                        value="7" <?= $stsel[7]; ?>><?= $statuslist[6]['name']; ?></option><? } ?>
                                    <? if ($editzp[0]['status'] == 7 OR $editzp[0]['status'] == 8) {
                                        ?>
                                        <option
                                        value="8" <?= $stsel[8]; ?>><?= $statuslist[7]['name']; ?></option><? } ?>
                                    <? if ($editzp[0]['status'] == 10) {
                                        ?>
                                        <option
                                        value="10" <?= $stsel[8]; ?>><?= $statuslist[7]['name']; ?></option><? } ?>
                                <? endif ?>
                                <? if ($editzp[0]['status'] == 8 OR $editzp[0]['status'] == 9 or $editzp[0]['type'] == 1) {
                                    ?>
                                    <option value="9" <?= $stsel[9]; ?>><?= $statuslist[8]['name']; ?></option><? } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="title_org option"></td>
                        <td>
                            <input type="checkbox" name="alertnews" class="notify" value="1"
                                   <? if (!empty($editzp[0]['alertnews'])):?>checked<? elseif (empty($editzp[0]['action'])): ?>checked<? endif ?>>
                            уведомлять о новом заказе<br/>
                            <input type="checkbox" name="alertcomm" class="notify" value="1"
                                   <? if (!empty($editzp[0]['alertcomm'])): ?>checked<? endif ?>>
                            уведомлять о новом комментарии
                        </td>
                </table>
                <br/>

                <table border="0" width="100%">
                    <tr class="last">
                        <td><a class="btn" href="/com/org/">Отмена</a></td>
                        <td align="right"><input type="submit" class="btn" value="Сохранить"></td>
                    </tr>
                </table>

            </form><br/><br/>
            <p>Поля отмеченные звёздочкой (<span class="red">*</span>) обязательны для заполнения</p>


            <div class="panel">
                <? //if($editzp[0]['status']==9):
                ?> <br/>
                <!--<a href="/com/org/reopen/<?= $_GET['value'] ?>/" class="link7">Открыть закупку повторно</a> <br> - Информация о всех заказах будет удалена из данной закупки<br/>-->
                <? //endif
                ?>
                <a href="/com/org/reopenduble/<?= $_GET['value'] ?>/" class="link7">Дублировать закупку</a> <br> -
                Данная закупка останется не измененной, при этом будет дублирована и окрыта новая<br/>
                <? //if ($editzp[0]['type']==0):
                ?>
                <a href="/com/org/reopendubord/<?= $_GET['value'] ?>/" class="link7">
                    Дублировать закупку с переносом в неё всех невыкупленных заказов
                </a>
                <br> - Все заказы за исключение заказов со статусом "Включен в счет", будут перенесены.
                <? //endif
                ?>
            </div>


            <h1>Информация</h1>
            <p><b>Уровень доступа</b>:<br/>
                Кому будет видна ваша закупка.<br/>
            <ul>
                <li><b>Зеленый</b> - закупка будет видна, только проверенным пользователям, имеющим репутацию на сайте -
                    как идеальный покупатель.
                </li>
                <li><b>Желтый</b> - Закупка будет видна как пользователям с репутацией, так и пользователям относительно
                    проверенным. Тем, с которыми можно работать.
                </li>
                <li><b>Красный</b> - Закупка будет видна всем пользователям, в том числе и новеньким.</li>
            </ul>
            <p><b>Статус</b>:<br/>
                Если Вы хотите создать новую закупку, но пока не хотите отправлять ее на проверку администрации, и не
                хотите, чтобы она была опубликована, установите статус "редактирование". Как только Вы будите, готовы к
                ее открытию, Вы в любое время можете установить статус "готова к открытию".</p>

            <p><b>Типы закупки</b>:<br/>
            <ul>
                <li><b>Классический</b> - Закупка собирается по принципу наполнения рядов и достижения минимальной суммы
                    выкупа. После чего организатор ставит её в статус СТОП и так далее.
                </li>
                <li><b>Магазин</b> - Закупка не переводится в статус СТОП, ряды не собираются, вместо этого пользователь
                    просто добавляет товар себе в корзину, как в обычном магазине. При этом у каждого заказа появляются
                    статусы: неоплаченный, оплаченный, раздача
                </li>
            </ul>

        <? else: ?>
            <h1>Вы не можете редактировать данную закупку</h1>
            <p><a href="/" class="link4">...На главную.</a></p>
            <p>Возможные причины ошибки:</p>
            <ul>
                <li>Время сессии авторизации истекло</li>
                <li>Вы пытаетесь попасть в раздел только для зарегистрированных пользователей</li>
                <li>Вы пытаетесь редактировать чужую закупку</li>
                <li>Такой закупки не существует</li>
            </ul>
        <? endif; ?>
    </div>
<? else: ?>
    <? @include('.access.php'); ?>
<? endif ?>