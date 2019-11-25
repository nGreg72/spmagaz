<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('userID')==1 OR $user->get_property('gid')==25):

if($_GET['section']=='add')
	{
	$id=intval($_GET['id']);
	$sql="	UPDATE `sp_add_org` SET `status`='3'
		WHERE `id`='$id'";
	$DB->execute($sql);
	$query="SELECT user FROM sp_add_org WHERE `id`='$id'";
        $userid = $DB->getOne($query);

	$sql="	UPDATE `punbb_users` SET `group_id`='5'
		WHERE `id`='$userid'";
	$DB->execute($sql);
	$semail=1;
	$subject='Вы приняты в группу Организаторы';
	$textemail="<p>Ваша заявка на вступление в группу Организаторы одобрена.</p><p>По всем вопросам вы можете писать нам на эл. почту.</p>";
	}
if($_GET['section']=='see')
	{
	$id=intval($_GET['id']);
	$sql="	UPDATE `sp_add_org` SET `status`='1'
		WHERE `id`='$id'";
	$DB->execute($sql);
	$query="SELECT user FROM sp_add_org WHERE `id`='$id'";
        $userid = $DB->getOne($query);

	$sql="	UPDATE `punbb_users` SET `group_id`='3'
		WHERE `id`='$userid'";
	$DB->execute($sql);
	$semail=1;
	$subject='Ваша заявка на вступление в гр. Организаторы отложена на рассмотрение';
	$textemail="<p>Ваше пребывание в группе Организаторы будет рассмотрено Администрацией в течении ближайшего времени.</p><p>Для уточнения деталей обратитесь в службу поддержки, написав письмо на текущий адрес эл. почты.</p>";
	}

if($_GET['section']=='dela')
	{
	$id=intval($_GET['id']);

	$sql="DELETE FROM `sp_add_org` WHERE `id` = '$id' LIMIT 1";
	$DB->execute($sql);
	header('Location: ?component=org');
	}

if($_GET['section']=='del')
	{
	$id=intval($_GET['id']);
	$sql="	UPDATE `sp_add_org` SET `status`='2'
		WHERE `id`='$id'";
	$DB->execute($sql);

	$query="SELECT user FROM sp_add_org WHERE `id`='$id'";
        $userid = $DB->getOne($query);

	$sql="	UPDATE `punbb_users` SET `group_id`='3'
		WHERE `id`='$userid'";
	$DB->execute($sql);
	$semail=1;
	$subject='Ваша заявка на вступление в гр. Организаторы отклонена';
	$textemail="<p>Ваша заявка на вступление в гр. Организаторы отклонена.</p><p> Для уточнения деталей обратитесь в службу поддержки, написав письмо на текущий адрес эл. почты.</p>";
	}

if($_GET['section']=='viewcheck')
	{
		$id=intval($_GET['id']);
		if(intval($_GET['city'])==1) $query = "SELECT `sp_url_ckeck`.*, `punbb_users`.`username`, `punbb_users`.`id` as `userID` FROM `sp_url_ckeck` LEFT JOIN `punbb_users` ON `sp_url_ckeck`.`user`=`punbb_users`.`id` WHERE `sp_url_ckeck`.`city`='".$id."' ORDER BY `sp_url_ckeck`.`url` ASC";
			elseif(intval($_GET['city'])==0) $query = "SELECT `sp_url_ckeck`.*, `punbb_users`.`username`, `punbb_users`.`id` as `userID` FROM `sp_url_ckeck` LEFT JOIN `punbb_users` ON `sp_url_ckeck`.`user`=`punbb_users`.`id` WHERE `sp_url_ckeck`.`user`='".$id."' ORDER BY `sp_url_ckeck`.`url` ASC";
			elseif(intval($_GET['city'])==2) $query = "SELECT `sp_url_ckeck`.*, `punbb_users`.`username`, `punbb_users`.`id` as `userID` FROM `sp_url_ckeck` LEFT JOIN `punbb_users` ON `sp_url_ckeck`.`user`=`punbb_users`.`id` ORDER BY `sp_url_ckeck`.`url` ASC";
		$all_check=$DB->getAll($query);
	}
