<?php
/*
 * Скрипт выбора города по региону и стране.
 * © Анников Яков, 2010
 * www.usualblog.ru
 */
require_once('config.php');

header('Content-type: text/xml; charset=windows-1251');

$db_host = $settings['dbHost'];
$db_user = $settings['dbUser'];
$db_pass = $settings['dbPass'];
$db_name = $settings['dbName'];

  function get_countries() {
$xml = '<?xml version="1.0" encoding="windows-1251"?><root>';
    $mysql_result = mysql_query("SELECT * FROM countries ORDER BY country_order");
    while($country = mysql_fetch_assoc($mysql_result)) {
      $xml .= '<country>';
      $xml .= '<id_country>'.$country['id_country'].'</id_country>';
      $xml .= '<country_name_ru><![CDATA['.$country['country_name_ru'].']]></country_name_ru>';
      $xml .= '<country_name_en><![CDATA['.$country['country_name_en'].']]></country_name_en>';
      $xml .= '<iso>'.$country['country_iso'].'</iso>';
      $xml .= '</country>';
    }
    $xml .= '</root>';
    echo $xml;
  }
 
  function get_regions($id_country) {
$xml = '<?xml version="1.0" encoding="windows-1251"?><root>';
    $mysql_result = mysql_query("SELECT * FROM regions WHERE id_country = ".intval($id_country)." ORDER BY region_order");
    while($region = mysql_fetch_assoc($mysql_result)) {
      $xml .= '<region>';
      $xml .= '<id_region>'.$region['id_region'].'</id_region>';
      $xml .= '<region_name_ru><![CDATA['.$region['region_name_ru'].']]></region_name_ru>';
      $xml .= '<region_name_en><![CDATA['.$region['region_name_en'].']]></region_name_en>';
      $xml .= '</region>';
    }
    $xml .= '</root>';
    echo $xml;
  }
 
  function get_cities($id_region) {
$xml = '<?xml version="1.0" encoding="windows-1251"?><root>';
    $mysql_result = mysql_query("SELECT * FROM cities WHERE id_region = ".intval($id_region)." ORDER BY city_order");
    while($city = mysql_fetch_assoc($mysql_result)) {
      $xml .= '<city>';
      $xml .= '<id_city>'.$city['id_city'].'</id_city>';
      $xml .= '<city_name_ru><![CDATA['.$city['city_name_ru'].']]></city_name_ru>';
      $xml .= '<city_name_en><![CDATA['.$city['city_name_en'].']]></city_name_en>';
      $xml .= '</city>';
    }
    $xml .= '</root>';
    echo $xml;
  }

MYSQL_CONNECT($db_host,$db_user,$db_pass) OR DIE("Не могу создать соединение с БД SQL<br/> ");
@mysql_select_db("$db_name") or die("Не могу выбрать базу данных <br/>"); 
mysql_query ("set character_set_client='cp1251'");
mysql_query ("set character_set_results='cp1251'");
mysql_query ("set collation_connection='cp1251_general_ci'"); 

if (isset($_POST['id_country'])) 
	{
	get_regions($_POST['id_country']);
	}
	elseif (isset($_POST['id_region'])) 
	{
	  get_cities($_POST['id_region']);
	}
	else 
	{
	  get_countries();
	}

/*
if (isset($_POST['id_region'])) 
	{
	  get_cities($_POST['id_region']);
	} else get_regions(1);*/

mysql_close(); 