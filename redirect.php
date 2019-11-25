<?

require_once 'lib/idna_convert.class.php';


function utf8_win($s) {
	$out = "";
	$c1 = "";
	$byte2 = false;
	for ($c = 0; $c < strlen($s); $c ++){
		$i = ord($s[$c]);
		if ($i <= 127) $out .= $s[$c];
		if ($byte2) {
			$new_c2 = ($c1 & 3) * 64 + ($i & 63);
			$new_c1 = ($c1 >> 2) & 5;
			$new_i = $new_c1 * 256 + $new_c2;
			if ($new_i == 1025){
				$out_i = 168;
			}else {
				if ($new_i == 1105){
					$out_i = 184;
				}else {
					$out_i = $new_i-848;
				}
			}
			$out .= chr($out_i);
			$byte2 = false;
		}
		if (($i >> 5) == 6) {
			$c1 = $i;
			$byte2 = true;
		}
	}
	return $out;
}
function utf8_win1($s) {
$s=strtr($s,array("\xD0\xB0"=>"а", "\xD0\x90"=>"А", "\xD0\xB1"=>"б", "\xD0\x91"=>"Б",
 "\xD0\xB2"=>"в", "\xD0\x92"=>"В", "\xD0\xB3"=>"г", "\xD0\x93"=>"Г", "\xD0\xB4"=>"д",
 "\xD0\x94"=>"Д", "\xD0\xB5"=>"е", "\xD0\x95"=>"Е", "\xD1\x91"=>"ё", "\xD0\x81"=>"Ё",
 "\xD0\xB6"=>"ж", "\xD0\x96"=>"Ж", "\xD0\xB7"=>"з", "\xD0\x97"=>"З", "\xD0\xB8"=>"и",
 "\xD0\x98"=>"И", "\xD0\xB9"=>"й", "\xD0\x99"=>"Й", "\xD0\xBA"=>"к", "\xD0\x9A"=>"К",
 "\xD0\xBB"=>"л", "\xD0\x9B"=>"Л", "\xD0\xBC"=>"м", "\xD0\x9C"=>"М", "\xD0\xBD"=>"н",
 "\xD0\x9D"=>"Н", "\xD0\xBE"=>"о", "\xD0\x9E"=>"О", "\xD0\xBF"=>"п", "\xD0\x9F"=>"П",
 "\xD1\x80"=>"р", "\xD0\xA0"=>"Р", "\xD1\x81"=>"с", "\xD0\xA1"=>"С", "\xD1\x82"=>"т",
 "\xD0\xA2"=>"Т", "\xD1\x83"=>"у", "\xD0\xA3"=>"У", "\xD1\x84"=>"ф", "\xD0\xA4"=>"Ф",
 "\xD1\x85"=>"х", "\xD0\xA5"=>"Х", "\xD1\x86"=>"ц", "\xD0\xA6"=>"Ц", "\xD1\x87"=>"ч",
 "\xD0\xA7"=>"Ч", "\xD1\x88"=>"ш", "\xD0\xA8"=>"Ш", "\xD1\x89"=>"щ", "\xD0\xA9"=>"Щ",
 "\xD1\x8A"=>"ъ", "\xD0\xAA"=>"Ъ", "\xD1\x8B"=>"ы", "\xD0\xAB"=>"Ы", "\xD1\x8C"=>"ь",
 "\xD0\xAC"=>"Ь", "\xD1\x8D"=>"э", "\xD0\xAD"=>"Э", "\xD1\x8E"=>"ю", "\xD0\xAE"=>"Ю",
 "\xD1\x8F"=>"я", "\xD0\xAF"=>"Я"));
return $s;
}

function win_utf8($in_text) { 
   $output = "";
   $other[1025] = "Ё";
   $other[1105] = "ё";
   $other[1028] = "Є";
   $other[1108] = "є";
   $other[1030] = "I";
   $other[1110] = "i";
   $other[1031] = "Ї";
   $other[1111] = "ї";
   for ($i = 0; $i < strlen($in_text); $i++){
      if (ord($in_text{$i}) > 191) {
         $output.="&#".(ord($in_text{$i})+848).";";
      }else {
         if (array_search($in_text{$i}, $other)===false){
            $output.=$in_text{$i};
         }else {
            $output.="&#".array_search($in_text{$i}, $other).";";
         }
      }
   }
   return $output;
}


function UrlToPuny($str)
{
	$idn = new idna_convert(array('idn_version'=>2008));
	return $idn->encode($str);
}




$url=UrlToPuny($_GET['url']);

$http='http';
if($_GET['ssh']==1)$http='https';
if(!empty($url))header('Location: '.$http.'://'.str_replace('http://','',$url));
else exit;
?>