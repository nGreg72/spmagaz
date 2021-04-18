<?defined('_JEXEC') or die('Restricted access');

if ($user->get_property('userID')==1 OR $user->get_property('gid')==25)
	{
	if(isset($_POST['change']) and intval($_POST['zakup'])>0) {
		$id=intval($_POST['zakup']);
		$userID=intval($_POST['user']);
		$status=intval($_POST['status']);


		$query = "SELECT `sp_zakup`.* FROM `sp_zakup` WHERE `id` ='$id'";
	        $zp = $DB->getAll($query);

		$query = "UPDATE `sp_zakup` SET `user`='$userID',`status`='$status' WHERE `id` ='$id' LIMIT 1 ;";
		$DB->execute($query);

		$query = "UPDATE `sp_url_ckeck` SET `user`='$userID' WHERE `id` ='{$zp[0]['id_check']}' LIMIT 1 ;";
		$DB->execute($query);

		$query = "UPDATE `sp_ryad` SET `user`='$userID' WHERE `id_zp` ='$id'  ;";
		$DB->execute($query);

	}
	

	if($_GET['section']=='add') {
		$query = "UPDATE `sp_zakup` SET `status` = '3', `date`='".time()."' WHERE `id` =".intval($_GET['id'])." LIMIT 1 ;";
		$DB->execute($query);

	$query = "SELECT `punbb_users`.`email`,`sp_zakup`.`id`,`sp_zakup`.`title`
		FROM `sp_zakup` LEFT
		JOIN `punbb_users` ON `sp_zakup`.`user` = `punbb_users`.`id` 
		WHERE `sp_zakup`.`id`=".intval($_GET['id'])." LIMIT 1";
        $items = $DB->getAll($query);

	if(count($items)>0)
		{
		$emailsup = $DB->getOne('SELECT `setting`.`value` 
		FROM `setting`
		WHERE `setting`.`name`=\'emailsup\'');

		$m= new Mail; // начинаем
		$m->From("$emailsup"); // от кого отправляется почта
		$m->To($items[0]['email']); // кому адресованно
		$m->text_html="text/html";
		$m->Subject( "Закупка одобрена - ".$_SERVER['HTTP_HOST']);
		$m->Body( "
Здравствуйте,<br/>
Это письмо отправлено вам сайтом: <a href=\"".$_SERVER['HTTP_HOST']."\">".$_SERVER['HTTP_HOST']."</a><br/>
<p>
Закупка  \"".$items[0]['title']."\" одобрена администратором и открыта для заказов.
</p><br/>
<p>Для просмотра закупки перейдите по ссылке:<br/>
<a href=\"http://".$_SERVER['HTTP_HOST']."/com/org/open/".$items[0]['id']."\">http://".$_SERVER['HTTP_HOST']."/com/org/open/".$items[0]['id']."</a></p>
" );    
		$m->Priority(3) ;    // приоритет письма
		$m->Send();    // а теперь пошла отправка
		
                }
         header('Location: index.php?component=zakup');
	}

	if($_GET['section']=='del') {
	
	if(empty($_GET['check'])) 
		{
		$query = "SELECT `punbb_users`.`email`,`sp_zakup`.`id`,`sp_zakup`.`title`
			FROM `sp_zakup` LEFT
			JOIN `punbb_users` ON `sp_zakup`.`user` = `punbb_users`.`id` 
			WHERE `sp_zakup`.`id`=".intval($_GET['id'])." LIMIT 1";
	        $items = $DB->getAll($query);

		if(count($items)>0)
			{
//die('1');
			$emailsup = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'emailsup\'');

			$m= new Mail; // начинаем
			$m->From("$emailsup"); // от кого отправляется почта
			$m->To($items[0]['email']); // кому адресованно
			$m->text_html="text/html";
			$m->Subject( "Закупка удалена модератором - ".$_SERVER['HTTP_HOST']);
			$m->Body( "
			Здравствуйте,<br/>
			Это письмо отправлено вам сайтом: <a href=\"".$_SERVER['HTTP_HOST']."\">".$_SERVER['HTTP_HOST']."</a><br/>
			<p>
				Закупка  \"".$items[0]['title']."\" была удалена модератором сайта.
			</p><br/>
				<p>Для получения подробной информации вы можете написать на эл. почту: $emailsup<br/>
			</p>" );    
				$m->Priority(3) ;    // приоритет письма
			$m->Send();    // а теперь пошла отправка
		
	                }
			$query = "SELECT `id`, `foto` FROM `sp_zakup` WHERE `id` = ".intval($_GET['id']);
		        $zakup=$DB->getAll($query);
	
			if ($zakup[0]['id']>0)
			{
			$query = "SELECT id FROM `sp_ryad` WHERE `id_zp` = ".$zakup[0]['id']; // ORDER BY id DESC LIMIT $flatCount
		        $ryad=$DB->getAll($query);

		        foreach ($ryad as $rd)
				{
				$query = "SELECT id FROM `sp_size` WHERE `id_ryad` = ".$rd['id']; // ORDER BY id DESC LIMIT $flatCount
				$size=$DB->getAll($query);
			        foreach ($size as $it)
					{// удаляем все заказы в этой закупке
					$query = "DELETE FROM `sp_order` WHERE `id_order` = ".$it['id'];
					$DB->execute($query);
					}
				//удаляем все размеры из рядов этой закупки
				$query = "DELETE FROM `sp_size` WHERE `id_ryad` = ".$rd['id'];
				$DB->execute($query);
				}
			//удаляем все ряды
			$query = "DELETE FROM `sp_ryad` WHERE `id_zp` = ".$zakup[0]['id'];
			$DB->execute($query);
			//удаляем закупку
	        	$query = "DELETE FROM `sp_zakup` WHERE `id` = ".$zakup[0]['id'];
			$DB->execute($query);
			//удаляем категории
			$sql="DELETE FROM `sp_cat_sub` WHERE `sp_cat_sub`.`zakup` = ".$zakup[0]['id'];
			$DB->execute($sql);

			@unlink($zakup[0]['foto']);
        		}


	         header('Location: index.php?component=zakup');exit;
		}

	if(intval($_GET['check'])==1) 
		{
			$query = "DELETE FROM `sp_url_ckeck` WHERE `id` =".intval($_GET['id'])." LIMIT 1 ";
			$DB->execute($query);
	         header('Location: index.php?component=zakup&check=all');exit;
		}
	if(intval($_GET['check'])==2) 
		{

		$query = "SELECT `id`, `foto` FROM `sp_zakup` WHERE `id_check` = ".intval($_GET['id']);
	        $zakup=$DB->getAll($query);

		$query = "DELETE FROM `sp_url_ckeck` WHERE `id` =".intval($_GET['id'])." LIMIT 1 ";
		$DB->execute($query);

		if ($zakup[0]['id']>0)
			{
			$query = "SELECT id FROM `sp_ryad` WHERE `id_zp` = ".$zakup[0]['id']; // ORDER BY id DESC LIMIT $flatCount
		        $ryad=$DB->getAll($query);

		        foreach ($ryad as $rd)
				{
				$query = "SELECT id FROM `sp_size` WHERE `id_ryad` = ".$rd['id']; // ORDER BY id DESC LIMIT $flatCount
				$size=$DB->getAll($query);
			        foreach ($size as $it)
					{// удаляем все заказы в этой закупке
					$query = "DELETE FROM `sp_order` WHERE `id_order` = ".$it['id'];
					$DB->execute($query);
					}
				//удаляем все размеры из рядов этой закупки
				$query = "DELETE FROM `sp_size` WHERE `id_ryad` = ".$rd['id'];
				$DB->execute($query);
				}
			//удаляем все ряды
			$query = "DELETE FROM `sp_ryad` WHERE `id_zp` = ".$zakup[0]['id'];
			$DB->execute($query);
			//удаляем закупку
	        	$query = "DELETE FROM `sp_zakup` WHERE `id` = ".$zakup[0]['id'];
			$DB->execute($query);
			//удаляем категории
			$sql="DELETE FROM `sp_cat_sub` WHERE `sp_cat_sub`.`zakup` = ".$zakup[0]['id'];
			$DB->execute($sql);

			@unlink($zakup[0]['foto']);
        		}
		header('Location: index.php?component=zakup');exit;
		}
	}

	if($_GET['section']=='date') {
	$query = "SELECT `sp_zakup`.`id` FROM `sp_zakup`
	ORDER BY `sp_zakup`.`id` ASC";
        $items = $DB->getAll($query);
	$i=0;
	foreach($items as $it):
		$dat=time()-13600+($it['id']*2);
		$query = "UPDATE `sp_zakup` SET `date`='$dat' WHERE `id` =".$it['id']." LIMIT 1 ;";
		$DB->execute($query);
		$i++;
	endforeach;
	}


	if(empty($_GET['check'])) 
		{
		$status=intval($_GET['status']);
		if($status>0) $sql_st=" `sp_zakup`.`status` = '$status'"; else $sql_st=" `sp_zakup`.`status` > '0'";

//---------------------------------------------
	$page	                = intval($_GET['page']);
	$num = 15;
	if ($page==0) $page=1;
	$posts = $DB->getOne("SELECT count(sp_zakup.id) FROM sp_zakup WHERE $sql_st");
	$colichestvo = $posts;
	$total = intval(($posts - 1) / $num) + 1;  
	$page = intval($page);  
	if(empty($page) or $page < 0) $page = 1; 
	if($page > $total) $page = $total; 
	$start = $page * $num - $num;
	$link_url="index.php?component=zakup&section=default&status={$_GET['status']}&check={$_GET['check']}";
	if ($page != 1) $pervpage = '<a href="'.$link_url.'&page=-1"><<</a> 
                               <a href="'.$link_url.'&page='. ($page - 1).'"><</a> '; 

	if ($page != $total) $nextpage = '  <a href="'.$link_url.'&page='. ($page + 1).'">></a>
                                   <a href="'.$link_url.'&page='.$total.'">>></a> '; 

	if($page - 2 > 0) $page2left = ' <a href="'.$link_url.'&page='. ($page - 2) .'">'. ($page - 2) .'</a>  '; 
	if($page - 1 > 0) $page1left = '<a href="'.$link_url.'&page='. ($page - 1) .'">'. ($page - 1) .'</a>  '; 
	if($page + 2 <= $total) $page2right = '  <a href="'.$link_url.'&page='. ($page + 2).'">'. ($page + 2) .'</a>'; 
	if($page + 1 <= $total) $page1right = '  <a href="'.$link_url.'&page='. ($page + 1).'">'. ($page + 1) .'</a>';

//---------------------------------------------

	if($_POST['filter'] = 1)
	{
		$sql_st = $sql_st." AND `sp_zakup`.`title` LIKE '%".$_POST['search']."%' ";
	}
	
	// вот тут всех ты должен поменятьт на данные со строки поиска
		$query = "SELECT `sp_zakup`.*, `sp_status`.`name`
		FROM `sp_zakup` 
		LEFT JOIN `sp_status` ON `sp_zakup`.`status` = `sp_status`.`id`
		WHERE $sql_st
		ORDER BY `sp_zakup`.`id` DESC
		LIMIT $start, $num";
	        $items = $DB->getAll($query);



		}
		else 
		{
//---------------------------------------------
	$page	                = intval($_GET['page']);
	$num = 15;
	if ($page==0) $page=1;
	$posts = $DB->getOne("SELECT count(sp_url_ckeck.id) FROM sp_url_ckeck ");
	$total = intval(($posts - 1) / $num) + 1;  
	$page = intval($page);  
	if(empty($page) or $page < 0) $page = 1; 
	if($page > $total) $page = $total; 
	$start = $page * $num - $num;
	$link_url="index.php?component=zakup&section=default&status={$_GET['status']}&check={$_GET['check']}";
	if ($page != 1) $pervpage = '<a href="'.$link_url.'&page=-1"><<</a> 
                               <a href="'.$link_url.'&page='. ($page - 1).'"><</a> '; 
	if ($page != $total) $nextpage = '  <a href="'.$link_url.'&page='. ($page + 1).'">></a>
                                   <a href="'.$link_url.'&page='.$total.'">>></a> '; 
	if($page - 2 > 0) $page2left = ' <a href="'.$link_url.'&page='. ($page - 2) .'">'. ($page - 2) .'</a>  '; 
	if($page - 1 > 0) $page1left = '<a href="'.$link_url.'&page='. ($page - 1) .'">'. ($page - 1) .'</a>  '; 
	if($page + 2 <= $total) $page2right = '  <a href="'.$link_url.'&page='. ($page + 2).'">'. ($page + 2) .'</a>'; 
	if($page + 1 <= $total) $page1right = '  <a href="'.$link_url.'&page='. ($page + 1).'">'. ($page + 1) .'</a>';

//---------------------------------------------



		$query = "SELECT `sp_url_ckeck`.*
		FROM `sp_url_ckeck`
		ORDER BY `sp_url_ckeck`.`id` DESC
		LIMIT $start, $num
		";
	        $items = $DB->getAll($query);
		}
	$query = "SELECT * FROM `sp_status` ORDER BY discr ASC";
	$statuslist=$DB->getAll($query);

	$query = "SELECT punbb_users.*, 
                        IF(punbb_users.display_name != '', punbb_users.display_name, punbb_users.username) AS username
		FROM `punbb_users` WHERE `group_id` = '5' OR `group_id` = '1' OR `group_id` = '4'";
	$orglist=$DB->getAll($query);

	}