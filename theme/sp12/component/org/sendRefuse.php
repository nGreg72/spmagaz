<?php defined('_JEXEC') or die('Restricted access');?>

<?

$sql="SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`curs`,`cities`.`city_name_ru`,
			`sp_zakup`.`proc`,`sp_zakup`.`user`,`sp_zakup`.`type`,`sp_zakup`.`dost`,`sp_zakup`.`status`
		FROM `sp_zakup`
		LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
		JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
		WHERE `sp_zakup`.`id`=".intval($_GET['value']);
	  $openzakup=$DB->getAll($sql);
?>

<div class="menu-top5">Рассылка сообщений участникам закупки <span style="color:red;">"<?=$openzakup[0]['title'];?>"</span> со статусом "Отказано!"  </div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>


   
<script 
	src="/<?=$theme?>js/tinymce4/tinymce.min.js">
</script>

<script>
        
	tinymce.init
({
	selector:"#textarea1", language:"ru",
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
	fontsize_formats : "8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt",
	plugins : "autolink autoresize contextmenu smileys textcolor link image jbimages lists charmap pagebreak preview paste code",
	toolbar : "undo redo | forecolor backcolor smileys link image pagebreak | copy paste | fontselect | fontsizeselect",
	contextmenu : "link image insertable | cell row column deletable",
	pagebreak_separator: "<!-- my page break -->",
	image_advtab: true,
	relative_urls: false, remove_script_host: true,
	
// KCFinder	start
	file_browser_callback: function(field, url, type, win) {
        tinyMCE.activeEditor.windowManager.open({
            file: '/<?=$theme?>/js/kcfinder3/browse.php?opener=tinymce4&field=' + field + '&type=' + type,
            title: 'KCFinder',
            width: 700,
            height: 500,
            inline: true,
            close_previous: false
        }, {
            window: win,
            input: field
        });
        return false;
    }
//KCFinder end	
	});
</script>

<form method="post" action="" enctype="multipart/form-data">
	<input name="sendrefuse" value="1" type="hidden"/>
	<table class="formadd">
	<tbody>
		<tr><td class="td1">Тема сообщения</td><td><input name="subject" value='Отказ в закупке "<?=$openzakup[0]['title'];?>"' class="inputbox" type="text"/></td></tr>
	</tbody></table>

<div style="padding:10px 5px">
	<span class="title-table">Текст сообщения</span><br/>
	<textarea class="tinymce" id="textarea1" name="text" style="width:500px;height:400px;">Драсьте... Вы чо там поназаказывали? Совсем, чо ли уже?</textarea>
	<br/>
			<table border="0" width="100%">
				<tr class="last">
					<td>
						<a class="link4" href="/com/org/open/<?=$_GET['value']?>">Ввернуться</a>
					</td>
					<td align="right">
						<input type="submit" class="button" value="Разосрать">
					</td>
				</tr>
			</table>

	<br/>
</div>
</form>
</div>