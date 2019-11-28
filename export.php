<?
if ($user->get_property('userID')>0 AND $user->get_property('active')>0)
	{

if ($_POST['export']==1 and $export==1)
{
function recRMDir($path){ 
    if (substr($path, strlen($path)-1, 1) != '/') $path .= '/'; 
    if ($handle = @opendir($path)){ 
        while ($obj = readdir($handle)){ 
            if ($obj != '.' && $obj != '..'){ 
                if (is_dir($path.$obj)){ 
                    if (!recRMDir($path.$obj)) return false; 
                }elseif (is_file($path.$obj)){ 
                    if (!unlink($path.$obj))    return false; 
                    } 
            } 
        } 
          closedir($handle); 
//            if (!@rmdir($path)) return false; 
          return true; 
    } 
   return false; 
}

if ($_POST['filter']==1)
	{
	$maxdate = $DB->getOne("SELECT value FROM `setting` WHERE `name`='max_date'");
	$date_dd=intval($_POST['date_dd']);
	$date_mm=intval($_POST['date_mm']);
	$date_yy=intval($_POST['date_yy']);
	 if($date_dd>31)$date_dd=31;
	 if($date_dd<1)$date_dd=1;
	 if($date_mm>12)$date_mm=12;
	 if($date_mm<1)$date_mm=1;
	 if($date_yy>$maxdate)$date_yy=$maxdate;
	 if($date_yy<2011)$date_yy=2011;
		$date_dd2=intval($_POST['date_dd2']);
	$date_mm2=intval($_POST['date_mm2']);
	$date_yy2=intval($_POST['date_yy2']);
		 if($date_dd2>31)$date_dd2=31;
	 if($date_dd2<1)$date_dd2=31;
	 if($date_mm2>12)$date_mm2=12;
	 if($date_mm2<1)$date_mm2=12;
	 if($date_yy2>$maxdate)$date_yy2=$maxdate;
	 if($date_yy2<2011)$date_yy2=$maxdate;

	$date1=mktime(0,0,0,$date_mm,$date_dd,$date_yy);
	$date2=mktime(23,59,0,$date_mm2,$date_dd2,$date_yy2);
	$sql_filter=" `finance`.`date`>='$date1' and `finance`.`date`<='$date2' and ";
	} else 
	{
	$date1=mktime(0,0,0,date('m'),date('d'),date('Y'));
	$date2=mktime(23,59,0,date('m'),date('d'),date('Y'));
	$sql_filter=" `finance`.`date`>='$date1' and `finance`.`date`<='$date2' and ";
	}

$novice = $DB->getAll('SELECT `finance`.* FROM `finance` 
		WHERE '.$sql_filter.' `finance`.`user`=\''.$user->get_property('userID').'\'
		ORDER BY `finance`.`date` DESC');

	$filename='excel/'.$user->get_property('userID').rand(100000,9999999).'finance.csv.php';
	recRMDir('excel/');
	$line='<?header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"'
		.$user->get_property('userID').rand(100000,9999999).'finance.csv'.'\"");?>';

	$line.="ИД;Дата;Название;Сумма;\n";
foreach ($novice as $nov)
	{
	$line.= $nov['id'].";".date('d/n/Y',$nov['date']).";".iconv('utf-8','windows-1251',$nov['name']).";".$nov['summ']." руб;\n";
	}

$fh = @fopen($filename, "a+");
@fwrite($fh, $line);
@fclose($fh);
header("Location: /".$filename);
}

 } else echo 'У вас нет прав для доступа к данному разделу.';
?>