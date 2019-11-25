<?
function add_pr ($str,$count)
	{
//	$st=preg_replace("/([.,;-])/","\${".$count."}?",$str);
	$str=iconv('UTF-8','WINDOWS-1251',$str);
	$i = 0;$no_pr = 0;$j = 1;
	while ($i < strlen($str))
		{
		$text[$j] = $text[$j].$str[$i];
		if ($str[$i] == ' '){$no_pr = 0;$j = $j+1;}
		if ($str[$i] != ' '){$no_pr = $no_pr+1;}
		if ($no_pr == $count){$text[$j] = $text[$j].' ';$no_pr = 0;}
		$i = $i+1;
		}
	while ($j != 0){$st = $st.$text[$j];$j = $j-1;}
	$st=iconv('WINDOWS-1251','UTF-8',$st);
	return $st;
	}

function PHP_slashes($string,$type='add')
{
    if ($type == 'add')
    {
        if (get_magic_quotes_gpc())
        {
            return $string;
        }
        else
        {
            if (function_exists('addslashes'))
            {
                return addslashes($string);
            }
            else
            {
                return mysql_real_escape_string($string);
            }
        }
    }
    else if ($type == 'strip')
    {
        return stripslashes($string);
    }
    else
    {
        die('error in PHP_slashes (mixed,add | strip)');
    }
}
if(!function_exists('utf8_strlen'))
	{
	function utf8_strlen($s)
		{
		return preg_match_all('/./u', $s, $tmp);
		}
	}

if(!function_exists('utf8_substr'))
	{
	function utf8_substr($s, $offset, $len = 'all')
		{
		if ($offset<0) $offset = utf8_strlen($s) + $offset;
		if ($len!='all')
			{
			if ($len<0) $len = utf8_strlen2($s) - $offset + $len;
			$xlen = utf8_strlen($s) - $offset;
			$len = ($len>$xlen) ? $xlen : $len;
			preg_match('/^.{' . $offset . '}(.{0,'.$len.'})/us', $s, $tmp);
			}
			else
			{
			preg_match('/^.{' . $offset . '}(.*)/us', $s, $tmp);
			}
		return (isset($tmp[1])) ? $tmp[1] : false;
		}
	}
if(!function_exists('utf8_strpos'))
	{
function utf8_strpos($str, $needle, $offset = null)
      {
          if (is_null($offset))
          {
              return mb_strpos($str, $needle);
          }
          else
          {
              return mb_strpos($str, $needle, $offset);
          }
      }
}
if(!function_exists('generate_chpu'))
	{
	function generate_chpu ($str)
		{
		//$str = iconv('windows-1251','utf-8',$str);
		$converter = array(
	        'а' => 'a',   'б' => 'b',   'в' => 'v',
	        'г' => 'g',   'д' => 'd',   'е' => 'e',
	        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
	        'и' => 'i',   'й' => 'y',   'к' => 'k',
	        'л' => 'l',   'м' => 'm',   'н' => 'n',
	        'о' => 'o',   'п' => 'p',   'р' => 'r',
	        'с' => 's',   'т' => 't',   'у' => 'u',
	        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
	        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
	        'ь' => '',  'ы' => 'y',   'ъ' => '',
	        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
	        'А' => 'A',   'Б' => 'B',   'В' => 'V',
	        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
	        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
	        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
	        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
	        'О' => 'O',   'П' => 'P',   'Р' => 'R',
	        'С' => 'S',   'Т' => 'T',   'У' => 'U',
	        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
	        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
	        'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
	        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);
		$str = strtr($str, $converter);
		$str = strtolower($str);
		$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
		$str = trim($str, "-");
		return $str;
		}
	}

function generate_password($number)

  {

    $arr = array('a','b','c','d','e','f',

                 'g','h','i','j','k','l',

                 'm','n','o','p','r','s',

                 't','u','v','x','y','z',

                 'A','B','C','D','E','F',

                 'G','H','I','J','K','L',

                 'M','N','O','P','R','S',

                 'T','U','V','X','Y','Z',

                 '1','2','3','4','5','6',

                 '7','8','9','0'); /*,'.',',',

                 '(',')','[',']','!','?',

                 '&','^','%','@','*','$',

                 '<','>','/','|','+','-',

                 '{','}','`','~');*/

    $pass = "";

    for($i = 0; $i < $number; $i++)

    {
      $index = rand(0, count($arr) - 1);

      $pass .= $arr[$index];

    }

    return $pass;

  }

