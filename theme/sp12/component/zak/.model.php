<?defined('_JEXEC') or die('Restricted access');

if(!empty($sort_city) and intval($sort_city)>0)
	{
	$sql_sort_city=' and `punbb_users`.`city`='.intval($sort_city);
	}
$sql_zakup_status="(`sp_zakup`.`status`=3 or `sp_zakup`.`status`=5)";
if($user->get_property('gid')==0)
	{
	$sql_zakup_level="and `sp_zakup`.`level`=1"; //красный
	}
	else
	{
	$sql_zakup_level="and (`sp_zakup`.`level`='1' or `sp_zakup`.`level`='2' or `sp_zakup`.`level`='3')";
	}
if(!empty($sort_catz) and intval($sort_catz)<>0 and intval($sort_catz)<>-1)
	{
	if($sort_catz==-2)$sql_zakup_status="`sp_zakup`.`status`>3 and `sp_zakup`.`status`<9 and  `sp_zakup`.`status`<>5";
	if($sort_catz>0) 
		{

		$query = "SELECT * FROM `sp_cat` WHERE `id`=".intval($sort_catz);
		$testcats=$DB->getAll($query);
		if($testcats[0]['podcat']==0)
			{
			$query = "SELECT * FROM `sp_cat` WHERE `podcat`=".$testcats[0]['id'];
			$allpodcats=$DB->getAll($query);
			$catselect='(';
			$i=0;
			foreach($allpodcats as $ac):$i++;
				$catselect.="`sp_cat_sub`.`cat`='".$ac['id']."'";
				if($i<count($allpodcats))$catselect.=' OR ';
			endforeach;
			$catselect.=') > 0';
			$sort_catz_scroll=$testcats[0]['id'];
			}
			else 
			{
				$catselect="`sp_cat_sub`.`cat`='".intval($sort_catz)."'";
				$sort_catz_scroll=$testcats[0]['podcat'];
			}
		$sql_sort_catz=" and (SELECT count(`sp_cat_sub`.`id`) 
		FROM `sp_cat_sub` 
		WHERE `sp_cat_sub`.`zakup`=`sp_zakup`.`id` and $catselect) > 0";
		}

	}

	$page	                = intval($_GET['page']);
	// Переменная хранит число сообщений выводимых на станице 
	$num = 12;
	// Извлекаем из URL текущую страницу 
	if ($page==0) $page=1;
	// Определяем общее число сообщений в базе данных 
	$posts = $DB->getOne("SELECT count(`sp_zakup`.`id`) 
				FROM `sp_zakup` 
				LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
				WHERE $sql_zakup_status and sp_zakup.hot = 1");
	// Находим общее число страниц 
	$total = intval(($posts - 1) / $num) + 1;  

	// Определяем начало сообщений для текущей страницы 
	$page = intval($page);  
	// Если значение $page меньше единицы или отрицательно 
	// переходим на первую страницу 
	// А если слишком большое, то переходим на последнюю 
	if(empty($page) or $page < 0) $page = 1; 
	if($page > $total) $page = $total; 
	// Вычисляем начиная к какого номера 
	// следует выводить сообщения 
	$start = $page * $num - $num;

	// Проверяем нужны ли стрелки назад 
	if($sort_catz==-1)$sort_catz_page='new';
	if($sort_catz==-2)$sort_catz_page='ready';
	if($sort_catz==0)$sort_catz_page='all';
	if($sort_catz>0)$sort_catz_page=$sort_catz;
	if($sort_city==0)$sort_city_page='every';
	if($sort_city>0)$sort_city_page=$sort_city;
	$link_url='/'.$sort_city_page.'/'.$sort_catz_page;
	if ($page != 1) $pervpage = '<a href="'.$link_url.'/-1">первая...</a> 
                               <a href="'.$link_url.'/'. ($page - 1).'">предыдущая...</a> '; 
	// Проверяем нужны ли стрелки вперед 
	if ($page != $total) $nextpage = '  <a href="'.$link_url.'/'. ($page + 1).'">следующая...</a>
                                   <a href="'.$link_url.'/'.$total.'">последняя...</a> '; 
	// Находим две ближайшие станицы с обоих краев, если они есть 
	if($page - 2 > 0) $page2left = ' <a href="'.$link_url.'/'. ($page - 2) .'">'. ($page - 2) .'</a>  '; 
	if($page - 1 > 0) $page1left = '<a href="'.$link_url.'/'. ($page - 1) .'">'. ($page - 1) .'</a>  '; 
	if($page + 2 <= $total) $page2right = '  <a href="'.$link_url.'/'. ($page + 2).'">'. ($page + 2) .'</a>'; 
	if($page + 1 <= $total) $page1right = '  <a href="'.$link_url.'/'. ($page + 1).'">'. ($page + 1) .'</a>';


$sql="	SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`user`,`sp_zakup`.`text`,`sp_zakup`.`min`,`sp_zakup`.`proc`,
		`sp_zakup`.`foto`,`punbb_users`.`username`,`cities`.`city_name_ru`,
		(select count(`sp_order`.`id`) from `sp_order` where `sp_order`.`id_zp`=`sp_zakup`.`id`) AS `res`,
		`sp_status`.`name` as `namestat`
	FROM `sp_zakup`
	LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
	LEFT JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
	LEFT JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
	WHERE $sql_zakup_status and sp_zakup.hot = '1'
	ORDER BY `sp_zakup`.id DESC
	LIMIT $start, $num";
//echo $sql;
$zakup = getAllcache($sql,120);


$registry['ads1'] = $DB->getAll('SELECT * FROM `links` WHERE `block`=1 ORDER BY id ASC');
