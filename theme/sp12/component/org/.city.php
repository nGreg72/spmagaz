<?defined('_JEXEC') or die('Restricted access');?>
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
<p>Уважаемый организатор, прежде чем создавать и открывать свои закупки вы должны закрепиться за городом и регионом. При этом вы сможете работать с клиентами из любой точки мира. Территориальное закрепление нужно для удобства поиска пользователем ближайшего организатора.</p>
<form action="" method="post" align="center">
<p><select name="country" id="country" class="inputbox">
	<option value="">Страна</option>
</select></p>
<p><select name="region" id="region" class="region inputbox">
	<option value="">Регион</option>
</select></p>
<p><select name="city" id="city" class="city inputbox">
	<option value="">Город</option>
</select></p>
<input type="hidden" name="selcity" value="1" />
<p><input type="submit" id="submitc" value="Выбрать" class="button"/></p>
</form>