function genpass($number, $param = 1)
  {
    $arr = array('a','b','c','d','e','f',
                 'g','h','i','j','k','l',
                 'm','n','o','p','r','s',
                 't','u','v','x','y','z',
                 'A','B','C','D','E','F',
                 'G','H','I','J','K','L',
                 'M','N','O','P','R','S',
                 'T','U','V','X','Y','Z',
                 '1','2','3','4','5','6',
                 '7','8','9','0','.',',',
                 '(',')','[',']','!','?',
                 '&','^','%','@','*','$',
                 '<','>','/','|','+','-',
                 '{','}','`','~');

    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
      if ($param>count($arr)-1)$param=count($arr) - 1;
      if ($param==1) $param=48;
      if ($param==2) $param=58;
      if ($param==3) $param=count($arr) - 1;

      $index = rand(0, $param);
      $pass .= $arr[$index];
    }
    return $pass;
  }
function online_check () {
	global $DB,$user;
	if($user->get_property('userID')>0)
		{
		$serv=serialize($_SERVER);
		$sql="UPDATE `punbb_users` SET `last_visit` = '".time()."', `data_serv` = '$serv' WHERE `id` ='".$user->get_property('userID')."' LIMIT 1 ;";
		$DB->execute($sql);
               	}
		else
		{
		if(intval($_COOKIE['ses_id'])>0)
			{
			$sql="UPDATE `session` SET `date` = '".time()."' WHERE `id` ='".intval($_COOKIE['ses_id'])."' LIMIT 1 ;";
			$DB->execute($sql);
			}
			else
			{
			$sql="INSERT INTO `session` (`date`) VALUE ('".time()."')";
			$DB->execute($sql);
			$sql="SELECT LAST_INSERT_ID()";
			$last_id=$DB->getOne($sql);
			setcookie('ses_id',"$last_id",(time()+60*15),'/');
			}
		}
}		

function generate_avatar_markup($user_id, $admin = false)
        {
	$filetypes = array('jpg', 'gif', 'png');
	$avatar_markup = '';

	foreach ($filetypes as $cur_type)
	{
		$path = 'forum/img/avatars/'.$user_id.'.'.$cur_type;$sl='/';
		if($admin){ $path = '../forum/img/avatars/'.$user_id.'.'.$cur_type;$sl='';}
		if (file_exists($path) && $img_size = @getimagesize($path))
		{
			$avatar_markup = '<img src="'.$sl.$path.'" '.$img_size[3].' class="photo" alt="" />';
			break;
		}
	}
	return $avatar_markup;
	}

function save_image_on_server($files, $upload_path, $settings='', $autogen='', $delete='') {
	if (is_uploaded_file($files['tmp_name'])) 
		{
		$filename = $files['tmp_name'];
		$ext = substr($files['name'], 
		1 + strrpos($files['name'], "."));
		if (filesize($filename) > $settings['max_image_size']) 
		{
		 return "Ошибка: Размер фото не может превышать: ".$settings['max_image_size']." Kb";
		} elseif (!in_array($ext, $settings['valid_types'])) 
			{
			return "Ошибка: Данный формат фото не поддерживается. <p>Выберите для загрузки фото в формате: GIF, JPG, PNG</p>";
			} else 
			{

 			$size = GetImageSize($filename);

			if(!empty($delete))@unlink($delete);
			if($autogen==''):
				$newname=rand(100000,99999999);
				while (file_exists($upload_path.$newname.".$ext"))
					$newname=rand(100000,99999999);
			else: $newname=$autogen;
			endif;

                        $dest=$upload_path.$newname.".$ext";

 			if (($size) && ($size[0] < $settings['max_image_width']) 
			&& ($size[1] < $settings['max_image_height'])) {

				if (!is_dir($upload_path)) {@mkdir($upload_path, 0777, true);}
				if (@move_uploaded_file($filename, $dest)) {
				$status[0]="Изображение успешно загружено";
				$status[1]=$dest;
				return $status;
			} else {
				return 'Ошибка: неудалось загрузить фото на сервер.';
				}
			} else {
				//return "Ошибка: Разрешение фото не может превышать: ".$settings['max_image_width']." x ".$settings['max_image_height'];
				img_resize($filename, $dest, $settings['max_image_width'], $settings['max_image_height']);

				$status[0]="Изображение успешно загружено";
				$status[1]=$dest;
				return $status;
				}
			}
		} else return "Ошибка: Файл не загружен";
	}


	function img_resize($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 100) {
	    if (!file_exists($src)) {  
	        return false;  
	    }  
	    $size = getimagesize($src);  

	    if ($size === false) {  
	        return false;  
	    }  

	    $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));  
	    $icfunc = 'imagecreatefrom'.$format;  
	    if (!function_exists($icfunc)) {  
	        return false;  
	    }  

	    if($width==0)if($size[0]<=800)$width=$size[0];else $width=800;
  
	    $x_ratio = $width  / $size[0];  
	    $y_ratio = $height / $size[1];  
  
	    if ($height == 0) {  
  
	        $y_ratio = $x_ratio;  
	        $height  = $y_ratio * $size[1];  
  
	    } elseif ($width == 0) {  
  
	        $x_ratio = $y_ratio;  
	        $width   = $x_ratio * $size[0];  
  
	    }  
  
	    $ratio       = min($x_ratio, $y_ratio);  
	    $use_x_ratio = ($x_ratio == $ratio);  
  
	    $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);  
	    $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);  
	    $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width)   / 2);  
	    $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);  
  
	    $isrc  = $icfunc($src);  
	    //$water = imagecreatefrompng("watermark.png");
	    //$isrc = $this->create_watermark($isrc,$water,50);

	    $idest = imagecreatetruecolor($width, $height);  

	    imagefill($idest, 0, 0, $rgb);  

	    imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);  

	    //header("Content-type: application/zip");  
	    //header("Content-type: image/jpeg");
	    imagejpeg($idest, $dest, $quality);
  
	    imagedestroy($isrc);  
	    imagedestroy($idest);  
  
	    return true;  
}  

