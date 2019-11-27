<? defined('_JEXEC') or die('Restricted access');
if ($user->get_property('userID') > 0):
    if ($_GET['section'] == 'ajax' and $_POST['key'] == $registry['license']) {
        if ($_POST['event'] == 'changeoffice') {
            $id = intval($_POST['rel']);//zakup
            $office = intval($_POST['value']); //office
            $sql = "SELECT * FROM office_set WHERE zp_id='$id' and user='{$user->get_property('userID')}' LIMIT 1";
            $testof = $DB->getAll($sql);
            if (count($testof) > 0) {
                $sql = "UPDATE office_set SET office='$office' WHERE zp_id='$id' and user='{$user->get_property('userID')}' LIMIT 1";
            } else {
                $sql = "INSERT INTO office_set (`user`, `zp_id`, `office`) VALUES ('{$user->get_property('userID')}','$id','$office')";
            }
            $DB->execute($sql);

        }
        exit;
    }
    if (empty($_GET['section']) or $_GET['section'] == 'paywm') {
        $basketStatus = "and `sp_zakup`.`status`>2 and `sp_zakup`.`status`<9 and (SELECT count(a.id) FROM sp_arch a WHERE a.zp_id = sp_zakup.id and a.user='{$user->get_property('userID')}') = 0";
        //вывод в категории для не оплаченных
        $where_py = "and (select count(sp_addpay.id) from `sp_addpay` where `sp_addpay`.`zp_id`=`sp_zakup`.`id` 
and sp_addpay.`user`='{$user->get_property('userID')}' and sp_addpay.`status`='0')=0";
//      status корзины                          status закупки
        if ($_GET['status'] == 3) {
            $basketStatus = " `sp_zakup`.`status`=3";
        }                                                    //Текущие заказы
        if ($_GET['status'] == 4) {
            $basketStatus = " `sp_zakup`.`status`=4";
        }                                                    //Заказы в стопе
        if ($_GET['status'] == 5) {
            $basketStatus = " `sp_zakup`.`status`=5";
        }                                                   //Дозаказы
        if ($_GET['status'] == 6) {
            $basketStatus = " `sp_order`.`user`='{$user->get_property('userID')}' 
        AND `sp_zakup`.`status`=6 
        AND (SELECT !count(sp_addpay.status) FROM `sp_addpay` 
        WHERE `sp_addpay`.`zp_id`=`sp_zakup`.`id` and sp_addpay.`user`='{$user->get_property('userID')}') ";
        }           //Неоплаченные
        if ($_GET['status'] == 7) {
            $basketStatus = " `sp_order`.`user`='{$user->get_property('userID')}' 
        AND (`sp_zakup`.`status`=6 OR `sp_zakup`.`status`=7) 
        AND (SELECT count(sp_addpay.status) FROM `sp_addpay` 
        WHERE `sp_addpay`.`zp_id`=`sp_zakup`.`id` and sp_addpay.`user`='{$user->get_property('userID')}')";
        }            //Оплаченные
        if ($_GET['status'] == 8) {
            $basketStatus = " `sp_zakup`.`status`=8";
            $whord = '3';
        }                                         //Оплата за доставку
        if ($_GET['status'] == 10) {
            $basketStatus = " `sp_zakup`.`status`=10 ";
        }                                                 //Раздача
        if ($_GET['status'] == 9) {
            $basketStatus = "`sp_zakup`.`status`=9 ";
        }                                                    //Закрыта
        if ($_GET['section'] == 'addpay' or $_GET['section'] == 'paywm') ;

        $sql = "SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`paytype`,`sp_zakup`.`user`,`sp_zakup`.`text`,`sp_zakup`.`min`,`sp_zakup`.`proc`
                ,`sp_zakup`.`rekviz`,`sp_zakup`.`status`,`sp_zakup`.`dost`,`sp_zakup`.`proc`,`sp_zakup`.`curs`,
                `sp_zakup`.`foto`,`sp_zakup`.`type`,`sp_zakup`.`office`,`punbb_users`.`username`,`cities`.`city_name_ru`, `sp_status`.`name`,
                (select count(`sp_order`.`id`) from `sp_order` where `sp_order`.`id_zp`=`sp_zakup`.`id`) AS `res`		
                FROM `sp_zakup`
                LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
                JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
                JOIN `sp_order` ON `sp_zakup`.`id`=`sp_order`.`id_zp`
                JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id` 
                WHERE  $basketStatus AND `sp_order`.`user` = '{$user->get_property('userID')}'
                GROUP BY `sp_zakup`.`id`
                ORDER BY `sp_zakup`.`id` DESC";
                $zakup = $DB->getAll($sql);

        foreach ($zakup as $zp):
            if ($zp['type'] == 1 and $_GET['status'] > 0) {
                $statustovar = "and `sp_order`.`status` IN ($whord)";
            } else $statustovar = '';
            $sql = "  select `sp_order`.*,`sp_order`.`message` AS `msg`,`sp_ryad`.`title`,`sp_ryad`.`articul`,`sp_ryad`.`autoblock`,`sp_ryad`.`allblock`,
                        `sp_ryad`.`message`,`sp_ryad`.`price`,`sp_ryad`.`tempOff` ,`sp_size`.`name` as `sizename`,
                        `sp_size`.`anonim`, (SELECT count(s.id) FROM sp_size s WHERE s.id_ryad = sp_size.id_ryad AND s.duble = sp_size.duble AND s.user = 0) AS colvoFreePos
                        from `sp_order` 
                        JOIN `sp_size` ON `sp_order`.`id_order`=`sp_size`.`id`
                        JOIN `sp_ryad` ON `sp_ryad`.`id`=`sp_size`.`id_ryad`
                        where `sp_order`.`id_zp`='" . $zp['id'] . "' and `sp_order`.`user`='{$user->get_property('userID')}' ";
                        $order[$zp['id']] = $DB->getAll($sql);

            $totalprice = 0;
            if (count($order[$zp['id']]) > 0) {
                foreach ($order[$zp['id']] as $ord):

                    if ($ord['kolvo'] == 0) $ord['kolvo'] = 1;

                    if ($ord['status'] == 2 || $ord['status'] == 7) continue;

//                  Заказы во временно отключенных рядах не учитываются
                    if ($ord['tempOff'] == 1) continue;

//                  Считаются заказы во всех статусах, кроме 'Отказано' и 'Нет в наличии'
                    if ($ord['status'] != 2 AND $ord['status'] != 7) $totalprice = $totalprice + ($ord['price'] * $ord['kolvo']) + $ord['tpr'];

//		            if($ord['status'] == 0 OR $ord['status'] == 1 OR $ord['status'] == 9)$totalprice=$totalprice+($ord['price']*$ord['kolvo'])+$ord['tpr'];
//                  В корзине пользователя будут суммироваться ТОЛЬКО заказы со статусом "Включено в счёт" и "Прибыл"
                endforeach;
            } else $order[$zp['id']] = null;
            $totalzp[$zp['id']] = $totalprice;

            $sql = "select *
            FROM `sp_addpay`
            where `zp_id`='" . $zp['id'] . "' and `user`='{$user->get_property('userID')}'";
            $addpay[$zp['id']] = $DB->getAll($sql);

            $sql = "select * FROM `sp_storage`
            where sp_storage.id_zp='" . $zp['id'] . "' and sp_storage.customer_id = '{$user->get_property('userID')}'";
            $onSite[$zp['id']] = $DB->getAll($sql);

        endforeach;
        $sql = "select *
	from `office`
	order by name ASC";
        $office = $DB->getAll($sql);

    }
    if ($_GET['section'] == 'addpay') {
        $basketStatus = "and `sp_zakup`.`status`>2 and `sp_zakup`.`status`<9 and (SELECT count(a.id) FROM sp_arch a WHERE a.zp_id = sp_zakup.id and a.user='{$user->get_property('userID')}') = 0";
        $where_py = "and (select count(sp_addpay.id) from `sp_addpay` where `sp_addpay`.`zp_id`=`sp_zakup`.`id` 
    and sp_addpay.`user`='{$user->get_property('userID')}' and sp_addpay.`status`='0')=0";
        if ($_GET['status'] == 3) {
            $basketStatus = " `sp_zakup`.`status`=3";
            $whord = '0,1,2';
        }           //Текущие заказы
        if ($_GET['status'] == 4) {
            $basketStatus = " `sp_zakup`.`status`=4 $where_py";
            $whord = '100';
        }   //Заказы в стопе
        if ($_GET['status'] == 5) {
            $basketStatus = " `sp_zakup`.`status`=5 $where_py";
            $whord = '100';
        }   //Дозаказы
        if ($_GET['status'] == 6) {
            $basketStatus = " `sp_zakup`.`status`=6";
            $whord = '3';
        }               //Неоплаченные
        if ($_GET['status'] == 7) {
            $basketStatus = "((select count(sp_addpay.status) from `sp_addpay` where `sp_addpay`.`zp_id`=`sp_zakup`.`id` and sp_addpay.`user`='{$user->get_property('userID')}')>0) 
			  and (`sp_zakup`.`status`=5 or `sp_zakup`.`status`=6 or `sp_zakup`.`status`=7)";
            $whord = '4';
        }       //Оплаченные
        if ($_GET['status'] == 8) {
            $basketStatus = " `sp_zakup`.`status`=8";
            $whord = '3';
        }             //Оплата за доставку
        if ($_GET['status'] == 10) {
            $basketStatus = " `sp_zakup`.`status`=10 and (SELECT count(a.id) FROM sp_arch a WHERE a.zp_id = sp_zakup.id and a.user='{$user->get_property('userID')}') = 0";
            $whord = '5';
        } //Раздача
        if ($_GET['status'] == 9) {
            $basketStatus = " (`sp_zakup`.`status`=9 or (SELECT count(a.id) FROM sp_arch a WHERE a.zp_id = sp_zakup.id and a.user='{$user->get_property('userID')}') > 0)";
            $whord = '6';
        } //Закрыта
        if (isset($_GET['status'])) {
            $basketStatus = "and ((($basketStatus and `sp_zakup`.`type`='0') or ($basketStatus and `sp_zakup`.`type`='2'))
			or (`sp_zakup`.`type`='1' and `sp_zakup`.`status`<='9' and 
			(select count(`sp_order`.`id`) from `sp_order` where `sp_order`.`id_zp`=`sp_zakup`.`id` and 
							`sp_order`.`status` IN ($whord) ) > '0' ))";
        } else $basketStatus = "and $basketStatus";
        $id = intval($_GET['value']);
        if ($_GET['section'] == 'addpay' or $_GET['section'] == 'paywm') $basketStatus = "and `sp_zakup`.`id`='$id'";
        $sql = "SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`paytype`,`sp_zakup`.`user`,`sp_zakup`.`text`,`sp_zakup`.`min`,`sp_zakup`.`proc`
                ,`sp_zakup`.`rekviz`,`sp_zakup`.`status`,`sp_zakup`.`dost`,`sp_zakup`.`proc`,`sp_zakup`.`curs`,
                `sp_zakup`.`foto`,`sp_zakup`.`type`,`sp_zakup`.`office`,`punbb_users`.`username`,`cities`.`city_name_ru`, `sp_status`.`name`,
                (select count(`sp_order`.`id`) from `sp_order` where `sp_order`.`id_zp`=`sp_zakup`.`id`) AS `res`
                FROM `sp_zakup`
                LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
                JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
                JOIN `sp_order` ON `sp_zakup`.`id`=`sp_order`.`id_zp`
                JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
                WHERE `sp_order`.`user`='{$user->get_property('userID')}'
                    $basketStatus	
                GROUP BY `sp_zakup`.`id`
                ORDER BY `sp_zakup`.`id` DESC
                ";
        $zakup = $DB->getAll($sql);

        foreach ($zakup as $zp):
            if ($zp['type'] == 1 and $_GET['status'] > 0) {
                $statustovar = "and `sp_order`.`status` IN ($whord)";
            } else $statustovar = '';
            $sql = "  select `sp_order`.*,`sp_order`.`message` AS `msg`,`sp_ryad`.`title`,`sp_ryad`.`articul`,`sp_ryad`.`autoblock`,`sp_ryad`.`allblock`,
	                `sp_ryad`.`message`,`sp_ryad`.`price`,`sp_size`.`name` as `sizename`,
	                `sp_size`.`anonim`, (SELECT count(s.id) FROM sp_size s WHERE s.id_ryad = sp_size.id_ryad AND s.duble = sp_size.duble AND s.user = 0) AS colvoFreePos
	                from `sp_order` 
	                JOIN `sp_size` ON `sp_order`.`id_order`=`sp_size`.`id`
	                JOIN `sp_ryad` ON `sp_ryad`.`id`=`sp_size`.`id_ryad`
	                where `sp_order`.`id_zp`='" . $zp['id'] . "' and `sp_order`.`user`='{$user->get_property('userID')}' $statustovar";
            $order[$zp['id']] = $DB->getAll($sql);
            $totalprice = 0;
            if (count($order[$zp['id']]) > 0) {
                foreach ($order[$zp['id']] as $ord):
                    if ($ord['kolvo'] == 0) $ord['kolvo'] = 1;
                    if ($ord['status'] == 2 || $ord['status'] == 7) continue;
                    if ($ord['status'] != 2 and $ord['status'] != 7) $totalprice = $totalprice + ($ord['price'] * $ord['kolvo']) + $ord['tpr'];
                endforeach;
            } else $order[$zp['id']] = null;
            $totalzp[$zp['id']] = $totalprice;
        endforeach;
    }
    if ($_GET['section'] == 'addpay' and isset($_POST['shopid'])) {
        $idzp = intval($_POST['shopid']);
        $oldid = intval($_POST['oldid']);
        $userID = intval($_POST['userID']); // для возврата
        $date = time();
//        $date_user = PHP_slashes($_POST['date']);
        $amount = floatval($_POST['amount']);
        $card = PHP_slashes($_POST['desc']);
        $transp = intval($_POST['transp']);  //транспортные
        $bankName = $_POST['bankName']; // шо за банк ?
        $whoPay = $_POST['whoPay']; // от кого отправлен платеж
        if ($oldid > 0) $fldop = 1;
        if ($_POST['vozvrat']) {
            $sql = "INSERT INTO `sp_addpay` (`zp_id`,`user`,`date`,`summ`,`card`,`status`,`transp`,`bankName`,`whoPay`) 
	                VALUES ('$idzp','{$userID}','$date','-$amount','$card','$transp','$bankName','$whoPay')";
            $DB->execute($sql);
            header("Location: /com/org/open/$idzp/");
            exit;
        } else {
            if ($oldid > 0) {
                $sql = "UPDATE `sp_addpay` SET status=5 WHERE id = '$oldid'";
                $DB->execute($sql);
            }
            $sql = ' SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`alertcomm`,`punbb_users`.`email`, `sp_zakup`.`user`
			FROM `sp_zakup`
			LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
			WHERE `sp_zakup`.`id` = ' . $idzp;
            $checkemail = $DB->getAll($sql);
            $emailsup = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'emailsup\'');
            $m = new Mail; // начинаем
            $m->From("$emailsup"); // от кого отправляется почта
            $m->To($checkemail[0]['email']); // кому адресованно
            $m->text_html = "text/html";
            $m->Subject("Новое уведомление об оплате к закупке на сайте " . $_SERVER['HTTP_HOST']);
            $m->Body("
Здравствуйте,<br/>
Это письмо отправлено вам сайтом: <a href=\"" . $_SERVER['HTTP_HOST'] . "\">" . $_SERVER['HTTP_HOST'] . "</a><br/>
<p>
Новое уведомление об оплате к вашей закупке  \"" . $checkemail[0]['title'] . "\", от пользователя 
<a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/profile/default/{$user->get_property('userID')}/\">{$user->get_property('username')}</a>.
</p>
Для просмотра перейдите по ссылке:<br/>
<a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $checkemail[0]['id'] . "\">http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $checkemail[0]['id'] . "</a><br/>

");
            $m->Priority(3);    // приоритет письма
            $m->Send();    // а теперь пошла отправка
            $sql = "INSERT INTO `message` (`from`, `to`, `date`,`subject`,`message`,`view`,`tresh`) 
			 VALUES ('" . $user->get_property('userID') . "', '" . $checkemail[0]['user'] . "','" . time() . "',
				'Новое уведомление об оплате к вашей закупке  \"{$checkemail[0]['title']}\"',
				'
				Новое уведомление об оплате к вашей закупке  \"" . $checkemail[0]['title'] . "\", от пользователя 
				<a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/profile/default/{$user->get_property('userID')}/\">{$user->get_property('username')}</a>.

				','0','0')";
            $DB->execute($sql);
            //fldop - это значит доплата
            $sql = "INSERT INTO `sp_addpay` (`zp_id`,`user`,`date`,`date_user`,`summ`,`card`,`fldop`,`transp`,`bankName`,`whoPay`) 
	                VALUES ('$idzp','{$user->get_property('userID')}','$date','$date_user','$amount','$card','$fldop','$transp','$bankName','$whoPay')";
//print_r($sql); die();
            $DB->execute($sql);
            header("Location: /com/basket/?status=" . $_POST['status']);
            exit;
        }
        header("Location: /com/basket/?status=" . $_POST['status']);
        exit;
    }
// todo создаём секцию доп оплаты
    if ($_GET['section'] == 'addpayExtra') {
        $basketStatus = "and `sp_zakup`.`status`>2 and `sp_zakup`.`status`<10 and (SELECT count(a.id) FROM sp_arch a WHERE a.zp_id = sp_zakup.id and a.user='{$user->get_property('userID')}') = 0";
        $where_py = "and (select count(sp_addpay.id) from `sp_addpay` where `sp_addpay`.`zp_id`=`sp_zakup`.`id` 
        and sp_addpay.`user`='{$user->get_property('userID')}' and sp_addpay.`status`='0')=0";
        $id = intval($_GET['value']);
        if ($_GET['section'] == 'addpayExtra') $basketStatus = "and `sp_zakup`.`id`='$id'";
        $sql = "	SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`paytype`,`sp_zakup`.`user`,`sp_zakup`.`text`,`sp_zakup`.`min`,`sp_zakup`.`proc`
		,`sp_zakup`.`rekviz`,`sp_zakup`.`status`,`sp_zakup`.`dost`,`sp_zakup`.`proc`,`sp_zakup`.`curs`,
		`sp_zakup`.`foto`,`sp_zakup`.`type`,`sp_zakup`.`office`,`punbb_users`.`username`,`cities`.`city_name_ru`, `sp_status`.`name`,
		(select count(`sp_order`.`id`) from `sp_order` where `sp_order`.`id_zp`=`sp_zakup`.`id`) AS `res`
	    FROM `sp_zakup`
	    LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
	    JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
	    JOIN `sp_order` ON `sp_zakup`.`id`=`sp_order`.`id_zp`
	    JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
	    WHERE `sp_order`.`user`='{$user->get_property('userID')}'
		$basketStatus	
	    GROUP BY `sp_zakup`.`id`
	    ORDER BY `sp_zakup`.`id` DESC";
        $zakup = $DB->getAll($sql);
// todo Вытаскиваем доплату из БД
        foreach ($zakup as $zp):
            if ($zp['type'] == 1 and $_GET['status'] > 0) {
                $statustovar = "and `sp_order`.`status` IN ($whord)";
            } else $statustovar = '';
            $sql = "  select `sp_order`.*,`sp_order`.`message` AS `msg`,`sp_ryad`.`title`,`sp_ryad`.`articul`,`sp_ryad`.`autoblock`,`sp_ryad`.`allblock`,
	                `sp_ryad`.`message`,`sp_ryad`.`price`,`sp_size`.`name` as `sizename`,
	                `sp_size`.`anonim`, (SELECT count(s.id) FROM sp_size s WHERE s.id_ryad = sp_size.id_ryad AND s.duble = sp_size.duble AND s.user = 0) AS colvoFreePos
	                from `sp_order` 
	                JOIN `sp_size` ON `sp_order`.`id_order`=`sp_size`.`id`
	                JOIN `sp_ryad` ON `sp_ryad`.`id`=`sp_size`.`id_ryad`
	                WHERE `sp_order`.`id_zp`='" . $zp['id'] . "' and `sp_order`.`user`='{$user->get_property('userID')}' $statustovar";
            $order[$zp['id']] = $DB->getAll($sql);

            $totalprice = 0;
            if (count($order[$zp['id']]) > 0) {
                foreach ($order[$zp['id']] as $ord):
                    if ($ord['kolvo'] == 0) $ord['kolvo'] = 1;
                    if ($ord['status'] == 2 || $ord['status'] == 7) continue;
                    if ($ord['status'] != 2 and $ord['status'] != 7) $totalprice = $totalprice + ($ord['price'] * $ord['kolvo']) + $ord['tpr'];
                endforeach;
            } else $order[$zp['id']] = null;
            $totalzp[$zp['id']] = $totalprice;
        endforeach;
        $sql = "SELECT `sp_addpay`.`doplata`, `sp_addpay`.`transp` FROM `sp_addpay`	WHERE `zp_id`='" . $zp['id'] . "' and `user`='{$user->get_property('userID')}'";
        $addpay[$zp['id']] = $DB->getAll($sql);
    }
// Отправка суммы ДОплаты в базу summExtra
    if ($_GET['section'] == 'addpayExtra' and isset($_POST['shopidExtra'])) {
        $idzp = intval($_POST['shopid']);
        $oldid = intval($_POST['oldid']);
        $userID = intval($_POST['userID']); // для возврата
        $date = time();
        $date_user = PHP_slashes($_POST['date']);
        $amount = floatval($_POST['amount']);
        $summExtra = floatval($_POST['summExtra']);                                                                      // delete
        $card = PHP_slashes($_POST['desc']);
        $transp = intval($_POST['transp']);  //транспортные
        $bankName = $_POST['bankName']; // шо за банк ?
        $whoPay = $_POST['whoPay']; // от кого отправлен платеж
        $sql = "UPDATE `sp_addpay` SET summExtra=$summExtra WHERE `zp_id`='" . $zp['id'] . "' and `user`='{$user->get_property('userID')}'";
        $DB->execute($sql);
        header("Location: /com/basket/?status=7");
        exit;
    }
// todo создаём секцию для оплаты за Доставку оплаты
    if ($_GET['section'] == 'addpayTransp') {
        $basketStatus = "AND (`sp_zakup`.`status`= 8 OR `sp_zakup`.`status`= 10) AND (SELECT count(a.id) FROM sp_arch a WHERE a.zp_id = sp_zakup.id and a.user='{$user->get_property('userID')}') = 0";
        $where_py = "and (select count(sp_addpay.id) from `sp_addpay` where `sp_addpay`.`zp_id`=`sp_zakup`.`id` 
        and sp_addpay.`user`='{$user->get_property('userID')}' and sp_addpay.`status`='0')=0";
        $id = intval($_GET['value']);
        if ($_GET['section'] == 'addpayExtra') $basketStatus = "and `sp_zakup`.`id`='$id'";
        $sql = "	SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`paytype`,`sp_zakup`.`user`,`sp_zakup`.`text`,`sp_zakup`.`min`,`sp_zakup`.`proc`
		,`sp_zakup`.`rekviz`,`sp_zakup`.`status`,`sp_zakup`.`dost`,`sp_zakup`.`proc`,`sp_zakup`.`curs`,
		`sp_zakup`.`foto`,`sp_zakup`.`type`,`sp_zakup`.`office`,`punbb_users`.`username`,`cities`.`city_name_ru`, `sp_status`.`name`,
		(select count(`sp_order`.`id`) from `sp_order` where `sp_order`.`id_zp`=`sp_zakup`.`id`) AS `res`
	    FROM `sp_zakup`
	    LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
	    JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
	    JOIN `sp_order` ON `sp_zakup`.`id`=`sp_order`.`id_zp`
	    JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
	    WHERE `sp_order`.`user`='{$user->get_property('userID')}' AND `sp_zakup`.`id` = $id
		$basketStatus	
	    GROUP BY `sp_zakup`.`id`
	    ORDER BY `sp_zakup`.`id` DESC";
        $zakup = $DB->getAll($sql);
// todo Вытаскиваем Доставку из БД
        foreach ($zakup as $zp):
            if ($zp['type'] == 1 and $_GET['status'] > 0) {
                $statustovar = "and `sp_order`.`status` IN ($whord)";
            } else $statustovar = '';
            $sql = "  select `sp_order`.*,`sp_order`.`message` AS `msg`,`sp_ryad`.`title`,`sp_ryad`.`articul`,`sp_ryad`.`autoblock`,`sp_ryad`.`allblock`,
	                `sp_ryad`.`message`,`sp_ryad`.`price`,`sp_size`.`name` as `sizename`,
	                `sp_size`.`anonim`, (SELECT count(s.id) FROM sp_size s WHERE s.id_ryad = sp_size.id_ryad AND s.duble = sp_size.duble AND s.user = 0) AS colvoFreePos
	                from `sp_order` 
	                JOIN `sp_size` ON `sp_order`.`id_order`=`sp_size`.`id`
	                JOIN `sp_ryad` ON `sp_ryad`.`id`=`sp_size`.`id_ryad`
	                WHERE `sp_order`.`id_zp`='" . $zp['id'] . "' and `sp_order`.`user`='{$user->get_property('userID')}' $statustovar";
            $order[$zp['id']] = $DB->getAll($sql);

            $totalprice = 0;
            if (count($order[$zp['id']]) > 0) {
                foreach ($order[$zp['id']] as $ord):
                    if ($ord['kolvo'] == 0) $ord['kolvo'] = 1;
                    if ($ord['status'] == 2 || $ord['status'] == 8) continue;
                    if ($ord['status'] != 2 and $ord['status'] != 8) $totalprice = $totalprice + ($ord['price'] * $ord['kolvo']) + $ord['tpr'];
                endforeach;
            } else $order[$zp['id']] = null;
            $totalzp[$zp['id']] = $totalprice;
        endforeach;
        $sql = "SELECT `sp_addpay`.`transp` FROM `sp_addpay`	WHERE `zp_id`='" . $zp['id'] . "' and `user`='{$user->get_property('userID')}'";
        $addpay[$zp['id']] = $DB->getAll($sql);
    }
// todo Отправка суммы за Доставку в базу summTransp (уведомление об оплате трансп расходов от пользователя)
    if ($_GET['section'] == 'addpayTransp' AND isset($_POST['shopidTransp'])) {
        $summTransp = floatval($_POST['summTransp']);                                                                        // Извлекаем переменную об транспортых от пользователя
        $bankNameTransp = $_POST['bankNameTransp'];
        $whoPayTransp = $_POST['whoPayTransp'];
        $sql = "UPDATE `sp_addpay` 
          SET
            `transpUser` = '$summTransp',
            `bankNameTransp` = '$bankNameTransp',
            `whoPayTransp` = '$whoPayTransp' 
          WHERE `zp_id`='" . $zp['id'] . "' and `user`='{$user->get_property('userID')}'";
        $DB->execute($sql);
        header("Location: /com/basket/?status=8");
        exit;
    }
    if ($_GET['section'] == 'paywm' and isset($_POST['shopid'])) {
        $idzp = intval($_POST['shopid']);
        $date = time();
        $date_user = date('d.m.Y H:i');
        $oldid = intval($_POST['oldid']);
        if ($oldid > 0) $fldop = 1;
        $sql = "	SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`user`,`sp_zakup`.`text`,`sp_zakup`.`min`,`sp_zakup`.`proc`
		,`sp_zakup`.`rekviz`,`sp_zakup`.`status`,`sp_zakup`.`dost`,`sp_zakup`.`proc`,`sp_zakup`.`curs`,
		`sp_zakup`.`foto`,`punbb_users`.`username`,`cities`.`city_name_ru`, `sp_status`.`name`,
		(select count(`sp_order`.`id`) from `sp_order` where `sp_order`.`id_zp`=`sp_zakup`.`id`) AS `res`
	FROM `sp_zakup`
	LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
	JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
	JOIN `sp_order` ON `sp_zakup`.`id`=`sp_order`.`id_zp`
	JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
	WHERE `sp_zakup`.`id`='$idzp'
	";
        $zakup = $DB->getAll($sql);
        if ($zakup[0]['dost'] > 0 and $zakup[0]['status'] > 3):
            $query = "SELECT `sp_ryad`.`price`,`sp_order`.`kolvo`
	                  FROM `sp_order` 
	                  LEFT JOIN `sp_ryad` ON `sp_order`.`id_ryad`=`sp_ryad`.`id`
	                  WHERE `sp_order`.`id_zp` = " . $idzp;
            $tp = $DB->getAll($query);
            $totalprice = 0;
            foreach ($tp as $t) {
                $totalprice = $totalprice + ($t['kolvo'] * $t['price']);
            }
            $userdost = round(($zp['dost'] / 100) * (($totalzp[$zp['id']] * $zp['curs']) / ($totalprice / 100)), 1);
        else: $userdost = 0; endif;
        $ttsumm = ($totalzp[$zp['id']] * $zp['curs']) + round(($totalzp[$zp['id']] * $zp['curs']) / 100 * $zp['proc'], 2) + $userdost;
        if ($fldop == 1) {
            $ttsumm = floatval($_POST['amount']);
            $mess = 'Доплата';
        } else $mess = 'Оплата';
        $amount = floatval($ttsumm);
        if ($ttsumm > $user->get_property('wm')) {
            header("Location: /com/error/nobalance/");
            exit;
        }
        if ($oldid > 0) {
            $sql = "UPDATE `sp_addpay` SET status=5 WHERE id = '$oldid'";
            $DB->execute($sql);
        }
        $card = PHP_slashes($_POST['desc']);
        $sql = "INSERT INTO `sp_addpay` (`zp_id`,`user`,`date`,`date_user`,`summ`,`card`,`status`,`fldop`,'tansp') 
	VALUES ('$idzp','{$user->get_property('userID')}','$date','$date_user','$amount','$mess заказа с кошелька','1','$fldop')";
        $DB->execute($sql);
        $query = "UPDATE `punbb_users` SET `wm` = wm - '$ttsumm'
		WHERE `punbb_users`.`id` ='{$user->get_property('userID')}' LIMIT 1 ;";
        $DB->execute($query);
        $query = "UPDATE `punbb_users` SET `wm` = wm + '$ttsumm'
		WHERE `punbb_users`.`id` ='{$zakup[0]['user']}' LIMIT 1 ;";
        $DB->execute($query);
        $bl1 = $DB->getAll("SELECT wm, username FROM punbb_users WHERE id='{$user->get_property('userID')}' LIMIT 1");
//print_r($bl1);exit;
        $bl2 = $DB->getAll("SELECT wm, username FROM punbb_users WHERE id='{$zakup[0]['user']}' LIMIT 1");
        $sql = "INSERT INTO sp_history (date, user, type, summ, mess, balance)
		VALUES ('$date','{$user->get_property('userID')}','0','$ttsumm','Оплата закупки <a href=\'/com/org/open/$idzp\' class=\'link4\'>#$idzp</a>.
			 Перевод организатору <a href=\'/com/user/default/{$zakup[0]['user']}\' class=\'link4\'>{$bl2[0]['username']}</a>','{$bl1[0]['wm']}')";
        $DB->execute($sql);
        $sql = "INSERT INTO sp_history (date, user, type, summ, mess, balance)
		VALUES ('$date','{$zakup[0]['user']}','1','$ttsumm','Оплата закупки <a href=\'/com/org/open/$idzp\' class=\'link4\'>#$idzp</a>.
			 От пользователя <a href=\'/com/user/default/{$user->get_property('userID')}\' class=\'link4\'>{$bl1[0]['username']}</a>','{$bl2[0]['wm']}')";
        $DB->execute($sql);
        header("Location: /com/basket/?status=" . $_GET['status']);
    }
    if ($_GET['section'] == 'delorder') {
        // скрипт удаления товара из корзины
        $delorder = intval($_GET['value']);
        $query = "SELECT `sp_order`.*, `punbb_users`.`email`,`sp_zakup`.`title`,`sp_zakup`.`alertnews`
		  FROM `sp_order` 
		  LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
		  JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
		  WHERE `sp_order`.`id` = '" . $delorder . "' and `sp_order`.`user`='" . $user->get_property('userID') . "'";
        $order = $DB->getAll($query);
        if (count($order) > 0) {
            $query = "DELETE FROM `sp_order` WHERE `id` = " . $delorder;
            $DB->execute($query);
            $query = "UPDATE sp_size SET `user`='0' WHERE id = " . $order[0]['id_order'];
            $DB->execute($query);
            if ($order[0]['alertnews'] == 1) {
                $emailsup = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'emailsup\'');
                $m = new Mail; // начинаем
                $m->From("$emailsup"); // от кого отправляется почта
                $m->To($order[0]['email']); // кому адресованно
                $m->text_html = "text/html";
                $m->Subject("Заказ удален пользователем - " . $_SERVER['HTTP_HOST']);
                $m->Body("
Здравствуйте,<br/>
Это письмо отправлено вам сайтом: <a href=\"" . $_SERVER['HTTP_HOST'] . "\">" . $_SERVER['HTTP_HOST'] . "</a><br/>
<p>
Пользователь  <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/profile/default/" . $user->get_property('userID') . "\">" . $user->get_property('username') . "</a> 
удалил(а) свой заказ из вашей закупки \"" . $order[0]['title'] . "\".
</p>
<br/>
Для просмотра закупки перейдите по ссылке:<br/>
<a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $order[0]['id_zp'] . "\">http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $order[0]['id_zp'] . "</a><br/>

<p>Вы в любое время можете отключить уведомления о новых комментариях в настройках вашей закупки</p>");
                $m->Priority(3);    // приоритет письма
                $m->Send();    // а теперь пошла отправка
            }
        }
        header("Location: /com/basket?status=3");
    }
    if ($_GET['section'] == 'goarch') {
        $id = intval($_GET['value']);
        $query = "INSERT INTO `sp_arch` (`user`,`zp_id`) VALUES ('{$user->get_property('userID')}','$id')";
        $DB->execute($query);
        header("Location: /com/basket/?status=5");
        exit;
    }
    if ($_GET['section'] == 'editorder') {
        $id_order = intval($_GET['value']);
        $query = "SELECT `sp_order`.*, `sp_ryad`.`title`,`sp_ryad`.`articul`,`sp_ryad`.`price`,
                  `sp_size`.`anonim`,`sp_size`.`name` as `sizename`, `sp_size`.`id` as 'idsize'
                  FROM `sp_order`
                  LEFT JOIN `sp_ryad` ON `sp_order`.`id_ryad`=`sp_ryad`.`id`
                  JOIN `sp_size` ON `sp_order`.`id_order`=`sp_size`.`id`
                  WHERE `sp_order`.`id` = '" . $id_order . "' and `sp_order`.`user`='" . $user->get_property('userID') . "'";
        $items = $DB->getAll($query);


        if ($_POST['action'] == 'editorder') {
            $text = PHP_slashes($_POST['textarea1']);
            $color = PHP_slashes(htmlspecialchars(strip_tags($_POST['color'])));
            $size = PHP_slashes(htmlspecialchars(strip_tags($_POST['size'])));
//            $price = PHP_slashes(htmlspecialchars(strip_tags($_POST['price'])));
            $price = str_replace(" ", "", $_POST['price']);
            $kolvo = intval($_POST['kolvo']);
            $idd = intval($_POST['idd']);
            $title = PHP_slashes(htmlspecialchars(strip_tags($_POST['title'])));
            $articul = intval($_POST['articul']);
            $anonim = intval($_POST['isAnonim']);
            $remains = intval($_POST['remains']);

        if ( $remains - $kolvo >= 0 ) {
            $query = "UPDATE `sp_order` SET
                      `message` = '$text',
                      `color` = '$color',
                      `kolvo` = '$kolvo'
                      WHERE `sp_order`.`id` ='$idd' and `user`= '" . $user->get_property('userID') . "' LIMIT 1 ;";
            $DB->execute($query);
            $query = "UPDATE `sp_size` SET
			          `anonim` = '$anonim',
			          `name` = '$size'
			          WHERE `sp_size`.`id` ='" . $items[0]['idsize'] . "' and `user`= '" . $user->get_property('userID') . "' LIMIT 1 ;";
            $DB->execute($query);
            $query = "UPDATE `sp_ryad` SET
                      `price` = '$price',
                      `title` = '$title',
                      `articul` = '$articul'
                      WHERE `sp_ryad`.`id` ='" . $items[0]['id_ryad'] . "';";
            $DB->execute($query);
        }
        else {
            echo $message = "Вы пытаетесь заказать больше, чем есть в коробке! Свяжитесь с организатором!";
        }

            $oke = 1;
        }

    }
endif;
