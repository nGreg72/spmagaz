<? if ($user->get_property('gid') >= 18 and ($hvastics[0]['user'] == $user->get_property('userID') || $user->get_property('gid') == 25)): ?>
    <!-- Load jQuery -->
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("jquery", "1");
    </script>

    <!-- Load TinyMCE -->
    <script type="text/javascript" src="
/<?= $theme ?>js/tiny_mce/jquery.tinymce.js"></script>
    <script type="text/javascript">
        $().ready(function () {
            $('textarea.tinymce').tinymce({
                // Location of TinyMCE script
                script_url: '/<?=$theme?>js/tiny_mce/tiny_mce.js',
                relative_urls: false,
                mode: "exact",
                convert_urls: false,
                remove_script_host: false,
                force_br_newlines: true,
                force_p_newlines: false,
                // General options
                theme: "advanced", language: "ru",
                plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

                // Theme options
                theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,preview,|,forecolor,backcolor",
                theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl",

                theme_advanced_toolbar_location: "top",
                theme_advanced_toolbar_align: "left",
                theme_advanced_statusbar_location: "bottom",
                theme_advanced_resizing: true,

                // Example content CSS (should be your site CSS)
                content_css: "css/content.css",

                // Drop lists for link/image/media/template dialogs
                template_external_list_url: "lists/template_list.js",
                external_link_list_url: "lists/link_list.js",
                external_image_list_url: "lists/image_list.js",
                media_external_list_url: "lists/media_list.js",

                // Replace values for the template plugin
                template_replace_values: {
                    username: "Some User",
                    staffid: "991234"
                }
            });
        });
    </script>
    <!-- /TinyMCE -->

    <div class="menu-top5">Редактировать пристрой</div>
    <div class="menu-body5">
        <div style="display:block" class="message"><?= $message; ?></div>
        <form action="" method="post" enctype="multipart/form-data" id="sendeform" name="sendeform">
            <input type="hidden" name="edit" value="<?= $hvastics[0]['id'] ?>">
            <table summary="" style="border-spacing: 0 10px;">
                <tr>
                    <td style="vertical-align: top" width="100"><b>Название</b></td>
                    <td><label>
                            <input type="text" class="inputbox" style="width:400px;" name="title"
                                       value="<?= $hvastics[0]['title']; ?>">
                        </label>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top"><b>Описание</b></td>
                    <td>
                        <label for="textarea1"></label>
                        <textarea style="width:500px;height:350px" class="tinymce" name="textarea1"
                                                                 id="textarea1"><?= $hvastics[0]['text'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td><b>Категория</b></td>
                    <td>
                        <select name="cat" class="inputbox">
                            <option value="0" selected="selected"></option>
                            <? foreach ($cat_zp as $cat): ?>
                                <? foreach ($cat as $ca): ?>
                                    <? if ($ca['podcat'] == 0): ?>
                                        <optgroup label="<?= $ca['name'] ?>">
                                    <? else: ?>
                                        <option value="<?= $ca['id'] ?>"
                                                <? if ($hvastics[0]['cat'] == $ca['id']): ?>selected<? endif ?>> <?= $ca['name'] ?></option>
                                    <? endif; ?>
                                    </optgroup>
                                <? endforeach; ?>
                            <? endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><b>Цена</b></td>
                    <td><input type="text" class="inputbox" name="price"
                               value="<?= $hvastics[0]['price']; ?>"><?= $registry['valut_name'] ?>
                    </td>
                </tr>
                <tr>
                    <td>Размер</td>
                    <td><input type="text" class="inputbox" name="size" value="<?= $hvastics[0]['size']; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Количество</td>
                    <td><input type="text" class="inputbox" name="quantity" value="<?=$hvastics[0]['quantity'];?>">
                    </td>
                </tr>
                <tr>
                    <td>Причина пристроя</td>
                    <td><input type="text" class="inputbox" name="cause" value="<?= $hvastics[0]['cause']; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Фото<br/>
                        <? $pth = 'img/uploads/pristroy/' . $hvastics[0]['id'];
                        $extension = ['.jpg', '.jpeg', '.gif', '.png'];
                        $img_path = '';
                        foreach ($extension as $ext) {
                            if (file_exists($pth . $ext)) {
                                $img_path = '/images/pristroy/0/0/1/' . $hvastics[0]['id'] . $ext;
                                break;
                            }
                        }
                        ?>

<!--                        --><?// if ($hvastics[0]['photo']): ?><!--<img src="--><?//= $hvastics[0]['photo'] ?><!--"alt="" />--><?// endif ?>
                    </td>
                    <td>
                        <input type="file" class="inputbox" name="photo" value="<?=$img_path?>">
                    </td>
                </tr>

            </table>
            <br/>

            <table border="0" width="100%">
                <tr class="last">
                    <td>
                        <a class="link4" href="/com/pristroy/open/<?= $hvastics[0]['id'] ?>">Отмена</a>
                    </td>
                    <td align="right">
                        <input type="submit" class="button" value="Сохранить">
                    </td>
                </tr>
            </table>
        </form>
    </div>
<? else: ?>
    <? @include('.access.php'); ?>
<? endif ?>