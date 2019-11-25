<?php
/*
Разработка: www.spmagaz.com
*/

/*    function set_tz_by_offset($offset) {
        $offset = $offset*60*60;
        $abbrarray = timezone_abbreviations_list();
        foreach ($abbrarray as $abbr) {
            //echo $abbr."<br>";
                foreach ($abbr as $city) {
                    //echo $city['offset']." $offset<br>";
                        if ($city['offset'] == $offset) { // remember to multiply $offset by -1 if you're getting it from js
                               date_default_timezone_set($city['timezone_id']);
                               return true;
                        }
                }
        }
    date_default_timezone_set("ust");
       return false;
       }

set_tz_by_offset(10);*/
date_default_timezone_set("Asia/Yakutsk");
// Report simple running errors
define( '_JEXEC', 1 );
error_reporting(0);
	$settings = [
	  'dbName' => 'u493783',
	  'dbUser' => 'root',
	  'dbPass' => '',
	  'dbHost' => '127.0.0.1'
    ]; // Настройки подключения к БД
	

	$domain = 'spmagaz.loc';
	$email = 'ngreg72@gmail.com';
	$theme='theme/sp12/'; // выбираем тему оформления и тип ядра
	$theme_admin='theme/'; // выбираем тему оформления для admin
	$root_path='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$err=0;
	$activated=0; // 0 - подтверждение по емаил и активация, 1 - без активации

	$max_image_width	= 120; // максимальная ширина загружаемого изображения
	$max_image_height	= 120; // максимальная высота загружаемого изображения
	$maxkbsize		= 900; // максимальный пазмер в KB
	$max_image_size		= $maxkbsize * 1024;
	$valid_types 		=  array("gif", "GIF", "jpg", "JPG", "png", "PNG", "jpeg", "JPEG");

	$avator_height_profile	= 120;
	$avator_width_profile	= 120;
	$avator_sc_profile	= 1;

	$avator_height_sport	= 30;
	$avator_width_sport	= 30;
	$avator_sc_sport	= 1;

	$sport_width_kontors	= 14; // размеры пиктограммы вида спорта
	$sport_height_kontors	= 14;
	$sport_sc_kontors	= 1;

	$avator_height_kontors	= 120;
	$avator_width_kontors	= 120;
	$avator_sc_kontors	= 1;
	
	$timer_generate		= true; // отображения времени генерации страницы
	$other_internal		= FALSE; // использовать другой шаблон для внутренних страниц 
	$system_query_cache	= true; // кэширование SQL запросов
	$antiddos		= FALSE; // antiddos защита системы, может работать в разных режимах
	$PrivateAccess		= false; // ограниченный доступ к сайту, только зарегистрированные пользователи
	$capcha_mess		= true;// использовать капчу в личных сообщениях
	$corect_dey		= date('d'); // отображение прогнозов на N дней раньше

	$licenseKey		= '8c0f93c6f234a00984ecd509db6f5a3fd6600066c2d02696d22f324adcf8279b';

	// массив настроек для заливки фото закупки
	$setimg1['max_image_width'] 	= 4092;
	$setimg1['max_image_height']	= 4092;
	$setimg1['maxkbsize']		= 8000;
	$setimg1['max_image_size']      = $setimg1['maxkbsize'] * 1024;
	$setimg1['valid_types']		= array("gif", "GIF", "jpg", "JPG", "png", "PNG", "jpeg", "JPEG");

	$setfile['maxkbsize']		= 30000;
	$setfile['max_image_size']      = $setimg1['maxkbsize'] * 1024;
	$setfile['valid_types']		= array('doc','docx','rar','zip','csv','xls','xlsx');

	$registry['valut_name']	= 'руб.'; // минималка валюта
	$registry['qnt_name']	= 'штук.'; // минималка количество
	$registry['weight_name']	= 'кг.'; // минималка вес

	$registry['sms_from']		= "admin-spmagaz";// websms.ru имя отправителя, также его надо указать на вебсмс
	$registry['sms_login']		= "admin_SpYkt"; // websms.ru логин
	$registry['sms_pass']		= "vfccjdfZHfccskrf"; // websms.ru пароль


	$MONETA_RU_MNT_ID = '';  // ID кошелька монета.ру
	$MONETA_RU_MNT_SECRET = ''; // SECRET Key монета.ру


	$ROBO_mrh_pass1 = "";  // секретный пароль робокасса 1
	$ROBO_mrh_pass2 = "";  // секретный пароль робокасса 1
	$ROBO_mrh_login = "";  // Идентификатор магазина


	$registry['type_payment']	= "robo"; // Тип билинга, для приема платежей - robo или moneta



	$loginza_wid=72579;
	$loginza_skey='2e1a26065a04385aeb2c6d1fc87df30d';
?>