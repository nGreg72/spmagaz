<?php

function loginExists ($login) {
  		return (get_userdatabylogin($login) != false);
	}
function generateLogin ($identity) {
		//$parts = parse_url($identity);
		//return self::nicknameToLogin ($parts['host'].$parts['path']);
		return 'loginza'.self::shotmd5($identity);
	}

function nicknameToLogin ($nickname) {
	$tran = array(
	'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ж'=>'g', 'з'=>'z',
	'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p',
	'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'ы'=>'i', 'э'=>'e', 'А'=>'A',
	'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Е'=>'E', 'Ж'=>'G', 'З'=>'Z', 'И'=>'I',
	'Й'=>'Y', 'К'=>'K', 'Л'=>'L', 'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R',
	'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Ы'=>'I', 'Э'=>'E', 'ё'=>"yo", 'х'=>"h",
	'ц'=>"ts", 'ч'=>"ch", 'ш'=>"sh", 'щ'=>"shch", 'ъ'=>"", 'ь'=>"", 'ю'=>"yu", 'я'=>"ya",
	'Ё'=>"YO", 'Х'=>"H", 'Ц'=>"TS", 'Ч'=>"CH", 'Ш'=>"SH", 'Щ'=>"SHCH", 'Ъ'=>"", 'Ь'=>"",
	'Ю'=>"YU", 'Я'=>"YA"
	);
	$nickname = strtr($nickname, $tran);
	$nickname = trim(preg_replace('/[^\w]+/i', '-', $nickname), '-');
	    return $nickname;
	}

function wp_insert_user($user_data) {
	global $DB;
	if(email_check($user_data['user_email']))$sql_email="`email` = '{$user_data['user_email']}' or";
	$sql="SELECT * FROM `punbb_users` WHERE  $sql_email
		`username` = '{$user_data['user_nicename']}' LIMIT 1";
	$udata=$DB->getAll($sql);
	if(count($udata)>0) {
		$sql="UPDATE `punbb_users` SET `display_name` = '{$user_data['display_name']}' WHERE $sql_email
			`username` = '{$user_data['user_nicename']}' LIMIT 1";
		$DB->execute($sql);

	        header("Location: /?uname={$user_data['user_nicename']}&pwd={$udata[0]['password']}&loginza=1");
		}
		else 
		{
		$salt=generate_password(6);
		$password=sha1($salt.sha1($user_data['user_pass']));
		$sql="INSERT INTO `punbb_users` (`username`,`group_id`,`password`,`salt`,`email`,`realname`,`user_url`,`display_name`)
		VALUES('{$user_data['user_nicename']}','3','{$password}','{$salt}','{$user_data['user_email']}',
		'{$user_data['first_name']} {$user_data['last_name']}','{$user_data['user_url']}','{$user_data['display_name']}')";

		$DB->execute($sql);
	        header("Location: /?uname={$user_data['user_nicename']}&pwd={$user_data['user_pass']}");
		exit;
//	        return $DB->id;
		}
	}

function create($profile) {
		$user_data = array();
		
		// Имя пользователя
//		$user_data['user_login'] = nicknameToLogin ($profile->nickname);

		// проверяем передан ли никнайм и его занятость
		/*if (!$user_data['user_login'] || loginExists ($user_data['user_login'])) {
			$user_data['user_login'] = generateLogin ($profile->identity);
		}
		// Ник
		if ($profile->nickname) {
			$user_data['user_nicename'] = $user_data['nickname'] = $profile->nickname;
		} elseif (!empty($profile->email) && preg_match('/^(.+)\@/i', $profile->email, $nickname)) {
			$user_data['user_nicename'] = $user_data['nickname'] = $nickname[1];
		} else {
		}*/

		$user_data['user_nicename'] = $user_data['nickname'] = nicknameToLogin ($profile->identity);
		// Сайт
		if (!empty($profile->web->blog)) {
			$user_data['user_url'] = $profile->web->blog;
		} elseif (!empty($profile->web->default)) {
			$user_data['user_url'] = $profile->web->default;
		} else {
			$user_data['user_url'] = $profile->identity;
		}
		// jabber
		if ($profile->im->jabber) {
			$user_data['jabber'] = $profile->im->jabber;
		}
		// description
		if ($profile->biography) {
			$user_data['description'] = $profile->biography;
		}
		// Отображать как
		if ($profile->name->full_name) {
			$user_data['display_name'] = $profile->name->full_name;
			$name_parts = explode(" ", $profile->name->full_name);
			// имя и фамилия по умолчанию
			$user_data['first_name'] = $name_parts[0];
			$user_data['last_name'] = $name_parts[1];
		} elseif ($profile->name->first_name || $profile->name->last_name) {
			$user_data['display_name'] = trim($profile->name->first_name.' '.$profile->name->last_name);
		} elseif (!empty($user_data['nickname'])) {
			$user_data['display_name'] = $user_data['nickname'];
		} else {
			$user_data['display_name'] = $profile->identity;
		}
		// Имя
		if ($profile->name->first_name) {
			$user_data['first_name'] = $profile->name->first_name;
		}
		// Фамилия
		if ($profile->name->last_name) {
			$user_data['last_name'] = $profile->name->last_name;
		}
		
		// остальные данные
		$user_data['user_pass'] = generate_password(10);
		$user_data['user_email'] = $profile->email;
		
		// создаем пользователя
		$wp_id = wp_insert_user($user_data);
		return $wp_id;
	}


if(isset($_POST['token'])) {
	$token=$_POST['token'];
	$sign=md5($token.$loginza_skey);
	$url="http://loginza.ru/api/authinfo?token=$token&id=$loginza_wid&sig=$sign";
	
	$data=loginza_api_request($url); // отправляем запрос на сервер логинзы
	$profile=json_decode($data);
	if(!is_object($profile) || !empty($profile->error_message) || !empty($profile->error_type))
		{ $message=$profile->error_message; }
		else 
		{
		create($profile);
		}
	}