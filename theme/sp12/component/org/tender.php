<?defined('_JEXEC') or die('Restricted access');?>
<div class="menu-top5">Заявка на вступление в группу Организаторы</div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>
<?if ($user->get_property('userID')>0 and $user->get_property('gid')==18):
if(empty($oke)):
?>

<ul>
<li>Прежде чем подать заявку в группу "Организаторы" ознакомьтесь с нашими <a href="/doc/page/rules.html" class="link4">правилами</a> и 
<a href="/doc/page/about.html" class="link4">основными понятиями</a>, прочтите раздел <a href="/com/help" class="link4">Помощь</a> и наш <a href="/forum" class="link4">Форум</a>.</li>
<li><b>Внимание!</b> После отправки заявки, Вам необходимо отправить письмо на адрес эл. почты <b>myspshop@yandex.ru</b>, с указанием ника и приложенными сканами основных страниц своего паспорта.</li>
<!--<li><b>Внимание!</b> После отправки заявки, Вам необходимо отправить письмо на адрес эл. почты <b>support@<?=$domain?></b>, с указанием ника и приложенными сканами основных страниц своего паспорта.</li>-->
</ul>
<h1>Форма заявки:</h1>

<script type="text/javascript">
            $(document).ready(function(){
                $.post("/cities.php", {},
                    function (xml) {
                        $(xml).find('country').each(function() {
                            id = $(this).find('id_country').text();
                            $("#country").append("<option value='" + id + "'>" + $(this).find('country_name_ru').text() + "</option>");
                    });
                });
                $("#country").change(function() {
                    id_country = $("#country option:selected").val();
                    if (id_country == "") {
                        $(".region, .city, #submit").hide();
                    }else {
                        $("#region").html('');
                        $("#region").html('<option value="">Выберите регион</option>');
                        $.post("/cities.php", {id_country: id_country},
                            function (xml) {
                                $(xml).find('region').each(function() {
                                id = $(this).find('id_region').text();
                                $("#region").append("<option value='" + id + "'>" + $(this).find('region_name_ru').text() + "</option>");
                            });
                        });
                        $(".region").show();
                    }
                });
                $("#region").change(function() {
                    id_region = $("#region option:selected").val();
                    if (id_region == "") {
                        $(".city").hide();
                    }else {
                        $("#city").html('');
                        $("#city").html('<option value="">Выберите город</option>');
                        $.post("/cities.php", {id_region: id_region},
                            function (xml) {
                                $(xml).find('city').each(function() {
                                id = $(this).find('id_city').text();
                                $("#city").append("<option value='" + id + "'>" + $(this).find('city_name_ru').text() + "</option>");
                            });
                        });
                    }
                    $(".city").show();
                });
                $("#city").change(function() {
                    if ($("#city option:selected").val() == "") {
                        $("#submitc").hide();
                    }else {
                        $("#submitc").show();
                    }
                });                

            });
</script>
<style>
.region, .city, #submitc {display:none;}
</style>

<form action="" method="post" align="center">
<table><tr><td width="200" valign="top">Ваш город:</td><td>
<select name="country" id="country" class="inputbox">
	<option value="">Страна</option>
</select><br/>
<select name="region" id="region" class="region inputbox">
	<option value="">Регион</option>
</select><br/>
<select name="city" id="city" class="city inputbox">
	<option value="">Город</option>
</select>
<tr><td width="150" valign="top">Ф.И.О.: <small style="color:red">*</small></td><td>
	<input type="text" name="name" value="<?=$_POST['name']?>" class="inputbox">
	</td></tr>

<tr><td width="150" valign="top">Имеете ли вы опыт работы Организатором?</td><td>
	<select name="opyt" id="opyt" class="inputbox">
		<option value="0" <?if($_POST['opyt']==0):?>selected<?endif?>>Нет</option>
		<option value="1" <?if($_POST['opyt']==1):?>selected<?endif?>>Да</option>
	</select>
	</td></tr>
<tr><td width="150" valign="top">Имеете ли вы своих поставщиков?</td><td>
	<select name="post" id="post" class="inputbox">
		<option value="0" <?if($_POST['post']==0):?>selected<?endif?>>Нет</option>
		<option value="1" <?if($_POST['post']==1):?>selected<?endif?>>Да</option>
	</select>
	</td></tr>
<tr><td width="150" valign="top">Перечислите сайты поставщиков (если есть):</td><td>
	<textarea name="site" name="site" class="inputbox" style="height:100px;"><?=$_POST['site']?></textarea>
	</td></tr>
<tr><td width="150" valign="top">Номер моб. телефона в федеральном формате: <small style="color:red">*</small><br/><small style="color:red">(будет выслана СМС с кодом подтверждения)</small></td><td>
	<input type="text" name="phone" value="<?if(empty($_POST['phone'])):?>+7<?else:?><?=$_POST['phone']?><?endif;?>" class="inputbox">
	</td></tr>
</table>
<script language="JavaScript" type="text/javascript">
function testch()
{
if (!document.getElementById('rules1').checked || !document.getElementById('rules2').checked)
	{alert("Вы должны согласится с правилами сервиса!");return false;}
return true;
}
</script>
<input type="checkbox" name="rules1" value="1" <?if($_POST['rules1']==1):?>checked<?endif?>> С <a href="/doc/page/rules.html" class="link4">правилами</a> сайта и закупок согласен.<br/>
<input type="checkbox" name="rules2" value="1" <?if($_POST['rules2']==1):?>checked<?endif?>> С <a href="/doc/page/rulesorg.html" class="link4">условиями</a> перехода в группу "Организаторы" согласен.
<input type="hidden" name="tender" value="1" />
<p><input type="submit" id="submitc" value="Отправить заявку" style="width:160px" onclick="return testch()" class="button"/></p>
</form>
<ul>
<?if($testcount>0):?><li>Вы уже подовали заявку. <?=$testcount?> раз(а).</li><?endif?>
<li>Вы не можете подавать заявку более чем 3 раза.</li>
<li>Поля отмеченные звездочкой (<small style="color:red">*</small>) обязательны для заполнения.</li>
</ul>
<?elseif($oke==1):?>
<form action="" method="post" align="center">
	<p>На указанный вами номер мобильного телефона была отправлена СМС с проверочным кодом.</p>
	<table><tr><td>Введите проверочный код:</td><td>
	<input type="hidden" name="tender" value="2" />
	<input type="text" name="code" value="" class="inputbox">
	</td></table>
	<input type="submit" id="submitc" value="Далее" class="button"/>
</form>
<h1>Информация:</h1>
<ul>
<li>СМС должна придти в течении 5 минут</li>
<li>В случаи возникновения сложностей свяжитесь с поддержкой сайта, используя <a href="/com/contacts/" class="link4">обратную связь</a></li>
</ul>
<?elseif($oke==2):?>
	<p>Спасибо! Ваша заявка принята к расмотрению. Мы оповестим вас о нашем решении по эл. почте или СМС.</p>
	<p><a href="/" class="link4">...На главную</a></p>
<?endif;?>


<?else:?>
	<p>Вы не можете подать заявку в группу Организаторы.</p>
<?endif;?>
</div>
