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
	
	/////////////////////////////////////////////////////

	
	
	////////////////////////////////////////////////////////
	$query = "SELECT o.id, o.id_order, o.id_ryad, o.date, o.kolvo, o.message, o.color, o.status
			, o.user, `punbb_users`.`username`
			FROM `sp_order` AS o
			LEFT JOIN `punbb_users` ON o.user=`punbb_users`.`id`
			WHERE o.`id_zp` = '".intval($_GET['value'])."' ORDER BY o.id DESC";
	  $allorder=$DB->getAll($query);

	  $items_total_all=0;
	  $allsize=array(); 
	  
	  unset($allryad);
	  unset($allorder);

	  $onepc=$openzakup[0]['min']/100;

	  $items_total_all=round($items_total_all/$onepc);
	  if ($items_total_all<100) $items_total_width=$items_total_all; else $items_total_width=100;

	  $sql="SELECT count(`sp_size`.`id`)
		FROM `sp_size`
		LEFT JOIN `sp_ryad` ON `sp_size`.`id_ryad`=`sp_ryad`.`id`
		WHERE `sp_ryad`.`id_zp`='".intval($_GET['value'])."' and `sp_size`.`user`>''";
	  $total_order_zp=$DB->getOne($sql);

	  $all_comments = $DB->getAll('
		SELECT `comments`.*, `punbb_users`.`username` 
		FROM `comments` LEFT JOIN `punbb_users` ON `comments`.`user`=`punbb_users`.`id`
		WHERE `comments`.`news`= \''.intval($nws).'\' and `comments`.`table`='.$table.'
		ORDER BY `comments`.`id` ASC
		');
		
		/////////////////////////////
	
	
	
	
	
	
	

	$page	                = intval($_GET['page']);
	// Переменная хранит число сообщений выводимых на станице 
	$num = 21;
	// Извлекаем из URL текущую страницу 
	if ($page==0) $page=1;
	// Определяем общее число сообщений в базе данных 
	$posts = $DB->getOne("SELECT count(`sp_zakup`.`id`) 
				FROM `sp_zakup` 
				LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
				WHERE $sql_zakup_status $sql_sort_city $sql_sort_catz $sql_zakup_level");
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

	if($sort_catz==-1)$sort_catz_page='new';
	if($sort_catz==-2)$sort_catz_page='ready';
	if($sort_catz==0)$sort_catz_page='all';
	if($sort_catz>0)$sort_catz_page=$sort_catz;
	if($sort_city==0)$sort_city_page='every';
	if($sort_city>0)$sort_city_page=$sort_city;
	$link_url='/'.$sort_city_page.'/'.$sort_catz_page;

/*	// Проверяем нужны ли стрелки назад
	if ($page != 1) $pervpage = 
					'<a href="'.$link_url.'/-1"> <<< </a> <a href="'.$link_url.'/'.($page - 1).'"> < </a> '; 
	// Проверяем нужны ли стрелки вперед 
	if ($page != $total) $nextpage = 
					'<a href="'.$link_url.'/'. ($page + 1).'"> > </a> <a href="'.$link_url.'/'.$total.'"> >>> </a> '; 
	// Находим две ближайшие страницы с обоих краев, если они есть 
	if($page - 4 > 0) $page4left = '<a href="'.$link_url.'/'. ($page - 4) .'">'. ($page - 4) .'</a>  ';
	if($page - 3 > 0) $page3left = '<a href="'.$link_url.'/'. ($page - 3) .'">'. ($page - 3) .'</a>  ';
	if($page - 2 > 0) $page2left = '<a href="'.$link_url.'/'. ($page - 2) .'">'. ($page - 2) .'</a>  '; 
	if($page - 1 > 0) $page1left = '<a href="'.$link_url.'/'. ($page - 1) .'">'. ($page - 1) .'</a>  '; 
	
	
	if($page + 4 <= $total) $page4right = '<a href="'.$link_url.'/'. ($page + 4).'">'. ($page + 4) .'</a>'; 
	if($page + 3 <= $total) $page3right = '<a href="'.$link_url.'/'. ($page + 3).'">'. ($page + 3) .'</a>'; 
	if($page + 2 <= $total) $page2right = '<a href="'.$link_url.'/'. ($page + 2).'">'. ($page + 2) .'</a>'; 
	if($page + 1 <= $total) $page1right = '<a href="'.$link_url.'/'. ($page + 1).'">'. ($page + 1) .'</a>';*/

$query = "SELECT `sp_order`.*,`sp_ryad`.`price`,`sp_ryad`.`title`, sp_zakup.curs
	  FROM `sp_order` 
	  LEFT JOIN `sp_ryad` ON sp_order.id_ryad = sp_ryad.id 
	  LEFT JOIN `sp_zakup` ON sp_order.id_zp = sp_zakup.id 
	  WHERE `sp_zakup`.`status`>2 $sql_zakup_level
	  ORDER BY sp_order.id DESC 
	  LIMIT 3";
$lastorder = getAllcache($query,120);

$mesarr= array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');

/*$sql="	SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`user`,`sp_zakup`.`text`,`sp_zakup`.`min`,`sp_zakup`.`proc`,
		`sp_zakup`.`foto`,`punbb_users`.`username`,`punbb_users`.`display_name`,`cities`.`city_name_ru`,
		(select count(`sp_order`.`id`) from `sp_order` where `sp_order`.`id_zp`=`sp_zakup`.`id`) AS `res`
	FROM `sp_zakup`
	LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
	JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
	WHERE (`sp_zakup`.`status`=3 or `sp_zakup`.`status`=5) $sql_zakup_level
	ORDER BY RAND()
	LIMIT 1";
$popzp = getAllcache($sql,120);*/

  if(!empty($popzp[0]['foto']))
	{
	$split=explode('/',$popzp[0]['foto']);
	$img_path='/images/'.$split[2].'/125/100/1/'.$split[3];
	} else $img_path='/'.$theme.'images/no_photo125x100.png';

$sql="	SELECT `sp_zakup`.`id`,
		`sp_zakup`.`title`,
		`sp_zakup`.`user`,
		`sp_zakup`.`text`,
		`sp_zakup`.`min`,
		`sp_zakup`.`proc`,
		`sp_zakup`.`foto`,
		`punbb_users`.`username`,
		`punbb_users`.`display_name`,
		`cities`.`city_name_ru`,
		
		
		(select count(`sp_order`.`id`) 
		from `sp_order` 
		where `sp_order`.`id_zp`=`sp_zakup`.`id`) AS `res`,
		`sp_status`.`name` as `namestat`
	FROM `sp_zakup`
	LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
	LEFT JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
	LEFT JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
	WHERE $sql_zakup_status $sql_sort_city $sql_sort_catz $sql_zakup_level
	ORDER BY sp_zakup.top DESC, sp_zakup.date DESC 
	"; 					// 	LIMIT $start, $num			Ограничение закупок на странице
// echo $sql;
$zakup = getAllcache($sql,120);

// -------------------------------------------------------------------------------------
$queue = "SELECT id, id_zp, id_order, id_ryad, kolvo, status, user 
		FROM `sp_order`";
$sql_data=$DB->getAll($queue);

$percent_all=0;
foreach ($sql_data as $data)
{
$point[1]=$data['id'];
$point[2]=$data['kolvo'];
$point[3]=$data['id_zp'];
}


//--------------------------------------------------------------------------------------------- попытка, не пытка
/*	$query = "SELECT o.id, o.id_order, o.id_ryad, o.date, o.kolvo, o.message, o.color, o.status
			, o.user, `punbb_users`.`username`
			FROM `sp_order` AS o
			LEFT JOIN `punbb_users` ON o.user=`punbb_users`.`id`
			WHERE o.`id_zp` = '".intval($zak['id'])."' ORDER BY o.id DESC";
	$allorder=$DB->getAll($query);

	  $items_total_all=0;
	  $allsize=array(); 
	  foreach($allorder as $aord)
		{
		$query = "SELECT * FROM `sp_ryad` WHERE `id` = ".$aord['id_ryad'];
		$allryad=$DB->getAll($query);

		$query = "SELECT `sp_size`.`anonim`, `sp_size`.`name`,`sp_size`.`user`,
				`sp_ryad`.`size`,`sp_ryad`.`spec`
			  FROM `sp_size` 
			  JOIN `sp_ryad` ON `sp_size`.`id_ryad`=`sp_ryad`.`id`
			  WHERE `sp_size`.`id` = ".$aord['id_order'];
		$allsize_tmp=$DB->getAll($query);
		
		$allryad[1]=$aord['date'];
		$allryad[2]=$aord['kolvo'];
		$allryad[3]=$allsize_tmp[0]['anonim'];
		$allryad[4]=$aord['user'];
		$allryad[5]=$aord['username'];
		$allryad[6]=$allsize_tmp[0]['name'];
		$allryad[7]=$aord['color'];
		$allryad[8]=$aord['id'];
		$allryad[9]=$aord['status'];
		$allryad[10]=htmlspecialchars_decode($aord['message']);
		if(count($allsize)<10)$allsize[]=$allryad;
		if($aord['status']!=2)$items_total_all=$items_total_all+($allryad[0]['price']*$aord['kolvo']*$openzakup[0]['curs']);
		}

*/
// ------------------------------------------------------------------------------------------------------






	$query = "SELECT * FROM `sp_level`";
	$level=getAllcache($query,6000);

	$query = "SELECT * FROM `sp_status`";
	$statuslist=getAllcache($query,6000);

	$query='SELECT * FROM sp_cat WHERE podcat=0';
	$all = getAllcache($query,6000);
	$i=0;
	foreach($all as $num):
		$cat_zp[$num['id']][0]=$num;
		$i++;
	endforeach;

	$query='SELECT * FROM sp_cat WHERE podcat>0';
	$all = getAllcache($query,6000);
	$i=0;
	foreach($all as $num):
		$cat_zp[$num['podcat']][]=$num;
		$i++;
	endforeach;



$registry['ads1'] = $DB->getAll('SELECT * FROM `links` WHERE `block`=1 ORDER BY id ASC');


$i=0;$sql_in='';$sql='';

//Вытаскиваем данные для создания миниатюр рядковых картинок на основной странице
/*foreach($zakup as $item) {
	$sql="SELECT `sp_ryad`.`photo`,`sp_ryad`.`message`,`sp_ryad`.`id`,`sp_ryad`.`id_zp`



		FROM `sp_ryad`
		WHERE `sp_ryad`.`id_zp` = '{$item['id']}' and (`sp_ryad`.`photo` != '')

			and `sp_ryad`.`spec`=1
		ORDER BY sp_ryad.id DESC LIMIT 5";
	$ryad = getAllcache($sql,120);

	$allryad_sort[$item['id']]=$ryad;
}*/