if($_GET['section']=='viewopen')
	{
	$id=intval($_GET['id']);
	if(intval($_GET['city'])==1) $sort="and `punbb_users`.`city`='$id'";
		elseif(intval($_GET['city'])==0) $sort="and `sp_zakup`.`user`='$id'";
		elseif(intval($_GET['city'])==2) $sort='';

	$query = "SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`text`,`sp_zakup`.`foto`, `sp_zakup`.`user`, 
	`punbb_users`.`username`, `sp_status`.`name`,
	`cities`.`city_name_ru`,`regions`.`region_name_ru` FROM `sp_zakup` LEFT
	JOIN `punbb_users` ON `sp_zakup`.`user` = `punbb_users`.`id` 
	JOIN `sp_status` ON `sp_zakup`.`status` = `sp_status`.`id`
	JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
	JOIN `regions` ON `cities`.`id_region`= `regions`.`id_region`
	WHERE `sp_zakup`.`status`> '2' $sort ORDER BY `sp_zakup`.`id` DESC";
        $items = $DB->getAll($query);
	}

if($semail==1){
	$query = "SELECT * FROM `punbb_users` WHERE `id` = '$userid' LIMIT 1";
        $userdata = $DB->getAll($query);

	$fromemail = $DB->getOne("SELECT value FROM `setting` WHERE `name`='emailsup'");
	$emailadmin = $DB->getOne("SELECT value FROM `setting` WHERE `name`='email_admin'");

	$m= new Mail; // начинаем
	$m->text_html='text/html';
	$m->From($fromemail); // от кого отправляется почта
	$m->To($userdata[0]['email']); // кому адресованно
	$m->Subject($subject);
	$textemail.='<p><a href="http://'.$domain.'">'.$domain.'</a></p>';
	$m->Body($textemail);
	$m->Priority(3) ;    // приоритет письма
	$m->Send();    // а теперь пошла отправка
	header('Location: index.php?component=org');
	}
if(empty($_GET['section']))
	{
//---------------------------------------------    
	if(!empty($_GET['city'])):
		 $sql_where=' WHERE `sp_add_org`.`city`='.intval($_GET['city']);
		 $pahe_get='&city='.intval($_GET['city']);
	endif;
	$page	                = intval($_GET['page']);

	// Переменная хранит число сообщений выводимых на станице 
	$num = 15;
	// Извлекаем из URL текущую страницу 
	if ($page==0) $page=1;
	// Определяем общее число сообщений в базе данных 
	$query="SELECT count(sp_add_org.id) FROM sp_add_org $sql_where";
        $posts = $DB->getOne($query);
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
	$link_url='index.php?component=org';
	if ($page != 1) $pervpage = '<a href="'.$link_url.'&page=-1"><<</a> 
                               <a href="'.$link_url.'&page='. ($page - 1).'"><</a> '; 
	// Проверяем нужны ли стрелки вперед 
	if ($page != $total) $nextpage = '  <a href="'.$link_url.'&page='. ($page + 1).'">></a>
                                   <a href="'.$link_url.'&page='.$total.'">>></a> '; 
	// Находим две ближайшие станицы с обоих краев, если они есть 
	if($page - 2 > 0) $page2left = ' <a href="'.$link_url.'&page='. ($page - 2) .'">'. ($page - 2) .'</a>  '; 
	if($page - 1 > 0) $page1left = '<a href="'.$link_url.'&page='. ($page - 1) .'">'. ($page - 1) .'</a>  '; 
	if($page + 2 <= $total) $page2right = '  <a href="'.$link_url.'&page='. ($page + 2).'">'. ($page + 2) .'</a>'; 
	if($page + 1 <= $total) $page1right = '  <a href="'.$link_url.'&page='. ($page + 1).'">'. ($page + 1) .'</a>';

//---------------------------------------------
	
	$query = "SELECT `sp_add_org`.*, IF(punbb_users.display_name != '', punbb_users.display_name, punbb_users.username) AS username, `punbb_users`.`email`, 
			`cities`.`city_name_ru`,`regions`.`region_name_ru` 
			FROM `sp_add_org` LEFT 
			JOIN `punbb_users` ON `punbb_users`.`id`=`sp_add_org`.`user`
			JOIN `cities` ON `cities`.`id_city`=`sp_add_org`.`city`
			JOIN `regions` ON `cities`.`id_region`= `regions`.`id_region`
			$sql_where
			ORDER BY id DESC
			LIMIT $start, $num";
	$items=$DB->getAll($query);

	}
endif;
?>