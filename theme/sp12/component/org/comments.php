<? defined('_JEXEC') or die('Restricted access'); ?>


<script
        src="/<?= $theme ?>js/tinymce4/tinymce.min.js">
</script>
<script>

    tinymce.init
    ({
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
        fontsize_formats: "10pt 12pt 14pt 16pt",
        plugins: "autolink autoresize smileys textcolor link image lists charmap pagebreak preview",
        toolbar: "forecolor backcolor | smileys | link image pagebreak |  | fontselect | fontsizeselect",
        contextmenu: "link image insertable | cell row column deletable",
        pagebreak_separator: "<!-- my page break -->",
        image_advtab: true,
        relative_urls: false, remove_script_host: true,
    });
</script>

<h2 id="com-start">Комментарии к текущей закупке:
    <? if ($user->get_property('userID') > 0): ?>
        <a href="/com/org/subs/<?= $_GET['value'] ?>/<?= $_GET['value2'] ?>" class="link1" style="margin-right: 20px;">
            (<? if ($testSubs > 0): ?>отписаться от комментариев<? else: ?>подписаться на комментарии<? endif ?>)
        </a>
    <? endif ?>
</h2>
<? foreach ($all_comments as $comm): ?>
    <div class="comm-body" id="m<?= $comm['id'] ?>">
        <? if ($comm['user'] > 0): ?>
            <div><a class="comm-user" href="/com/profile/default/<?= $comm['user'] ?>/"><?= $comm['username'] ?></a></div>
        <? endif; ?>
        <div class="comm-date"><?= date('d.m.Y, H:i', $comm['date']) ?></div>
        <? if ($user->get_property('gid') == 25): ?>
            <div>
                <? /*<a href="?component=category&section=edit&edit=<?=$ca['id']?>"><img src="images/edit.png" width="16" height="16" border="0" alt="edit" title="редактировать"/></a>*/ ?>
                <a href="/com/org/delcom/<?= intval($_GET['value']) ?>/<?= $comm['id'] ?>" onclick="return confirm_delete_comment(this)">
                    <img src="/<?= $theme ?>images/Remove_order.png" alt="del" class="com-del" title="удалить"/></a>
            </div>
        <? endif ?>
        <div class="comMessage"><b><?= html_entity_decode($comm['message']) ?></b></div>
<!--        <?/* if ($user->get_property('gid') > 0): */?>
            <a href="javascript://" class="link7 reply" id="r<?/*= $comm['id'] */?>" rel="<?/*= $comm['id'] */?>">Ответить</a>
        --><?/* endif */?>
    </div>
<? endforeach; ?>
<div class="message"><?= $message ?></div>  <!--сообщения об ошибках-->


<form action="" method="post" id="comments" name="comments" onSubmit="return checkForm()">
    <input type="hidden" name="add-comm" value="1"/>
        <? if ($user->get_property('userID') > 0): ?>
        <input type="hidden" name="login" value="1"/>

<!--        <input type="hidden" name="login" value="0"/>
        Имя: <span>*</span><br/>
        <input type="text" name="name" value="" class="inputbox"/><br/>
        Эл. почта: <span>*</span><br/>
        <input type="text" name="email" value="" class="inputbox"/><br/>
        Веб-сайт:<br/>
        <input type="text" name="web" value="" class="inputbox"/><br/>-->

    Текст комментария: <br/>
    <textarea id="textarea1" class="tinymce" name="message" class="comm-area"></textarea><br/>

<!--    <?/* if ($user->get_property('userID') == 0 or $capcha_mess): */?>
        <img src="/lib/capcha.php" alt="картинка" width="120" height="50"/><br/>
        Введите код с картинки: <span>*</span><br/>
        <input type="text" name="capcha" value="" class="inputbox"/><br/>
    --><?/* endif; */?>

    <!--		Google reCaptcha с клиенским ключом		-->
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <div class="g-recaptcha" style="margin-left: 24%" data-sitekey="6LfdYiMUAAAAAIsIJB02mh32S7Y34VF-f6_vs67q"></div>

    <input type="submit" class="btn" value="Комментировать" style="width:150px;"/>
    <? endif; ?>
</form>

<script src="/<?=$theme?>js/sweetalert2/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" type="text/css" href="/<?=$theme?>js/sweetalert2/dist/sweetalert2.css">
<script type="text/javascript">
    function confirm_delete_comment(ln) {
        var link = ln.href; // Получаем значение тега href
        var text = " Отмена невозможна!";
        swal({
            title: 'Вы действительно хотите удалить комментарий, а ?', // Заголовок окна
            type: "question", // Тип окна
            text: text,
            showCancelButton: true, // Показывать кнопку отмены
        }).then(function () {
            window.location.href = link;
        })
        return false;
    }
</script>

<script type="text/javascript">

</script>