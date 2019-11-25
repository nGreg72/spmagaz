<?
//А вторая функция будет выполнять запросы к БД.
function getAllcache($sql, $time=600) {
	global $DB, $system_query_cache;
	if(!$system_query_cache)$time=0;
	$crc=md5($sql); 
	$modif=time()-@filemtime ("cache/".$crc);
	if ($modif<$time)
		{
		$cache=file_get_contents("cache/".$crc);
		$cache=unserialize($cache);
		}
		else 
		{
		$cache = $DB->getAll($sql);
		$fp = @fopen ("cache/".$crc, "w");
		@fwrite ($fp, serialize($cache));
		@fclose ($fp); 
		}
        return $cache;
}
function getOnecache($sql, $time=600) {
	global $DB, $system_query_cache;
	if(!$system_query_cache)$time=0;
	$crc=md5($sql); 
	$modif=time()-@filemtime ("cache/".$crc);
	if ($modif<$time)
		{
		$cache=file_get_contents("cache/".$crc);
		$cache=unserialize($cache);
		}
		else 
		{
		$cache = $DB->getOne($sql);
		$fp = @fopen ("cache/".$crc, "w");
		@fwrite ($fp, serialize($cache));
		@fclose ($fp); 
		}
        return $cache;
}

$city=$_GET['city'];
$catz=$_GET['catz'];
if(intval($_COOKIE['sp_city'])>0)
	{
        $sort_city=$_COOKIE['sp_city'];
	}
if(!empty($city))
	{
	if($city=='every')$city=0;
	$city=intval($city);
	setcookie('sp_city',$city,(time()+36000),'/');
	$sort_city=$city;
	unset($city);
	}
if(!empty($catz))
	{
	if($catz=='all')$catz=0;
	if($catz=='new')$catz=-1;
	if($catz=='ready')$catz=-2;
	$catz=intval($catz);
	$sort_catz=$catz;
	unset($catz);
	}

$time_reload=(60*60*1);

$sql = "SELECT c.* 
	FROM sp_zakup z
	LEFT JOIN punbb_users p ON p.id = z.user
	LEFT JOIN cities c ON c.id_city = p.city
	WHERE z.status=3 or z.status=5";
$items=getAllcache($sql,$time_reload);

foreach($items as $item) {
	$all_city[$item['city_name_ru']] = $item;
	$all_city[$item['city_name_ru']]['countzp']++;
}

//пишем кэш для форума, чтобы он брал списочек городов
$sql="SELECT `cities`.`id_city`,`cities`.`city_name_ru` FROM `cities` WHERE `show`=1";
getAllcache($sql,$time_reload);


$registry['ads1'] = $DB->getAll('SELECT * FROM `links` WHERE `block`=1 ORDER BY id ASC');
$registry['ads2'] = $DB->getAll('SELECT * FROM `links` WHERE `block`=2 ORDER BY id ASC');
$registry['ads3'] = $DB->getAll('SELECT * FROM `links` WHERE `block`=3 ORDER BY id ASC');