function myscandir($dir)
{
    $list = scandir($dir);
    unset($list[0],$list[1]);
    return array_values($list);
}

function getLicense() {
	global $licenseKey;
	if($_GET['license']) {echo $licenseKey;exit;}
}
function clear_dir($dir)
{
    $list = myscandir($dir);
    
    foreach ($list as $file)
    {
        if (is_dir($dir.$file))
        {
            clear_dir($dir.$file.'/');
            rmdir($dir.$file);
        }
        else
        {
            unlink($dir.$file);
        }
    }
}


function save_file_on_server($files, $upload_path, $settings, $autogen='', $delete='') {
	if (is_uploaded_file($files['tmp_name'])) 
		{
		$filename = $files['tmp_name'];
		$ext = substr($files['name'], 
		1 + strrpos($files['name'], "."));
		if (filesize($filename) > $settings['max_image_size']) 
		{
		 return "Ошибка: Размер файла не может превышать: ".$settings['max_image_size']." Kb";
		} elseif (!in_array(strtolower($ext), $settings['valid_types'])) 
			{
			return "Ошибка: Данный формат файла не поддерживается. <p>Выберите для загрузки файл в формате: 'doc','docx','rar','zip','scv','xls','xlsx'</p>";
			} else 
			{

			if(!empty($delete))@unlink($delete);
			if($autogen==''):
				$newname=rand(100000,99999999);
				while (file_exists($upload_path.$newname.".$ext"))
					$newname=rand(100000,99999999);
			else: $newname=$autogen;
			endif;
			if (!is_dir($upload_path)) {@mkdir($upload_path, 0777, true);}
			if (@move_uploaded_file($filename, $upload_path.$newname.".$ext")) {
			$path=$upload_path.$newname.".$ext";
			$status[0]="Файл успешно загружен";
			$status[1]=$path;
			return $status;
			} else {
				return 'Ошибка: неудалось загрузить файл на сервер.';
				}
			}
		} else return "Ошибка: Файл не загружен";
	}



function loginza_json_support () {
	if ( function_exists('json_decode') ) {
		return true;
	}
	// загружаем библиотеку работы с JSON если она необходима
	if (!class_exists('Services_JSON')) {
		include_once('../lib/JSON.php' );
	}
	return false;
}

// если нету поддержки json
if ( !loginza_json_support() ) {
	// декодирует json в объект/массив
	function json_decode($data) {
        $json = new Services_JSON();
        return $json->decode($data);
    }
}

function loginza_api_request($url) {
	if ( function_exists('curl_init') ) {
		$curl = curl_init($url);
		$user_agent = 'Loginza-API/Wordpress';
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$raw_data = curl_exec($curl);
		curl_close($curl);
		return $raw_data;
	} else {
		return file_get_contents($url);
	}
}

function tolink($buf) {
    $buf = preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" target=\"_blank\" >$3</a>", $buf);
    $buf = preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" target=\"_blank\" >$3</a>", $buf);
    return($buf);
}