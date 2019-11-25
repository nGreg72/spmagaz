<?
function parseString( $str , $val, $par) {
        if ($par==1)$str = str_replace('\\','',preg_replace("/['\"`!\\/@№;:?#$%^&*()_]/","",@strval($str))); // удаляет все кавычки вообще
        if ($val>=1)$str = trim( $str ); // удаляет пробельные символы вначале и в конце строки
        if ($val>=2)$str = str_replace(' ','',$str); // удаляет вообще все пробелы
        if ($val>=3)$str = preg_replace("/[^\x20-\xFF]/","",@strval($str)); // удаляет непечатаемые, опасные символы
        if ($val>=4)$str = strip_tags( $str ); // удаляет все html тэги
        if ($val>=5)$str = htmlspecialchars( $str, ENT_QUOTES ); // все специальные символы типа кавычек и т.п. перекодируются в вид html сущностей типа "<" и др
        if ($val>=6)$str = mysql_real_escape_string( $str ); // выполняется экранирование строки для sql запроса специальной функцией
        return $str;
}
function email_check($email) {
	if (!preg_match("/^(?:[a-z0-9_.-]+(?:[-_.]?[a-z0-9_.-]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i",trim($email)))
		{
		 return false;
		}
		else return true;
	}
if (isset($_GET['component']))$component=parseString($_GET['component'],4,1);
if (isset($_GET['section']))$section=parseString($_GET['section'],4,1);

if ($_POST['event']=='signup') 
	{
	$_POST['wm']=utf8_substr(parseString($_POST['wm'],4,0),0,15);
	$_POST['name']=utf8_substr(parseString($_POST['name'],4,0),0,50);
	$_POST['sr']=utf8_substr(parseString($_POST['sr'],4,0),0,50);
	$_POST['fam']=utf8_substr(parseString($_POST['fam'],4,0),0,50);
	$_POST['username']=utf8_substr(parseString($_POST['username'],4,1),0,50);
//	if (empty($_POST['wm'])) $err=6;
	if (!email_check($_POST['email'])) $err=3;
	if ($_POST['pwd']<>$_POST['pwd2']) $err=4;
	if (empty($_POST['username'])) $err=5;
	}

//print_r($_POST);
//exit;

?>