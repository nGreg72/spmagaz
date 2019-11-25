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
                plugins : "paste smileys autolink contextmenu textcolor link lists table pagebreak preview code",
                toolbar : "undo redo | forecolor backcolor | smileys | link pagebreak | | fontselect | fontsizeselect | ",
                contextmenu : "link insertable|| textcolor || code paste copy ",
                pagebreak_separator: "<!-- my page break -->",
                image_advtab: true,
                relative_urls: false, remove_script_host: true,

                external_filemanager_path:"/filemanager/",
                filemanager_title:"Мои файлики" ,
                filemanager_access_key:"myPrivateKey",
                external_plugins: { "filemanager" : "/<?=$theme?>/js/tinymce4/plugins/responsivefilemanager/plugin.min.js"}
            });

        </script>

        <div class="menu-top5">Организаторская: Редактировать ряд</div>
        <div class="menu-body5">
            <form action="" method="post" enctype="multipart/form-data" id="sendeform" name="sendeform">
                <input type="hidden" name="action" value="editr">
                <input type="hidden" name="idpost" value="<?= intval($_GET['value']) ?>">
                <table summary="">
                    <tr>
                        <td valign="top" width="100"><b>Название</b></td>
                        <td><input type="text" class="inputbox" style="width:500px;" name="title"
                                   value="<?= str_replace('"', '\'', $items[0]['title']); ?>">
                            <br/>Именно это будет в корзине у участника. Пишите так чтобы ему было понятно!
                        </td>
                    </tr>
                    <tr style="background-color: #dee1e1">
                        <td valign="top">Артикул</td>
                        <td><input type="text" class="inputbox" style="width:400px;" name="articul"
                                   value="<?= $items[0]['articul']; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><b>Описание</b></td>
                        <td>
                            <textarea name="textarea1" class="tinymce"
                                      id="textarea1"><?= $items[0]['message'] ?></textarea>
                        </td>
                    </tr>
                    <tr style="background-color: #dee1e1">
                        <td class="title option"><b>Цена</b></td>
                        <td><input type="text" class="inputbox" name="price" value="<?= $items[0]['price']; ?>"> р.</td>
                    </tr>

                    <!--Вытаскиваем данные об количестве товара в коробке из базы... -->
                    <? $query = "SELECT * FROM `sp_size` WHERE `id_ryad` = " . $id_editr;
                    $howMuchInsideBox = $DB->getAll($query); ?>


                    <tr>
                        <td valign="top"><b>Размеры или количество</b></td>
                        <td><input type="text" class="inputbox" style="width:60px;" name="size"
                                   value="<?= $howMuchInsideBox[0]['name'] ?>"> <!--... и вставляем тут!-->
                            <!--                     Устаревщий вариант вытаскивания инфы из БД, из таблицы sp_ryad <? //=$items[0]['size'];?>  -->

                            <!--<a href="/com/org/edpos/<?= $_GET['value'] ?>/<?= $_GET['value2'] ?>" class="link4">редактировать</a><br/>-->
                            <!--			Пример размеров: 40,41,41,42<br/>
                                        Пример количества: 3-->
                        </td>
                    </tr>
                    <tr style="background-color: #dee1e1">
                        <td class="title option">Основное фото товара</td>
                        <td>
                            <input class="inputbox" type="file" name="photo" style="width:550px"> <br/>
                            <?
                            if ($img_path2 > ''):?>
                                <img src="<?= $img_path2 ?>" width="125" height="100" alt="" border="0" align="left"
                                     class="photo"/><br/>
                                <input type="checkbox" name="photodel" value="1">удалить фото<br/>
                            <? endif ?>
                            <br/>&nbsp;


                        </td>
                    </tr>

                    <tr>
                        <td class="title option"></td>
                        <td>
                            <input type="checkbox" name="autoblock" class="notify" value="1"
                                   <? if ($items[0]['autoblock'] == 1): ?>checked<? endif ?>>
                            автоматическая блокировка размерного ряда при заполнении (autoblock)<br/>

                            <input type="checkbox" name="allblock" class="notify" value="1"
                                   <? if ($items[0]['allblock'] == 1): ?>checked<? endif ?>>
                            блокировка каждого заказа, выписать может только Организатор (allblock)<br/>

                            <input type="checkbox" name="auto" class="notify" value="1"
                                   <? if ($items[0]['auto'] == 1): ?>checked<? endif ?>>
                            дублировать линейку размеров при наполнении автоматически (auto)
                        </td>
                    </tr>
                    <tr style="background-color: #dee1e1">
                        <td class="title option">Категория</td>
                        <td><input type="text" class="inputbox" name="cat" value="<?= $items[0]['cat']; ?>"
                                   title="разбивка товаров на категории внутри закупки"> <br/>(внутри закупки будет
                            создана данная категория, для товаров)
                        </td>
                    </tr>
                </table>

                <table>
                    <!-- todo Вкл/Выкл "Нет в наличии"-->
                    <tr>
                        <td class="title option" style="width: 100px;">Временно нет в наличии</td>
                        <td>
                            <select name="tempOff">
                                <option value="0"
                                        <? if ($items[0]['tempOff'] == 0): ?>selected<? endif ?>>Есть в наличии</option>
                                <option value="1"
                                        <? if ($items[0]['tempOff'] == 1): ?>selected<? endif ?>>Нет в наличии</option>
                            </select>
                        </td>
                        <td>Если товара временно нет в наличии, но он скоро может появиться, можно временно отключить
                            позицию. Пользователи не будут видеть отключенный товар!
                        </td>
                    </tr>
                    <tr style="background-color: #dee1e1">
                        <td class="title option">Акция?</td>
                        <td>
                            <select>
                                <option value="0" <? if ($items[0]['bla bla'] == 0): ?>selected<? endif ?>>Нет</option>
                                <option value="1"
                                        <? if ($items[0]['bla bla'] == 1): ?>selected<? endif ?>>Акция</option>
                                <option value="2" <? if ($items[0]['bla bla'] == 2): ?>selected<? endif ?>>Sale</option>
                            </select>
                        </td>
                        <td>Ежели это акция, то выбираем на русском или английском</td>
                    </tr>
                </table>
                <br/>
            </form>

            <h2>Дполнительные фото товара</h2>

            <!-- jQuery UI styles -->
            <link rel="stylesheet"
                  href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/blitzer/jquery-ui.css" id="theme">
            <!-- blueimp Gallery styles -->
            <link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
            <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
            <link rel="stylesheet" href="/fmanager/css/jquery.fileupload.css">
            <link rel="stylesheet" href="/fmanager/css/jquery.fileupload-ui.css">
            <!-- CSS adjustments for browsers with JavaScript disabled -->
            <noscript>
                <link rel="stylesheet" href="/fmanager/css/jquery.fileupload-noscript.css">
            </noscript>
            <noscript>
                <link rel="stylesheet" href="/fmanager/css/jquery.fileupload-ui-noscript.css">
            </noscript>
            <!-- The file upload form used as target for the file upload widget -->
            <form id="fileupload"
                  action="/fmanager/uploads/index.php?id=<?= $_GET['value2'] ?>&id_r=<?= $_GET['value'] ?>&type=2"
                  method="POST" enctype="multipart/form-data">
                <!-- Redirect browsers with JavaScript disabled to the origin page -->
                <noscript><input type="hidden" name="redirect" value=""></noscript>
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="fileupload-buttonbar">
                    <div class="fileupload-buttons">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="fileinput-button">
                <span>Добавить фото...</span>
                <input type="file" name="files[]" multiple>
            </span>
                        <button type="submit" class="start">Старт закачки</button>
                        <button type="reset" class="cancel">Отмена заказчки</button>
                        <button type="button" class="delete">Удалить</button>
                        <input type="checkbox" class="toggle">
                        <!-- The global file processing state -->
                        <span class="fileupload-process"></span>
                    </div>
                    <!-- The global progress state -->
                    <div class="fileupload-progress fade" style="display:none">
                        <!-- The global progress bar -->
                        <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                        <!-- The extended global progress state -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="presentation">
                    <tbody class="files"></tbody>
                </table>

            </form>
            <!-- The blueimp Gallery widget -->
            <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                <div class="slides"></div>
                <h3 class="title"></h3>
                <a class="prev">‹</a>
                <a class="next">›</a>
                <a class="close">×</a>
                <a class="play-pause"></a>
                <ol class="indicator"></ol>
            </div>
            <!-- The template to display files available for upload -->
            <script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error"></strong>
        </td>
        <td>
            <p class="size">Процесс...</p>
            <div class="progress"></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="start" disabled>Старт</button>
            {% } %}
            {% if (!i) { %}
                <button class="cancel">Отмена</button>
            {% } %}
        </td>
    </tr>
{% } %}

            </script>
            <!-- The template to display files available for download -->
            <script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
            </p>
            {% if (file.error) { %}
                <div><span class="error">Ошибка</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            <button class="delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>Удалить</button>
            <input type="checkbox" name="delete" value="1" class="toggle">
        </td>
    </tr>
{% } %}

            </script>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
            <!-- The Templates plugin is included to render the upload/download listings -->
            <!-- The Templates plugin is included to render the upload/download listings -->
            <script src="/fmanager/js/tmpl.min.js"></script>
            <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
            <script src="/fmanager/js/load-image.js"></script>
            <script src="/fmanager/js/load-image-meta.js"></script>
            <!-- The Canvas to Blob plugin is included for image resizing functionality -->
            <script src="/fmanager/js/canvas-to-blob.min.js"></script>
            <!-- blueimp Gallery script -->
            <script src="/fmanager/js/jquery.blueimp-gallery.min.js"></script>
            <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
            <script src="/fmanager/js/jquery.iframe-transport.js"></script>
            <!-- The basic File Upload plugin -->
            <script src="/fmanager/js/jquery.fileupload.js"></script>
            <!-- The File Upload processing plugin -->
            <script src="/fmanager/js/jquery.fileupload-process.js"></script>
            <!-- The File Upload image preview & resize plugin -->
            <script src="/fmanager/js/jquery.fileupload-image.js"></script>
            <!-- The File Upload audio preview plugin -->
            <script src="/fmanager/js/jquery.fileupload-audio.js"></script>
            <!-- The File Upload video preview plugin -->
            <script src="/fmanager/js/jquery.fileupload-video.js"></script>
            <!-- The File Upload validation plugin -->
            <script src="/fmanager/js/jquery.fileupload-validate.js"></script>
            <!-- The File Upload user interface plugin -->
            <script src="/fmanager/js/jquery.fileupload-ui.js"></script>
            <!-- The File Upload jQuery UI plugin -->
            <script src="/fmanager/js/jquery.fileupload-jquery-ui.js"></script>
            <!-- The main application script -->
            <script
                    src="/fmanager/js/main.js.php?id=<?= $_GET['value2'] ?>&id_r=<?= $_GET['value'] ?>&type=2"></script>
            <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
            <!--[if (gte IE 8)&(lt IE 10)]>
            <script src="/fmanager/js/cors/jquery.xdr-transport.js"></script>
            <![endif]-->


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