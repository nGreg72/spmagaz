<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('gid')>=23):?>
<!-- Load jQuery -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("jquery", "1");
</script>

<!-- Load TinyMCE -->
<script type="text/javascript" 
src="/<?=$theme?>js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '/<?=$theme?>js/tiny_mce/tiny_mce.js',
			relative_urls : false,
mode : "exact",
convert_urls : false,
remove_script_host : false,
force_br_newlines : true,
force_p_newlines : false,

			// General options
			theme : "advanced",language:"ru",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,images,advlist",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,images,cleanup,code,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl",

			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
</script>
<!-- /TinyMCE -->
<div class="menu-top5">Организаторская: Добавить несколько рядов</div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>
	<form action="" method="post" enctype="multipart/form-data" id="sendeform" name="sendeform">
	<input type="hidden" name="action" value="addr_multi">
	<input type="hidden" name="idpost" value="<?=intval($_GET['value'])?>">
	<table summary="">
		<tr>
<!--			<td valign="top" width="100"><b>Название</b></td>
			<td><input type="text" class="inputbox" style="width:400px;" name="title"  value="<?=$_POST['title'];?>">
			Именно это будет в корзине у участника. Пишите так чтобы ему было понятно!
			</td>
		</tr>
		<tr>
			<td valign="top">Артикул</td>
			<td><input type="text" class="inputbox" style="width:400px;" name="articul"  value="<?=$_POST['articul'];?>">
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Описание</b></td>
			<td>
		<textarea style="width:500px;height:350px" class="tinymce" name="textarea1" id="textarea1"><?=$_POST['textarea1']?></textarea>
			</td>
		</tr>
		<tr>
			<td class="title option"><b>Цена</b></td>
			<td><input type="text" class="inputbox" name="price" value="<?=$_POST['price'];?>"> р.</td>
		</tr>
		<tr>
			<td valign="top"><b>Размеры или количество</b></td>
			<td><input type="text" class="inputbox" style="width:400px;" name="size"  value="<?=$_POST['size'];?>"><br/>
			<small>Пример размеров: 40,41,41,42<br/>
			Пример количества: 3</br>
			Диапазон размеров: 1-10 (только цифры)</small>
			</td>
		</tr>-->
		<tr>
			<td class="title option">Мультизагрузка фото товара</td>
			<td>
			<input class="inputbox" style="width: 500px;" type="file" required multiple name="photo[]">
			</td>
		</tr>
		<!--<tr>
			<td class="title option"></td>
			<td>
			<input type="checkbox" name="auto" class="notify"  value="1">
			дублировать линейку размеров при наполнении автоматически
			</td>
		</tr>
		<tr>
			<td class="title option">Категория</td>
			<td><input type="text" class="inputbox" name="cat" value="<?=$_POST['cat'];?>" title="разбивка товаров на категории внутри закупки"> (в нутри закупки будет создана данная категория, для товаров)</td>
		</tr>
		</table><br/>-->

		<table border="0" width="100%">
		<tr class="last">
			<td>
			<a class="link4" href="/com/org/open/<?=intval($_GET['value'])?>"><< Вернуться в закупку</a>
			</td>
			<td align="right">
				<input type="submit" class="button" value="Сохранить">
			</td>
		</tr>
	</table>
	</form>
</div>
<?else:?>
<?@include('.access.php');?>
<?endif?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter23071057 = new Ya.Metrika({id:23071057,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/23071057" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->