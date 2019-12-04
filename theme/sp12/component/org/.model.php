<? defined('_JEXEC') or die('Restricted access');
$_POST['price'] = strval(str_replace(" ", "", $_POST['price'])); //убираем пробел в поле вводе цены
/**
 * @param $str
 * @param string $charset
 * @return string|string[]|null
 */
function str_without_accents($str, $charset = 'utf-8')
{
    $str = htmlentities($str,
        ENT_NOQUOTES,
        $charset);
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    return $str;   // or add this : mb_strtoupper($str); for uppercase :)
}

$registry = null;
if ($_GET['section'] == 'ajax' and $_POST['key'] == $registry['license']) {
    if ($_POST['event'] == 'savecomm') {
        $id = intval($_POST['id']);
        $text = PHP_slashes($_POST['text']);
        $userID = intval($_POST['userID']);
        $username = PHP_slashes($_POST['username']);
        $sql = "SELECT * FROM `comments` WHERE id = '$id'";
        $c = $DB->getAll($sql);
        $c[0]['message'] = $c[0]['message'] . '<div class="comm-body2"><a class="comm-user" href="/com/profile/default/' . $userID . '/">
					' . $username . '</a><span class="comm-date">время: ' . date('d.m.Y H:i') . '</span><p>' . $text . '</p></div>';
        $sql = "UPDATE `comments` SET `message` = '{$c[0]['message']}' WHERE id = '$id'";
        $DB->execute($sql);
        $sql = ' SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`user`,`sp_zakup`.`alertcomm`,`punbb_users`.`email` 
			FROM `sp_zakup`
			LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
			WHERE `sp_zakup`.`id` = ' . intval($_POST['value']);
        $checkemail = $DB->getAll($sql);
        $emailsup = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'emailsup\'');
        if ($_POST['section'] == 'ryad') {
            $sb = "Новый комментарий к ряду на сайте " . $_SERVER['HTTP_HOST'];
            $bt = "Здравствуйте,<br/>
                <p>К вашему ряду оставлен новый комментарий.</p>
                Для просмотра комментария или ответа перейдите по ссылке:<br/>
                <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/org/ryad/" . $checkemail[0]['id'] . "/" . $_POST["value2"] . "\">http://" . $_SERVER['HTTP_HOST'] . "/com/org/ryad/" . $checkemail[0]['id'] . "/" . $_POST["value2"] . "</a><br/>
                <p>Вы в любое время можете отключить уведомления о новых комментариях в настройках вашей закупки</p>";
        } else {
            $sb = "Новый комментарий к закупке \"" . $checkemail[0]['title'] . "\" на сайте " . $_SERVER['HTTP_HOST'];
            $bt = "
Здравствуйте,<br/>
<p>
К вашей закупке  \"" . $checkemail[0]['title'] . "\", оставлен новый комментарий.
</p>
Для просмотра комментария или ответа перейдите по ссылке:<br/>
<a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $checkemail[0]['id'] . "\">http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $checkemail[0]['id'] . "</a><br/>





<p>Вы в любое время можете отключить уведомления о новых комментариях в настройках вашей закупки</p>";
        }
        /*	$sql="	INSERT INTO `message` (`from`, `to`, `date`,`subject`,`message`,`view`,`tresh`)
					VALUES ('".$userID."', '".$checkemail[0]['user']."','".time()."',
						'$sb','$bt','0','0')";
				$DB->execute($sql);
			*/
        $m = new Mail; // начинаем
        $m->From("$emailsup"); // от кого отправляется почта
        $m->To($checkemail[0]['email']); // кому адресованно
        $m->text_html = "text/html";
        $m->Subject($sb);
        $m->Body($bt);
        $m->Priority(3);    // приоритет письма
        $m->Send();    // а теперь пошла отправка
    }
    if ($_POST['event'] == 'changemoder') {
        $id = intval($_POST['rel']);
        $moderate = intval($_POST['value']);
        $sql = "UPDATE sp_order SET status='$moderate' WHERE id='$id' LIMIT 1";
        $DB->execute($sql);
        $sql = "SELECT sp_order.status , `punbb_users`.`email`, `sp_ryad`.`title`
			FROM sp_order 
			JOIN `punbb_users` ON `sp_order`.`user`=`punbb_users`.`id`
			JOIN `sp_ryad` ON `sp_order`.`id_ryad`=`sp_ryad`.`id`
			WHERE sp_order.id='$id' LIMIT 1";
        $orderd = $DB->getAll($sql);
        if ($moderate == 0) $newstatus = 'Новый';
        if ($moderate == 1) $newstatus = 'Включено в счет';
        if ($moderate == 2) $newstatus = 'Отказано';
        if ($moderate == 3) $newstatus = 'Не оплачен';
        if ($moderate == 4) $newstatus = 'Оплачен';
        if ($moderate == 5) $newstatus = 'Раздача';
        if ($moderate == 6) $newstatus = 'Архив';
        if ($moderate == 7) $newstatus = 'Нет в наличии';
        if ($moderate == 8) $newstatus = 'В обработке';
        if ($moderate == 9) $newstatus = 'Прибыл';
		
        $emailsup = $DB->getOne('SELECT `setting`.`value` 
				FROM `setting`
				WHERE `setting`.`name`=\'emailsup\'');
        $m = new Mail; // начинаем
        $m->From("$emailsup"); // от кого отправляется почта
        $m->To($orderd[0]['email']); // кому адресованно
        $m->text_html = "text/html";
        $m->Subject("Статус заказа изменён - " . $_SERVER['HTTP_HOST']);
        $m->Body("
				Здравствуйте,<br/>
				Это письмо отправлено вам сайтом: <a href=\"" . $_SERVER['HTTP_HOST'] . "\">" . $_SERVER['HTTP_HOST'] . "</a><br/>
				<p>
				Организатор изменил(а) статус заказа \"{$orderd[0]['title']}\" на \"" . $newstatus . "\".
				</p><br/>
				<p>Для просмотра заказа перейдите по ссылке:<br/>
				<a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/basket/\">http://" . $_SERVER['HTTP_HOST'] . "/com/basket/</a></p>
				");
        $m->Priority(3);    // приоритет письма
        $m->Send();    // а теперь пошла отправка. ---------------Включение/отключение рассылки.-------------------

        $id_zp = intval($_POST['id_zp']);

//в обработке
        $sql = "SELECT sp_order.kolvo, sp_order.id_ryad, sp_order.status, sp_ryad.price FROM sp_order 
                LEFT JOIN sp_ryad ON sp_ryad.id = sp_order.id_ryad
                WHERE sp_order.id_zp = $id_zp AND sp_order.status = 8";
        $result = $DB->getAll($sql);
        $data1 = 0;
        $count1 = 0;
        foreach ($result as $item){
            $qnt = $item['kolvo'];
            $price = $item['price'];
            $data1 = $data1 + ($qnt * $price);
            $count1++;
        }

//включено в счёт
        $sql = "SELECT sp_order.kolvo, sp_order.id_ryad, sp_order.status, sp_ryad.price FROM sp_order 
                LEFT JOIN sp_ryad ON sp_ryad.id = sp_order.id_ryad
                WHERE sp_order.id_zp = $id_zp AND sp_order.status = 1";
        $result = $DB->getAll($sql);
        $data2 = 0;
        $count2 = 0;
        foreach ($result as $item){
            $qnt = $item['kolvo'];
            $price = $item['price'];
            $data2 = $data2 + ($qnt * $price);
            $count2++;
        }

//отказано
        $sql = "SELECT sp_order.kolvo, sp_order.id_ryad, sp_order.status, sp_ryad.price FROM sp_order 
                LEFT JOIN sp_ryad ON sp_ryad.id = sp_order.id_ryad
                WHERE sp_order.id_zp = $id_zp AND sp_order.status = 2";
        $result = $DB->getAll($sql);
        $data3 = 0;
        $count3 = 0;
        foreach ($result as $item){
            $qnt = $item['kolvo'];
            $price = $item['price'];
            $data3 = $data3 + ($qnt * $price);
            $count3++;
        }

//нет в наличии
        $sql = "SELECT sp_order.kolvo, sp_order.id_ryad, sp_order.status, sp_ryad.price FROM sp_order 
                LEFT JOIN sp_ryad ON sp_ryad.id = sp_order.id_ryad
                WHERE sp_order.id_zp = $id_zp AND sp_order.status = 7";
        $result = $DB->getAll($sql);
        $data4 = 0;
        $count4 = 0;
        foreach ($result as $item){
            $qnt = $item['kolvo'];
            $price = $item['price'];
            $data4 = $data4 + ($qnt * $price);
            $count4++;
        }

//прибыло
        $sql = "SELECT sp_order.kolvo, sp_order.id_ryad, sp_order.status, sp_ryad.price FROM sp_order 
                LEFT JOIN sp_ryad ON sp_ryad.id = sp_order.id_ryad
                WHERE sp_order.id_zp = $id_zp AND sp_order.status = 9";
        $result = $DB->getAll($sql);
        $data5 = 0;
        $count5 = 0;
        foreach ($result as $item){
            $qnt = $item['kolvo'];
            $price = $item['price'];
            $data5 = $data5 + ($qnt * $price);
            $count5++;
        }

        $count_data = [$data1, $data2, $data3, $data4, $data5, $count1, $count2, $count3, $count4, $count5];
        $j_data = json_encode($count_data);
        echo $j_data;
    }

    if ($_POST['event'] == 'payForDelivery'){
        $id_zp = intval($_POST['id_zp']);
        $rel = $_POST['rel'];
        $transpStatus = $_POST['transpStatus'];

        $sql = "UPDATE sp_addpay SET `transpStatus` = $transpStatus WHERE `id` = $rel";
        $DB->execute($sql);

        $sql = "SELECT sp_addpay.transpStatus FROM sp_addpay WHERE id = $rel";
        $result = $DB->getOne($sql);
        $payForDeliveryResult = json_encode([$result, $rel]);
        echo $payForDeliveryResult;
    }

    exit;

}
if (!empty($_POST['add-comm'])) {
    session_start();
    if ($_GET['section'] == 'open') {
        $table = 5;
        $ryad_id = $table;
    }
    if ($_GET['section'] == 'ryad') {
        $table = 51;
        $rev = $_GET["value"];
        $_GET['value'] = $_GET["value2"];
    }

    // Проверка валидности ВОЗВРАЩЁННОГО ключа recaptcha
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(empty($_POST['g-recaptcha-response'])) {
            exit('Вы не подтвердили, что вы не робот!');
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $secret = '6LfdYiMUAAAAAC7Qk6MEhUGBiQEGvTG-7uMlWK9m';
        $recaptcha = $_POST['g-recaptcha-response'];
        $ip = $_SERVER['REMOTE_ADDR'];

        $url_data = $url.'?secret='.$secret.'&response='.$recaptcha.'&remoteip='.$ip;
        $curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$url_data);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);

        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);


        $res = curl_exec($curl);
        curl_close($curl);

        $res = json_decode($res);

        if($res->success) {
            echo 'YES';
        }
        else {
            exit('Error');
        }
    }
    // END Проверка валидности ВОЗВРАЩЁННОГО ключа recaptcha

    if (intval($_POST['login']) == 1):

            if ($user->get_property('userID') > 0):
                $err = 0;
                $mess = PHP_slashes(htmlspecialchars(markhtml($_POST['message'])));
                if (utf8_strlen($mess) == 0):$message = "Введите текст комментария";
                    $err = 1;endif;
                if ($err == 0):
                    $sql = "INSERT INTO `comments` (`news`,`user`,`message`,`date`,`table`)
				VALUE ('" . intval($_GET['value']) . "','" . $user->get_property('userID') . "','$mess','" . time() . "','$ryad_id')";
                    $DB->execute($sql);
                endif;
            else:
                $message = "Ошибка добавления комментария. Обратитесь за помощью к администратору.";
            endif;



            $err = 0;
            $name = PHP_slashes(htmlspecialchars(strip_tags($_POST['name'])));
            $email = PHP_slashes(htmlspecialchars(strip_tags($_POST['email'])));
            $web = PHP_slashes(htmlspecialchars(strip_tags($_POST['web'])));
            $mess = PHP_slashes(htmlspecialchars(markhtml($_POST['message'])));
            if (utf8_strlen($mess) == 0):$message = "Введите текст комментария";
                $err = 1;endif;
            if (email_check($email) and $err == 0):
                $sql = "INSERT INTO `comments` (`news`,`user`,`name`,`email`,`web`,`message`,`date`,`table`)
					VALUE ('" . intval($_GET['value']) . "','0','$name','$email','$web','$mess','" . time() . "','$ryad_id')";
                $DB->execute($sql);
            endif;


    endif;
    if (empty($message)) {
        $sql = ' SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`alertcomm`,`punbb_users`.`email` 
			FROM `sp_zakup`
			LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
			WHERE `sp_zakup`.`id` = ' . intval($_GET['value']);
        $checkemail = $DB->getAll($sql);
        $emailsup = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'emailsup\'');
        if ($_GET['section'] == 'ryad') {
            $sb = "Новый комментарий к ряду на сайте " . $_SERVER['HTTP_HOST'];
            $bt = "Здравствуйте,<br/>
                <p>К вашему ряду оставлен новый комментарий.</p>
                Для просмотра комментария или ответа перейдите по ссылке:<br/>
                <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/org/ryad/" . $checkemail[0]['id'] . "/" . $_GET["value2"] . "\">http://" . $_SERVER['HTTP_HOST'] . "/com/org/ryad/" . $checkemail[0]['id'] . "/" . $_GET["value2"] . "</a><br/>
                <p>Вы в любое время можете отключить уведомления о новых комментариях в настройках вашей закупки</p>";
        } else {
            $sb = "Новый комментарий к закупке \"" . $checkemail[0]['title'] . "\" на сайте " . $_SERVER['HTTP_HOST'];
            $bt = "
Здравствуйте,<br/>
<p>
К вашей закупке  \"" . $checkemail[0]['title'] . "\", оставлен новый комментарий.
</p>
Для просмотра комментария или ответа перейдите по ссылке:<br/>
<a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $checkemail[0]['id'] . "\">http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $checkemail[0]['id'] . "</a><br/>





<p>Вы в любое время можете отключить уведомления о новых комментариях в настройках вашей закупки</p>";
        }
        $m = new Mail; // начинаем
        $m->From("$emailsup"); // от кого отправляется почта
        $m->To($checkemail[0]['email']); // кому адресованно
        $m->text_html = "text/html";
        $m->Subject($sb);
        $m->Body($bt);
        $m->Priority(3);    // приоритет письма
        $m->Send();    // а теперь пошла отправка
        if ($_GET['section'] == 'open') {
            header("Location: /com/org/open/{$_GET["value"]}/");
        }
        if ($_GET['section'] == 'ryad') {
            header("Location: /com/org/ryad/{$rev}/{$_GET["value2"]}/");
        }

    }
    $scroll = '100000000';
}
if ($_GET['section'] == 'exportr') {
    if ($user->get_property('gid') != 25) $onlystatus = '`sp_zakup`.`user`=' . $user->get_property('userID') . ' and ';
    $sql = "	SELECT `sp_zakup`.* , `sp_status`.`name`,`punbb_users`.`username`,`sp_level`.`name` as `levname`,`cities`.`city_name_ru`
                FROM `sp_zakup`
                LEFT JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
                JOIN `sp_level` ON `sp_zakup`.`level`=`sp_level`.`id`
                JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
                JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
                WHERE $onlystatus `sp_zakup`.`id`=" . intval($_GET['value']);
    $openzakup = $DB->getAll($sql);

    if ($_GET['togo'] == '1' and $user->get_property('gid') >= 23 and intval($_GET['value']) > 0) {
        $id = intval($_GET['value']);
        if ($user->get_property('gid') != 25) $onlystatus = "and r.user='{$user->get_property('userID')}'";
        $sql = "SELECT r.* FROM sp_ryad r WHERE r.id_zp='$id' $onlystatus
		        ORDER BY position ASC ";
        $allryad = $DB->getAll($sql);

        $ar = ['À' => 'A', 'à' => 'a', 'Á' => 'A', 'á' => 'a', 'Â' => 'a', 'â' => 'a',
            'Ã' => 'a', 'ã' => 'a', 'Ä' => 'A', 'ä' => 'a', 'Å' => 'A', 'å' => 'a',
            'Æ' => 'a', 'æ' => 'a', 'Ç' => 'C', 'ç' => 'c', 'Ð' => 'E', 'ð' => 'e',
            'È' => 'E', 'è' => 'e', 'É' => 'E', 'é' => 'e', 'Ê' => 'E',
            'ê' => 'e', 'Ë' => 'E', 'ë' => 'e', 'Ì' => 'I', 'ì' => 'i', 'Í' => 'I',
            'í' => 'i', 'Î' => 'I', 'î' => 'i', 'Ï' => 'I', 'ï' => 'i', 'Ñ' => 'N',
            'ñ' => 'n', 'Ò' => 'O', 'ò' => 'o', 'Ó' => 'O', 'ó' => 'o', 'Ô' => 'O',
            'ô' => 'o', 'Õ' => 'O', 'õ' => 'o', 'Ö' => 'O', 'ö' => 'o', 'Ø' => 'O',
            'ø' => 'o', 'Œ' => 'O', 'œ' => 'o', 'ß' => 's', 'Þ' => 'T', 'þ' => 't',
            'Ù' => 'U', 'ù' => 'u', 'Ú' => 'U', 'ú' => 'u', 'Û' => 'U', 'û' => 'u',
            'Ü' => 'U', 'ü' => 'u', 'Ý' => 'Y', 'ý' => 'y', 'Ÿ' => 'Y', 'ÿ' => 'y'];
        if (count($allryad) > 0):
            if ($allryad[0]['user'] != $user->get_property('userID') and $user->get_property('gid') != 25) exit;
            $out = "#ID;Заголовок;Описание;Артикул;Цена;Размеры;Фото(url);Категория(Фильтр);Отключка\n";
            $out = iconv('UTF-8', 'WINDOWS-1251', $out);
            foreach ($allryad as $num):
                $num['title'] = str_replace(';', ',', htmlspecialchars_decode($num['title']));
                $num['articul'] = str_replace(';', ',', htmlspecialchars_decode($num['articul']));
                $num['message'] = str_replace('
', ' ', str_replace(';', ',', htmlspecialchars_decode($num['message'])));
                $num['message'] = str_replace('"', "'", $num['message']);
                $num['price'] = str_replace('\'', ',', htmlspecialchars_decode($num['price']));
                $num['size'] = str_replace('\'', ',', htmlspecialchars_decode($num['size']));
                $num['photo'] = str_replace('\'', ',', htmlspecialchars_decode($num['photo']));
                $str = "{$num['position']};{$num['title']};\"{$num['message']}\";{$num['articul']};{$num['price']};{$num['size']};{$num['photo']};{$num['cat']};{$num['tempOff']}\n";
                $str = strtr($str, $ar);
                $str = iconv('UTF-8', 'WINDOWS-1251', $str);
                $out .= $str;
            endforeach;
//echo $out;exit;
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"sp.zakup$id.csv\"");
            //$out=str_without_accents($out);
            //setlocale(LC_ALL, 'ru_RU.utf8');
            //iconv_set_encoding('internal_encoding','UTF-8');
            //$out = iconv('UTF-8','WINDOWS-1251',$out);
            echo $out;
            exit;
        else:
            echo 'Нет товаров для экспорта';
        endif;
        exit;
    }
    if ($_POST['import'] == '1' and $user->get_property('gid') >= 23 and intval($_GET['value']) > 0) {
        if ($_FILES["file"]["size"] > 0) {
            $userID = $user->get_property('userID');
            $date = time();
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                $filename = $_FILES['file']['tmp_name'];
                $ext = substr($_FILES['file']['name'],
                    1 + strrpos($_FILES['file']['name'], "."));
                $valid_types = array('csv', 'CSV');
                if (!in_array($ext, $valid_types)) {
                    $message = "Ошибка: Данный формат фото не поддерживается. Выберите для загрузки файл в формате: CSV";
                } else {
                    $dir = "tmp";
                    $newname = rand(100000, 99999999);
                    while (file_exists("$dir/$newname.$ext")) $newname = rand(100000, 99999999);
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0777, true);
                    }
                    if (@move_uploaded_file($filename, "$dir/$newname.$ext")) {
                        $i = 0;
                        $add = 0;
                        $update = 0;
                        $continue = 0;
                        $file_handle = fopen("$dir/$newname.$ext", "r");
                        while (!feof($file_handle)) {
                            $line = fgets($file_handle);
                            $i++;
                            if ($i == 1) continue;
                            $item = explode(';', $line);
                            if (count($item) > 1) {
                                $idpos = intval($item[0]);
                                $title = PHP_slashes($item[1]);
                                $message = PHP_slashes($item[2]);
                                $articul = PHP_slashes($item[3]);
                                $price = floatval($item[4]);
                                $size = PHP_slashes($item[5]);
                                $photo = PHP_slashes($item[6]);
                                $cat = PHP_slashes(strip_tags(trim($item[7])));
                                $tempOff = $item[8];
                                $tempOff = str_replace(array("\r", "\n"), "", $tempOff);
                                $id_zp = intval($_GET['value']);
                                $testcheck = $DB->getOne("SELECT count(id) FROM sp_ryad WHERE id_zp='$id_zp' and position='$idpos'");
                                if ($idpos > 0 and $testcheck > 0) {
                                    if ($photo > '') $sqlph = ", `photo` = '$photo'"; else $sqlph = '';
                                    $update++;
                                    $sql = "UPDATE `sp_ryad` SET
                                            `title` = '$title',
                                            `message` = '$message',
                                            `articul` = '$articul',
                                            `price` = '$price',
                                            `tempOff` = $tempOff,
                                            `cat` = '$cat',
                                            `size` = '$size'  $sqlph
							WHERE `position` = '$idpos' and `id_zp`='$id_zp' LIMIT 1";
                                } else {
                                    $position = $DB->getOne("SELECT MAX(position) FROM sp_ryad WHERE id_zp='$id_zp'");
                                    $position = $position + 1;
                                    $add++;
                                    $sql = "INSERT INTO `sp_ryad` 
                                            (`title`,`message`,`articul`,`price`,`size`,`photo`,`position`,`user`,`id_zp`,`spec`,`auto`,`cat`,`duble`, `tempOff`)
							                VALUE 
							                ('$title','$message','$articul','$price','$size','$photo','$position','{$user->get_property('userID')}','$id_zp','1','1','$cat','1','$tempOff');";
                                }
                                $sql = iconv('windows-1251', 'UTF-8', $sql);
                                $DB->execute($sql);
                                if ($testcheck == 0) { // add
                                    $last_id = $DB->id;
                                    $size = explode(',', $size);
                                    $query_size = '';
                                    if (count($size) == 1) {
                                        if (is_numeric($size[0]) and intval($size[0]) < 10) {
                                            for ($i = 0; $i < intval($size[0]); $i++) {
                                                $query_size = "INSERT INTO sp_size VALUES('','$last_id',$id_zp,'','','1','');";
                                                $DB->execute($query_size);
                                            }
                                        } else {
                                            $query_size = "INSERT INTO sp_size VALUES('','$last_id','" . $size[0] . "','','1','');";
                                            $DB->execute($query_size);
                                        }
                                    }
                                    if (count($size) > 1) {
                                        foreach ($size as $si) {
                                            $si = PHP_slashes(trim($si));
                                            if ($si > '') {
                                                $query_size = "INSERT INTO sp_size VALUES('','$last_id','" . $si . "','','1','');";
                                                $DB->execute($query_size);
                                            }
                                        }
                                    }
                                }//
                            }
                        }
                        fclose($file_handle);
                        $message = "Импорт успешно завершен.<br/>Добавлено: $add записей<br/>
						Обновлено: $update записей<br/> Пропущено: $continue записей";
                        @unlink("$dir/$newname.$ext");
                    }
                }
            }
        } else {
            $message = "Ошибка: Проверьте корректность файла";
        }

    }

}
if ($_GET['section'] == 'open' or $_GET['section'] == 'ryad' or $_GET['section'] == 'delsel') {
    if ($_GET['section'] == 'open') {
        $table = 5;
        $nws = intval($_GET["value"]);
    }
    if ($_GET['section'] == 'ryad') {
        $table = 51;
        $nws = intval($_GET["value2"]);
    }
    if ($user->get_property('gid') > 0) {
        $sql = "SELECT count(id) FROM subs WHERE `id_post` = '$nws' and `user` = '{$user->get_property('userID')}' and `table` = '$table'";
        $testSubs = $DB->getOne($sql);

    }
    $registry['premoder'] = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'premoder\'');
    if ($user->get_property('gid') == 0 or $user->get_property('gid') == 18) $onlystatus = '`sp_zakup`.`status`>2 and `sp_zakup`.`status`<=9 and ';
    $sql = "	SELECT `sp_zakup`.* , `sp_status`.`name`,`punbb_users`.`username`, `punbb_users`.`phone`, `sp_level`.`name` as `levname`,`cities`.`city_name_ru`
		FROM `sp_zakup`
		LEFT JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
		JOIN `sp_level` ON `sp_zakup`.`level`=`sp_level`.`id`
		JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
		JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
		WHERE $onlystatus `sp_zakup`.`id`=" . intval($_GET['value']);
    $openzakup = $DB->getAll($sql);
    if ($openzakup[0]['level'] == 3 and $user->get_property('gid') == 0) unset($openzakup);
    if ($openzakup[0]['status'] < 3 and $openzakup[0]['user'] <> $user->get_property('userID') and $user->get_property('gid') < 25) unset($openzakup);
    if (floatval($openzakup[0]['curs']) == 0 and count($openzakup) == 1) $openzakup[0]['curs'] = 1;
    if ($openzakup[0]['user'] == $user->get_property('userID') OR $user->get_property('gid') == 25):
        $query = "SELECT * FROM `sp_status`";
        $statuslist = $DB->getAll($query);
    endif;
    if (count($openzakup) == 1):
        if (!empty($openzakup[0]['foto'])) {
            $split = explode('/', $openzakup[0]['foto']);
            $img_path = '/images/' . $split[2] . '/125/100/1/' . $split[3];
        } else $img_path = '/' . $theme . 'images/no_photo125x100.png';
//, o.tpr
        $query = "SELECT o.id, o.id_order, o.id_ryad, o.date, o.kolvo, o.message, o.color, o.status
			, o.user, `punbb_users`.`username`, `punbb_users`.`group_id`
			FROM `sp_order` AS o
			LEFT JOIN `punbb_users` ON o.user=`punbb_users`.`id`
			WHERE o.`id_zp` = '" . intval($_GET['value']) . "' ORDER BY o.id DESC";
        $allorder = $DB->getAll($query);
        $items_total_all = 0;
        $items_qnt_all = 0;
        $allsize = [];
        foreach ($allorder as $aord) {
            $query = "SELECT * FROM `sp_ryad` WHERE `id` = " . $aord['id_ryad'];
            $allryad = $DB->getAll($query);
            $query = "SELECT `sp_size`.`anonim`, `sp_size`.`name`,`sp_size`.`user`,
				`sp_ryad`.`size`,`sp_ryad`.`spec`
			  FROM `sp_size` 
			  JOIN `sp_ryad` ON `sp_size`.`id_ryad`=`sp_ryad`.`id`
			  WHERE `sp_size`.`id` = " . $aord['id_order'];
            $allsize_tmp = $DB->getAll($query);
            $allryad[1] = $aord['date'];
            $allryad[2] = $aord['kolvo'];
            $allryad[3] = $allsize_tmp[0]['anonim'];
            $allryad[4] = $aord['user'];
            $allryad[5] = $aord['username'];
            $allryad[6] = $allsize_tmp[0]['name'];
            $allryad[7] = $aord['color'];
            $allryad[8] = $aord['id'];
            $allryad[9] = $aord['status'];
            $allryad[11] = $aord['group_id'];
            $allryad[10] = htmlspecialchars_decode($aord['message']);
            if (count($allsize) < 10) {
                $allsize[] = $allryad;
            }
            if ($aord['status'] != 2) {
                $items_total_all = $items_total_all + ($allryad[0]['price'] * $aord['kolvo'] * $openzakup[0]['curs']);
            }   //сумма в рублях
            if ($aord['status'] != 2) {
                $items_qnt_all = $items_qnt_all + $aord['kolvo'];
            }
        }
        unset($allryad);
        unset($allorder);
        $onepc = $openzakup[0]['min'] / 100;
        $items_total_all = round($items_total_all / $onepc);                                                        //процент в рублях
        if ($items_total_all < 100) {
            $items_total_width = $items_total_all;
        } else {
            $items_total_width = 100;
        }      //процент в рублях до 100
        $justQnt = $items_qnt_all;
        $items_qnt_all = round($items_qnt_all / $onepc);                                                            //процент в штуках
        if ($items_qnt_all < 100) {
            $items_qnt_width = $items_qnt_all;
        } else {
            $items_qnt_width = 100;
        }              //процент в рублях до 100%
        // Первоначальный вариант от создателя, показывающий неверное количество заказов внутри закупки
//        $sql = "SELECT count(`sp_size`.`id`)
//		FROM `sp_size`
//		LEFT JOIN `sp_ryad` ON `sp_size`.`id_ryad`=`sp_ryad`.`id`
//		WHERE `sp_ryad`.`id_zp`='" . intval($_GET['value']) . "' and `sp_size`.`user`>''";
//        $total_order_zp = $DB->getOne($sql);
        // Переделанный вариант от меня, показывающий верное количество заказов внутри закупки
        $sql = "SELECT count(`sp_order`.`id`)                                                                           
		FROM `sp_order`
		WHERE `sp_order`.`id_zp`='" . intval($_GET['value']) . "' and `sp_order`.`user`>''";
        $total_order_zp = $DB->getOne($sql);

        $all_comments = $DB->getAll(' SELECT `comments`.*, `punbb_users`.`username` 
                            FROM `comments` LEFT JOIN `punbb_users` ON `comments`.`user`=`punbb_users`.`id`
                            WHERE `comments`.`news`= \'' . intval($nws) . '\' and `comments`.`table`=' . $table . '
                            ORDER BY `comments`.`id` ASC');

        if ($testSubs > 0) {
            $lastcomm = $all_comments[(count($all_comments) - 1)]['id'];
            $sql = "UPDATE subs SET `lastcomm` = '$lastcomm' WHERE `id_post` = '$nws' and `user` = '{$user->get_property('userID')}' and `table` = '$table'";
            $DB->execute($sql);
        }

        $query = "SELECT * FROM `sp_delivery`";
        $delivery = getAllcache($query, 6000);
        $query = "SELECT sp_addpay.*, punbb_users.username, 
                  (SELECT sum(sp_ryad.price*sp_order.kolvo)  FROM sp_order LEFT JOIN sp_ryad ON sp_order.id_ryad=sp_ryad.id WHERE sp_order.user=sp_addpay.user 
				    and sp_order.id_zp=sp_addpay.zp_id and sp_order.status = 1 ) AS tprice,
				    (SELECT sum(sp_order.oversize) FROM sp_order WHERE sp_order.user=sp_addpay.user AND sp_order.id_zp=sp_addpay.zp_id) AS over,
				  (SELECT office_set.office FROM office_set WHERE office_set.user=sp_addpay.user 
				    and office_set.zp_id=sp_addpay.zp_id LIMIT 1) AS officeid FROM `sp_addpay` 
				    LEFT JOIN `punbb_users` ON `sp_addpay`.`user`=`punbb_users`.`id` WHERE sp_addpay.zp_id = '" . intval($_GET['value']) . "' ORDER BY sp_addpay.id DESC";
        $addpay = $DB->getAll($query);
		
//Создаём список ВСЕХ пользователей в данной закупке для  новой версии таблицы "оплата". На основе sp_order, а не sp_addpay.
        $sql = "SELECT sp_order.user as orderUserNumber, punbb_users.username, sp_addpay.*,
            (SELECT sum(sp_ryad.price*sp_order.kolvo)  FROM sp_order LEFT JOIN sp_ryad ON sp_order.id_ryad=sp_ryad.id 
              WHERE sp_order.user=sp_addpay.user and sp_order.id_zp=sp_addpay.zp_id and (sp_order.status = 1 OR sp_order.status = 9)) AS tprice
            FROM sp_order
            LEFT JOIN punbb_users ON sp_order.user = punbb_users.id
            LEFT JOIN sp_addpay ON sp_order.user = sp_addpay.user AND sp_order.id_zp = sp_addpay.zp_id
            WHERE sp_order.id_zp = ' ".intval($_GET['value'])." ' GROUP BY sp_order.user ORDER BY sp_addpay.id DESC";
        $all_payers = $DB->getAll($sql);

        $sql = "select * from `office` order by name ASC";
        $office = $DB->getAll($sql);
        $tmp = [];
        foreach ($office as $item) {
            $tmp[$item['id']] = $item;
        }
        $office = $tmp;
        unset($tmp);
    endif;
    $sort = 'position ASC';
    if ($_POST['typePrice'] || $_COOKIE['typePrice']) {
        $registry['typePrice'] = $_POST['typePrice'] ? intval($_POST['typePrice']) : $_COOKIE['typePrice'];
        if ($registry['typePrice'] == 1) $sort = 'price ASC';
        if ($registry['typePrice'] == 2) $sort = 'price DESC';
        if ($registry['typePrice'] == 3) $sort = 'position ASC';
        setcookie('typePrice', $registry['typePrice'], time() + 360000, '/');
    }
    if ($_POST['typeSize'] || $_COOKIE['typeSize']) {
        $registry['typeSize'] = $_POST['typeSize'] ? PHP_slashes($_POST['typeSize']) : $_COOKIE['typeSize'];
        setcookie('typeSize', $registry['typeSize'], time() + 360000, '/');
        $wh_s = "and (SELECT count(`sp_size`.`id`) FROM `sp_size` WHERE `sp_size`.`id_ryad`=`sp_ryad`.`id` and `sp_size`.`name` LIKE '{$registry['typeSize']}') > 0";
    }
    if (isset($_POST['typeView'])) {
        $registry['short'] = $_POST['typeView'];
        setcookie('typeView', $_POST['typeView'], time() + 3600 * 60 * 31, '/');
    } elseif (isset($_COOKIE['typeView'])) {
        $registry['short'] = $_COOKIE['typeView'];
    } else {
        $registry['short'] = 1;
    }

}
if ($_GET['section'] == 'vieworder' || $_GET['section'] == 'move') {
    $sql = "SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`curs`,`cities`.`city_name_ru`,
			`sp_zakup`.`proc`,`sp_zakup`.`user`,`sp_zakup`.`type`,`sp_zakup`.`dost`,`sp_zakup`.`status`
		FROM `sp_zakup`
		LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
		JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
		WHERE `sp_zakup`.`id`=" . intval($_GET['value']);
    $openzakup = $DB->getAll($sql);

    if ($_GET['section'] == 'move') {
        $idm = null;
        $move_sql = " and o.id = '$idm'";
        $sql = "SELECT `sp_zakup`.`id`,`sp_zakup`.`title`
		FROM `sp_zakup`
		WHERE `sp_zakup`.`user`='{$openzakup[0]['user']}' and `sp_zakup`.`id`!='{$openzakup[0]['id']}'
			and `sp_zakup`.`status`>=3 and `sp_zakup`.`status`<9";
        $allZP = $DB->getAll($sql);
    }
    if ($_GET['u']) {
        $sqlus = " and o.user='" . intval($_GET['u']) . "' ";
    } else $sqlus = '';
    //Добавляем o.oversize в запрос
    $query = "SELECT o.id, o.id_order, o.id_ryad, o.date, o.kolvo, o.oversize, o.message, o.color, o.status
			, o.user, `punbb_users`.`username`, `punbb_users`.`group_id`, `punbb_users`.`wm`,
			`sp_ryad`.`tempOff`, `sp_ryad`.`duble`, 
			(select office.name 
				from office_set
				left join office ON office.id =office_set.office
				 where office_set.user = o.user and office_set.zp_id = o.`id_zp`) AS name_office
			FROM `sp_order` AS o
			LEFT JOIN `punbb_users` ON o.user=`punbb_users`.`id`
			LEFT JOIN `sp_ryad` ON sp_ryad.id = o.id_ryad
			WHERE o.`id_zp` = '" . intval($_GET['value']) . "' $sqlus ORDER BY o.id DESC";
    $allorder = $DB->getAll($query);
    $items_total_all = 0;
//    $allsize = [];

    foreach ($allorder as $aord) {
        $query = "SELECT * FROM `sp_ryad` WHERE `id` = " . $aord['id_ryad'];
        $allryad = $DB->getAll($query);

        $query = "SELECT name, user, duble, anonim   
			  FROM sp_size 
			  WHERE `sp_size`.`id` = " . $aord['id_order'];
        $allsize_tmp = $DB->getAll($query);

        $allryad[1] = $aord['date'];
        $allryad[2] = $aord['kolvo'];
        $allryad[3] = $allsize_tmp[0]['anonim'];
        $allryad[4] = $aord['user'];
        $allryad[5] = $aord['username'];
        $allryad[6] = $aord['message'];
        $allryad[7] = $allsize_tmp[0]['name'];
        $allryad[8] = $aord['color'];
        $allryad[9] = $aord['status'];
        $allryad[10] = $aord['id'];
        $allryad[11] = htmlspecialchars_decode($aord['message']);
        $allryad[12] = $aord['name_office'];
        $allryad[13] = $aord['group_id'];
        $allryad[14] = $aord['oversize'];                                       // Вытаскивем наценку за крупногабарит
        $allryad[15] = $aord['wm'];                                          // пользователь WhatsApp?
        $allryad[16] = $aord['tempOff'];                                          // временное отключения ряда
        $allryad[17] = $aord['duble'];                                      //дублирование рядов. Т.е., количество рядов
        $allryad[18] = $allsize_tmp[0]['name'];                                       //количество позиций в ряде
        $allryad['id_order'] = $aord['id_order'];
        $allryad['current_row'] = $allsize_tmp[0]['duble'];

        $allsize[] = $allryad;
        $items_total_all = $items_total_all + ($allryad[0]['price'] * $aord['kolvo']);
    }
    unset($allryad);
    unset($allorder);
    $query = "SELECT sp_addpay.*
			FROM `sp_addpay` 
			WHERE sp_addpay.zp_id = '" . intval($_GET['value']) . "' ORDER BY sp_addpay.id DESC";
    $addpay = $DB->getAll($query);
    $addpayN = array();
    foreach ($addpay as $item) {
        $addpayN[$item['user']] = $item;
    }
}
// todo Делаем секцию для транспортных платежей
if ($_GET['section'] == 'transPay' OR $_GET['section'] == 'testPage') {
    $sql = "SELECT sp_addpay.*, punbb_users.username, (SELECT sum(sp_ryad.price*sp_order.kolvo) FROM sp_order 
				LEFT JOIN sp_ryad ON sp_order.id_ryad=sp_ryad.id WHERE sp_order.user=sp_addpay.user 
				and sp_order.id_zp=sp_addpay.zp_id and sp_order.status = 1) AS tprice FROM `sp_addpay` 
			LEFT JOIN `punbb_users` ON `sp_addpay`.`user`=`punbb_users`.`id` WHERE sp_addpay.zp_id = '" . intval($_GET['value']) . "' ORDER BY punbb_users.username ASC";
    $summ = $DB->getAll($sql);

    $sql = "SELECT sp_addpay.*, punbb_users.username, (SELECT sum(sp_ryad.price*sp_order.kolvo) FROM sp_order 
				LEFT JOIN sp_ryad ON sp_order.id_ryad=sp_ryad.id WHERE sp_order.user=sp_addpay.user 
				and sp_order.id_zp=sp_addpay.zp_id and sp_order.status = 1) AS tprice FROM `sp_addpay` 
			LEFT JOIN `punbb_users` ON `sp_addpay`.`user`=`punbb_users`.`id` WHERE sp_addpay.zp_id = 1133 ORDER BY punbb_users.username ASC";
    $testSQL = $DB->getAll($sql);

    // Вытаскиваем название закупки для заголовка
    $sql = "SELECT `sp_zakup`.`title`, `sp_zakup`.`user` FROM sp_zakup WHERE `sp_zakup`.`id` = '" . intval($_GET['value']) . "' ";
    $title = $DB->getAll($sql);
    if ($_POST['action'] == 'transPay') {
        $usr = $_POST['userID'];
        $summ = $_POST['transpSumm'];
        $transpStatus = $_POST['transpStatus'];
        foreach (array_combine($usr, $summ) AS $specificUser => $specificSumm) {
            $sql = "UPDATE sp_addpay SET `transp` = '$specificSumm' WHERE `zp_id` = '" . intval($_GET['value']) . "' and `user` = '$specificUser'";
            $DB->execute($sql);
        };
/*        foreach (array_combine($usr, $transpStatus) AS $specificUser => $specificStatus) {
            $sql = "UPDATE sp_addpay SET `transpStatus` = '$specificStatus' WHERE `zp_id` = '" . intval($_GET['value']) . "' and `user` = '$specificUser'";
            $DB->execute($sql);
        };*/
        header('Location: /com/org/transPay/' . $_GET['value']);
        exit;
    };

}
//todo Секция уведомления от имени другого пользователя
if ($_GET['section'] == 'notification'){
    $id_zp = intval($_GET['value']);
//Создание списка всех пользователей, участвующих в закупке
        $sql = "SELECT sp_order.user, sp_order.id_zp, punbb_users.username,sp_addpay.user AS paidUser 
                FROM sp_order 
                LEFT JOIN punbb_users ON punbb_users.id = sp_order.user
                LEFT JOIN sp_addpay ON sp_order.user = sp_addpay.user AND sp_addpay.zp_id = sp_order.id_zp
                WHERE sp_order.id_zp = '" .intval($_GET['value']). "' GROUP BY sp_order.user";
        $listAllUsers = $DB->getAll($sql);

//Создание списка оплативших пользователей
/*        $sql = "SELECT sp_addpay.user FROM sp_addpay WHERE sp_addpay.zp_id = " .intval($_GET['value']);
        $paidUser = $DB->getAll($sql);*/

// Вытаскиваем название закупки для заголовка
        $sql = "SELECT `sp_zakup`.`title`, `sp_zakup`.`user` FROM sp_zakup WHERE `sp_zakup`.`id` = '" . intval($_GET['value']) . "' ";
        $title = $DB->getAll($sql);

//Запись данных в БД
        if($_POST['action'] == 'notification'){
            $forUser = $_POST['forUser'];
            $bankName = $_POST['bank'];
            $summ = $_POST['summ'];
			$whoPay = $_POST['whoPay'];
            $date = time();

                if (!isset($forUser)){$error1 = "За кого уведомляешь то ?";}
                elseif (!isset($bankName)){$error2 = "Банк я за тебя буду указывать ?";}
                elseif (empty($summ)){$error3 = "А сумма то где ?";}
                elseif (empty($whoPay)){$error4 = "И от кого денежки поступили, интересно ?";}
                else {$sql = "INSERT INTO `sp_addpay` (`zp_id`,`user`,`date`,`summ`,`bankName`, `whoPay`) 
	                VALUES ('$id_zp','$forUser','$date','$summ','$bankName', '$whoPay')";
                    $DB->execute($sql);
                    header('Location: /com/org/open/' .$id_zp);
                }

        };
}

//todo Перенос заказов в другой выкуп
if ($_GET['section'] == 'move') {
    if ($_POST['move']) {
        $id_zp = intval($_POST['id_zp']);
        $id_o = intval($_POST['id_o']);
        $query = "SELECT o.* FROM `sp_order` AS o WHERE o.`id` = '$id_o'";
        $order = $DB->getAll($query);

        $query = "CREATE TEMPORARY TABLE foo AS SELECT * FROM sp_ryad WHERE id = '{$order[0]['id_ryad']}';";
        $DB->execute($query);

        $query = "UPDATE foo SET `id` = NULL,`id_zp`='$id_zp', `auto`='0', `spec`='0', `position`='0';";
        $DB->execute($query);

        $query = "INSERT INTO sp_ryad SELECT * FROM foo;";
        $DB->execute($query);

        $newid = $DB->id;
        $query = "DROP TABLE foo";
        $DB->execute($query);

        $query = "CREATE TEMPORARY TABLE foo2 AS SELECT * FROM sp_size WHERE id_ryad = '{$order[0]['id_ryad']}' 
			      and duble='{$order[0]['duble']}' and user = ''{$order[0]['user']};";
        $DB->execute($query);

        $query = "UPDATE foo2 SET id=NULL, duble=1, id_ryad=$newid;";
        $DB->execute($query);

        $query = "INSERT INTO sp_size SELECT * FROM foo2;";
        $DB->execute($query);

        $query = "DROP TABLE foo2";
        $DB->execute($query);

        $query = "UPDATE `sp_order` SET
			`id_zp` = '$id_zp', `id_ryad` = '$newid' WHERE `sp_order`.`id` = '$id_o' LIMIT 1 ;";
        $DB->execute($query);

//		$query = "UPDATE `office_set` SET
//			`zp_id` = '$id_zp'
//			 WHERE `office_set`.`user` = '' and `office_set`.`zp_id` = '' LIMIT 1 ;";
//	        $DB->execute($query);
        $message = 'Заказ успешно перенесен в другую <a href="/com/org/open/' . $id_zp . '/" class="link4">закупку</a>.';

    }
}

//todo Перенос заказов в другой ряд
if ($_GET['section'] == 'ryad') {
    if ($_POST['move_orders']) {
        $id_zp = intval($_GET['value']);
        $old_row_id = intval($_POST['from']);
        $new_row_id = intval($_POST['to']);

        $query = "SELECT id_order, price, duble FROM sp_order 
                LEFT JOIN sp_ryad ON sp_ryad.id = $old_row_id
                WHERE id_ryad = $old_row_id";
        $id_order = $DB->getAll($query);

//  Вытаскиваем цену товара и количество рядов того ряда, КОТОРЫЙ переезжает
        $source_price = $id_order[0]['price'];
        $source_num_row = $id_order[0]['duble'];

//  Вытаскиваем цену и количество рядов в целевом ряде
        $sql = "SELECT duble, price FROM sp_ryad WHERE id = $new_row_id";
        $temp = $DB->getAll($sql);

        $target_price = $temp[0]['price'];
        $target_num_row = intval($temp[0]['duble']);

//  Считаем новое значение количества рядов (sp_ryad.duble) в целевом ряде
        $summ = $source_num_row + $target_num_row;

//  Обновляем значение sp_ryad.duble
        $sql = "UPDATE sp_ryad SET duble = $summ WHERE id = $new_row_id";
        $DB->execute($sql);

//  Меняем номер ряда в заказах
        foreach ($id_order as $id){
            $id_ord = $id['id_order'];
            $sql = "UPDATE sp_order SET 
                    sp_order.id_ryad = $new_row_id WHERE id_order = $id_ord  ";
            $DB->execute($sql);
        }

        $query2 = "SELECT id, duble FROM sp_size WHERE id_ryad = $old_row_id";
        $id_size = $DB->getAll($query2);

//        Обновляем таблицу sp_size. Устанавливаем номер нового ряда и меняем duble с учётом рядов, которые уже были
//        в целевом ряде. Т.е., сдвигаем положение перемещаемых заказов.
        foreach ($id_size as $id){
            $id_s = $id['id'];
            $id_d = $id['duble'] + $target_num_row;
            $sql2 = "UPDATE sp_size SET 
                      id_ryad = $new_row_id,
                      duble = $id_d 
                     WHERE id = $id_s";
            $DB->execute($sql2);
        }

        $message = 'Низкий поклон <a href="/com/org/open/' . $id_zp . '/" class="link4">закупку</a>.';
    }
}

// далее фанкции закрытого доступа -----------------------------------------------------------------------------
if ($user->get_property('userID') > 0):
    if ($_GET['section'] == 'multi') {
        $idpost = intval($_POST['idpost']);
        if ($_POST['action'] == 'addr_multi') {
            $countFile = count($_FILES['photo']['name']);
            for ($i = 0; $i < $countFile; $i++) {
                $position = $DB->getOne("SELECT MAX(position) FROM sp_ryad WHERE id_zp='$idpost'");
                $position = $position + 1;
                $query = "INSERT INTO sp_ryad (user,id_zp,title,articul,message,mess_edit,price,size,duble,auto,spec,position,cat)
			                VALUES('" . $user->get_property('userID') . "','$idpost','Товар #$position', '','','','0','','1','','1','$position','');";
                $DB->execute($query);

                $lid = $DB->id;
                if ($_FILES['photo']['size'][$i] > 0) {
                    $filedata = [];
                    $filedata['name'] = $_FILES['photo']['name'][$i];
                    $filedata['type'] = $_FILES['photo']['type'][$i];
                    $filedata['tmp_name'] = $_FILES['photo']['tmp_name'][$i];
                    $filedata['error'] = $_FILES['photo']['error'][$i];
                    $filedata['size'] = $_FILES['photo']['size'][$i];
                    $setimg1 = null;
                    $imgpath_r = save_image_on_server($filedata, 'img/uploads/zakup/', $setimg1, 'r' . $lid, 'this');
                    if (!empty($imgpath_r[1])) {
                        $sql = "UPDATE `sp_ryad` SET 
					`photo` = '" . $imgpath_r[1] . "'
					WHERE `sp_ryad`.`id` = " . $lid;
//echo $sql;exit;
                        $DB->execute($sql);
                    }
                }
                $message = 'Добавлено товаров: ' . $countFile . '';
            }
        }
    }
    if ($_GET['section'] == 'addr') {
        $id_zp = intval($_GET['value']);
        $lock = $DB->getOne("SELECT id FROM sp_ryad WHERE user='{$user->get_property('userID')}' and id_zp='$id_zp' and `lock` = 1 ORDER BY id ASC");
        if ($lock > 0) {
            $latIdMysql = $lock;
        } else {
            $query = "INSERT INTO sp_ryad (`user`,`id_zp`,`lock`)
                        VALUES('" . $user->get_property('userID') . "','$id_zp','1');";
            $DB->execute($query);

//echo $query;exit;
            $latIdMysql = $DB->id;
        }
    }
    if (($_GET['section'] == 'addr' and !empty($_POST['action'])) or ($_GET['section'] == 'editr' and !empty($_POST['action']))) {
        $action = $_POST['action'];
        $text = PHP_slashes($_POST['textarea1']);
        $title = PHP_slashes(htmlspecialchars(strip_tags(str_replace('"', '\'', $_POST['title']))));
        $articul = PHP_slashes(htmlspecialchars(strip_tags($_POST['articul'])));
        $cat = PHP_slashes(htmlspecialchars(strip_tags(trim($_POST['cat']))));
        $price = floatval(str_replace(",", ".", $_POST['price']));            // замена запятой на точку  ...str_replace(",",".",....
        $size = htmlspecialchars(strip_tags($_POST['size']));
        $auto = intval($_POST['auto']);
        $autoblock = intval($_POST['autoblock']);
        $allblock = intval($_POST['allblock']);
        $tempOff = intval($_POST['tempOff']);
        $idpost = intval($_POST['idpost']);
        $idryad = intval($_POST['idryad']);
        $idzp = intval($_GET['value']);
        if ($action == 'editr') $idzp = intval($_GET['value2']);
        if ($action == 'addr') if ($size == '') $message .= 'Ошибка: Укажите "Размеры или количество"<br/>';
        if ($price == '') $message .= 'Укажите "цену".<br/>';
        if ($title == '') $message .= 'Вы не указали "название" рядка.<br/>';
        if ($idpost == 0) $message .= 'Вы пытаетесь добавить рядок к не существующей закупке<br/>';
        if ($action == 'addr') {
            $query = "SELECT user FROM `sp_zakup` WHERE `id` = '$idpost'";
            $testuser = $DB->getOne($query);
            if ($testuser <> $user->get_property('userID') AND $user->get_property('gid') <> 25) $message .= 'Вы пытаетесь редактировать чужую закупку<br/>';
        }
        if ($action == 'addr') {
            $query = "SELECT * FROM `sp_ryad` WHERE `id_zp` = '$idpost' AND `title` = '$title' AND `articul`='$articul'";
            $items = $DB->getAll($query);
            if (count($items) > 0) $message .= 'Рядок с таким "названием" и "артикулем" в этой закупке уже существует..';
        }
        if ($action == 'editr') {
            $query = "SELECT * FROM `sp_ryad` WHERE `id` = '" . $idpost . "'";
            $items = $DB->getAll($query);
            if ($items[0]['user'] <> $user->get_property('userID') AND $user->get_property('gid') <> 25) $message .= 'Вы не можете редактировать чужую закупку.';
        }
        if (empty($message)) {
            if ($action == 'addr') {
                $position = $DB->getOne("SELECT MAX(position) FROM sp_ryad WHERE id_zp='$idpost'");
                $position = $position + 1;
                /*$query = "INSERT INTO sp_ryad
		        (user,id_zp,title,articul,message,mess_edit,price,size,duble,auto,spec,position,cat)
			VALUES('".$user->get_property('userID')."','$idpost','$title',
			'$articul','$text','','$price','$size','1',$auto,'1','$position','$cat');";*/
                $query = "UPDATE `sp_ryad` SET
                            `title` 	= '$title',
                            `articul` 	= '$articul',
                            `message` 	= '$text',
                            `price` 	= '$price',
                            `cat` 		= '$cat',
                            `size` 		= '$size',
                            `duble` 	= '1',
                            `spec` 		= '1',
                            `position` 	= '$position',
                            `auto` 		= '$auto',
                            `autoblock` = '$autoblock',
                            `allblock` 	= '$allblock',
                            `lock` 		= '0'
                            WHERE `sp_ryad`.`id` ='$idryad' LIMIT 1 ;";
                $lid = $idryad;
            }
            if ($action == 'editr') {
                $query = "UPDATE `sp_ryad` SET
                            `title` 	= '$title',
                            `articul` 	= '$articul',
                            `message` 	= '$text',
                            `price` 	= '$price',
                            `cat` 		= '$cat',
                            `autoblock` = '$autoblock',
                            `allblock` 	= '$allblock',
                            `tempOff` 	= '$tempOff',
                            `auto` 		= '$auto' WHERE `sp_ryad`.`id` =$idpost LIMIT 1 ;";
                $lid = $idpost;
            }
//echo $query; exit;
            $DB->execute($query);
//Количество товара в рядке. Обновление в таблиц sp_zise.name
            if ($action == 'editr') {
                $qqq = "UPDATE `sp_size` SET `name` = '$size' WHERE `sp_size`.`id_ryad` =$idpost LIMIT 1 ;";
//echo $qqq; exit;
                $DB->execute($qqq);;
            }
            if ($_POST['photodel'] == '1') {
                @unlink($items[0]['photo']);
                $sql = "UPDATE `sp_ryad` SET 
			            `photo` = ''
			            WHERE `sp_ryad`.`id` = " . $lid;
                $DB->execute($sql);
            }
//	if ($action=='addr') $last_id=$lid=$DB->id;
            if ($_FILES['photo']['size'] > 0) {
                $imgpath_r = save_image_on_server($_FILES['photo'], 'img/uploads/zakup/', $setimg1, 'r' . $lid, 'this');
                if (!empty($imgpath_r[1])) {
                    $sql = "UPDATE `sp_ryad` SET 
					`photo` = '" . $imgpath_r[1] . "'
					WHERE `sp_ryad`.`id` = " . $lid;
//echo $sql;exit;
                    $DB->execute($sql);
                }
            }
//    Создание ряда! Количество товара в рядке. Внесение в таблицу sp_zise.name
            if ($action == 'addr') {
                $last_id = $idryad;
                $size = explode(',', $_POST['size']);
                $query_size = '';
                if (count($size) == 1) {
                    if (strpos($size[0], '-')) {
                        $diapazon = explode('-', $size[0]);
                        $diapazon[0] = intval($diapazon[0]);
                        $diapazon[1] = intval($diapazon[1]);
                        if ($diapazon[0] > $diapazon[1]) {
                            $rez = $diapazon[0];
                            $diapazon[0] = $diapazon[1];
                            $diapazon[1] = $rez;
                        }
                        for ($i = $diapazon[0]; $i < intval($diapazon[1]); $i++) {
                            $query_size = "INSERT INTO sp_size VALUES('','$last_id','$id_zp','','','1','');";
                            $DB->execute($query_size);
                        }
                    } else {
                        if (is_numeric($size[0]) and intval($size[0]) < 10) {
                            for ($i = 0; $i < intval($size[0]); $i++) {
                                $query_size = "INSERT INTO sp_size VALUES('','$last_id','$id_zp','$size[0]','','1','');";
                                $DB->execute($query_size);
                            }
                        } else {
                            $query_size = "INSERT INTO sp_size VALUES('','$last_id','$id_zp','" . $size[0] . "','','1','');";
                            $DB->execute($query_size);
                        }
                    }
                }
                if (count($size) > 1) {
                    for ($i = 0; $i < count($size); $i++) {
                        $query_size = "INSERT INTO sp_size VALUES('','$last_id','$id_zp','" . $size[$i] . "','','1','');";
                        $DB->execute($query_size);
                    }
                }
                header('Location: /com/org/open/' . $_GET['value']);
                exit;
            }
//  Создание ряда!  Количество товара в рядке. Внесение в таблицу sp_zise.name
            header('Location: /com/org/open/' . $idzp);

        }
    }
    if ($_GET['section'] == 'status') {
        $query = "SELECT `sp_zakup`.`user`, `sp_zakup`.`status` FROM `sp_zakup` WHERE `sp_zakup`.`id`=" . intval($_GET['value']) ;
        $zakup = $DB->getAll($query);
        if (count($zakup) == 1) {
            /*	if ($zakup[0]['status']==0 or $zakup[0]['status']==1) $arr[]=1;
		if ($zakup[0]['status']<=2)$arr[]=2;
		if ($zakup[0]['status']==3)$arr[]=3;
		if ($zakup[0]['status']==4)$arr[]=4;
		if ($zakup[0]['status']==5)$arr[]=5;
		if ($zakup[0]['status']==6)$arr[]=6;
		if ($zakup[0]['status']==7)$arr[]=7;
		if ($zakup[0]['status']==8)$arr[]=8;
		if ($zakup[0]['status']==9)$arr[]=9;
                if ($zakup[0]['status']==10)$arr[]=10;

		if(in_array(intval($_GET['value2']),$arr)) если раскомментировать,перестанет работать изменение статуса закупки ОРГОМ наверху, где маленький значок   .../com/org/open/   */
            {
                $sql = "UPDATE `sp_zakup` SET `status` = '" . intval($_GET['value2']) . "' WHERE `sp_zakup`.`id` =" . intval($_GET['value']);
                $DB->execute($sql);
            }
        }
        header('Location: /com/org/open/' . intval($_GET['value']));
    }
    if ($_GET['section'] == 'order') {
        $id_order = intval($_GET['value']);
        $query = "	SELECT `sp_size`.* FROM `sp_size` WHERE `sp_size`.`id` = " . $id_order;
        $items_order = $DB->getAll($query);
        if (empty($id_order) OR count($items_order) == 0) $message .= 'Ошибка: Вы пытаетесь сделать заказ по несуществующей позиции.<br/>';
        if ($items_order[0]['user'] == $user->get_property('userID')) $message .= 'Ошибка: Данная позиция уже забронирована Вами.<br/>';
        if ($items_order[0]['user'] > 0) $message .= 'Ошибка: Данная позиция уже забронирована другим участником..<br/>';
        $query = "SELECT sp_ryad.*, sp_zakup.curs FROM `sp_ryad` JOIN `sp_zakup` ON `sp_ryad`.`id_zp`=`sp_zakup`.`id`
                  WHERE `sp_ryad`.`id` = " . $items_order[0]['id_ryad'];
        $items = $DB->getAll($query);
    }
    if ($_GET['section'] == 'addo' or $_GET['section'] == 'order') {
        $action = $_POST['action'];
        $text = PHP_slashes($_POST['textarea1']);
        $title = PHP_slashes(htmlspecialchars(strip_tags($_POST['title'])));
        $articul = PHP_slashes(htmlspecialchars(strip_tags($_POST['articul'])));
        $price = floatval(str_replace(",", ".", $_POST['price']));                // замена запятой на точку  ...str_replace(",",".",....
        $size = PHP_slashes($_POST['size']);
        $kolvo = intval($_POST['kolvo']);
        $color = PHP_slashes(htmlspecialchars(strip_tags($_POST['color'])));
        $anonim = intval($_POST['isAnonim']);
        $idpost = intval($_POST['idpost']);
        $uniccod = rand(10000, 99999);
//	$kolvo			= intval($_POST[kolvo]);
        $message = '';
        if (empty($action) and $_GET['section'] == 'addo')  // вывод инфы о закупке при ручной подаче заказа
        {
            if ($user->get_property('gid') == 0 or $user->get_property('gid') == 18) $onlystatus = '`sp_zakup`.`status`>2 and `sp_zakup`.`status`<9 and ';
            $sql = "	SELECT `sp_zakup`.* , `sp_status`.`name`,`punbb_users`.`username`,
				`sp_level`.`name` as `levname`,`cities`.`city_name_ru`
                FROM `sp_zakup`
                LEFT JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
                JOIN `sp_level` ON `sp_zakup`.`level`=`sp_level`.`id`
                JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
                JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
                WHERE $onlystatus `sp_zakup`.`id`=" . intval($_GET['value']);
            $zakup = $DB->getAll($sql);
            if ($zakup[0]['status'] < 3 and $zakup[0]['user'] <> $user->get_property('userID') and $user->get_property('gid') < 25) unset($zakup);
            if ($zakup[0]['user'] == $user->get_property('userID') OR $user->get_property('gid') == 25):
                $query = "SELECT * FROM `sp_status` ORDER BY `id` ASC";
                $statuslist = $DB->getAll($query);
            endif;
            if (!empty($zakup[0]['foto'])) {
                $split = explode('/', $zakup[0]['foto']);
                $img_path = '/images/' . $split[2] . '/125/100/1/' . $split[3];
            } else $img_path = '/' . $theme . 'images/no_photo125x100.png';
            if (count($zakup) == 0) {
                header('Location: /com/org/open/' . intval($_GET['value']));
                exit;
            }
        }
            if ($action == 'addo')  // ручная подача заявки
            {
            $query = "SELECT sp_zakup.*,`punbb_users`.`email` 
			  FROM `sp_zakup`
			  LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`			
			  WHERE `sp_zakup`.`id` = $idpost";
            $zakup = $DB->getAll($query);
            if (count($zakup) == 0) $message .= "Ошибка: Вы пытаетесь сделать заказ в несуществующей закупке.<br/>";
            if ($kolvo == 0) $kolvo = 1;
            if ($price == 0) $message .= "Ошибка: Вы не указали стоимость товара.<br/>";
            if ($title == '') $message .= "Ошибка: Вы не указали название товара.<br/>";
        }
        if ($action == 'order') {
            $query = "SELECT * FROM `sp_size` WHERE `sp_size`.`id` = $idpost";
            $items_order = $DB->getAll($query);
            if ($idpost == 0 or count($items_order) == 0) $message .= "Ошибка: Такого размера, рядка или закупки не существует, обратитесь к администратору.<br/>";
            if ($items_order[0]['user'] > 0) $message .= "Ошибка: Данная позиция уже забронирована другим участником.<br/>";
            if ($items_order[0]['user'] == $user->get_property('userID')) $message .= 'Данная позиция уже забронирована Вами.<br/>';
        }
/*        if ($action == 'editorder') { // need to work
            $query = "SELECT * FROM `sp_order` WHERE `id` = '" . $idpost . "'"; // ORDER BY id DESC LIMIT $flatCount
            $db = null;
            $db->setQuery($query);
            $items = ($items = $db->loadObjectList()) ? $items : [];
            if ($items[0]->user <> $user->get('id') AND $user->get('id') <> 62) $err = 9;
        }*/
        if (empty($message)) {
            /*$query = "UPDATE sp_addpay SET `status`=4 WHERE
			 `user` = '" . $user->get_property('userID') . "' and
			 `zp_id` = '" . intval($_GET['value']) . "';";
            $DB->execute($query);*/
            if ($action == 'order') {
                $text = tolink($text);
                $query = "SELECT `sp_ryad`.`id_zp`, `sp_zakup`.`type` FROM `sp_ryad` 
                LEFT JOIN `sp_zakup` ON `sp_zakup`.`id`=`sp_ryad`.`id_zp`
                WHERE `sp_ryad`.`id` = " . $items_order[0]['id_ryad'];
                $items_3 = $DB->getAll($query);

                $query = "SELECT sp_order.kolvo FROM sp_order WHERE sp_order.id_zp ='" .$items_order[0]['id_zp']."' AND  sp_order.id_ryad = '" .$items[0]['id']. "'";
                $haveOrdersArray = $DB->getAll($query);

                $haveOrders = 0;
                foreach ($haveOrdersArray AS $value){
                    $haveOrders+=$value['kolvo'];
                }

                $diff = ($items_order[0]['name'] * $items_order[0]['duble']) - ($haveOrders + $kolvo);  //Проверяем разницу между количеством позиций в коробке и заказываемым количеством товара

                $spSizeIdRyad = $items[0]['id'];
                $spSizeIdzp = $items[0]['id_zp'];
                $spSizeName = $items_order[0]['name'];
                $spSizeDuble = $items_order[0]['duble'];

                if ($items_order[0]['name'] > 1) {
                    if ($diff >= 0) {
                        $dates = date('Y.m.d:H.i');
                        $dateunix = time();
                        $query = "INSERT INTO sp_order VALUES('','" . $user->get_property('userID') . "','$idpost',
			                     '$text','','$color','$kolvo',0,'$dates','$uniccod','" . $items_3[0]['id_zp'] . "','" . $items_order[0]['id_ryad'] . "','$dateunix','0','0');";
                        $DB->execute($query);
                        // todo в запросе к БД (записи) добавить последнюю цифру - данные для addrDelivery
                        // todo запрос добавляет квадратики с именами, отвечает за их количество
                        // todo добавляем запись данных о наценки за крупногабарит ( 0 между $kolvo и $dates )

                        if ($items_3[0]['type'] == 0 or $items_3[0]['type'] == 2) {
                            $query = "UPDATE sp_size SET `user` = '" . $user->get_property('userID') . "', `anonim` = '$anonim' WHERE `sp_size`.`id` =$idpost LIMIT 1 ;";
                            $DB->execute($query);

                            if ($diff > 0) {
                                $query = "INSERT INTO sp_size VALUES('','$spSizeIdRyad','$spSizeIdzp','$spSizeName',0,'$spSizeDuble',0)";
                                $DB->execute($query);
                            }
                        }
                    } else {
                        $message .= "Ошибка: Вы пытаетесь заказать больше, чем есть в коробке! Обратитесь к организатору.<br/>";
                    }
                } else {
                    $dates = date('Y.m.d:H.i');
                    $dateunix = time();
                    $query = "INSERT INTO sp_order VALUES('','" . $user->get_property('userID') . "','$idpost',
			                     '$text','','$color','$kolvo',0,'$dates','$uniccod','" . $items_3[0]['id_zp'] . "','" . $items_order[0]['id_ryad'] . "','$dateunix','0','0');";
                    $DB->execute($query);
                    // todo в запросе к БД (записи) добавить последнюю цифру - данные для addrDelivery
                    // todo запрос добавляет квадратики с именами, отвечает за их количество
                    // todo добавляем запись данных о наценки за крупногабарит ( 0 между $kolvo и $dates )

                    if ($items_3[0]['type'] == 0 or $items_3[0]['type'] == 2) {
                        $query = "UPDATE sp_size SET `user` = '" . $user->get_property('userID') . "', `anonim` = '$anonim' WHERE `sp_size`.`id` =$idpost LIMIT 1 ;";
                        $DB->execute($query);}

                        $query = "INSERT INTO sp_size VALUES('','$spSizeIdRyad','$spSizeIdzp','$spSizeName',0,'$spSizeDuble',0)";
                        $DB->execute($query);
                };
                $query = "SELECT * FROM `sp_ryad` WHERE `id` = " . $items_order[0]['id_ryad'];
                $items_ryad = $DB->getAll($query);

//Нахрен теперь не нужно   ------------------------------------------------------
                // proverka nujno li sozdovat dop ryad

/*                        $new_duble = $items_ryad[0]['duble'] + 1;
                        $query = "SELECT id FROM `sp_size` WHERE `id_ryad`='" . $items_order[0]['id_ryad'] .
                            "' AND `user`='0' AND `name` = '" . $items_order[0]['name'] . "' AND (`duble`= '" . ($items_order[0]['duble'] - 1) .
                            "' OR `duble`= '" . ($items_order[0]['duble'] + 1) . "');";
                        $items_none = $DB->getAll($query);
                        if (($items_order[0]['duble'] == $items_ryad[0]['duble']) AND $items_ryad[0]['auto'] == 1 AND count($items_none) == 0) {
                            $query = "UPDATE sp_ryad SET `duble`=$new_duble WHERE id = " . $items_order[0]['id_ryad'];
                            $DB->execute($query);

                            $query = "SELECT * FROM `sp_size` WHERE `id_ryad` = " . $items_order[0]['id_ryad'] . " AND duble = '" . $items_ryad[0]['duble'] . "'";
                            $items_old_s = $DB->getAll($query);

                            $id_zp_size = $items_3[0]['id_zp'];
                            foreach ($items_old_s as $item_os) {
                                $query = "INSERT INTO sp_size VALUES('','" . $item_os['id_ryad'] . "','" . $id_zp_size . "','" . $item_os['name'] . "', '','" . ($item_os['duble'] + 1) . "','');";
                                $DB->execute($query);
                    }
                }*/
//---------------------------------------------------------------------------------------------------------------------------------
                $query = "SELECT sp_zakup.*, punbb_users.email, punbb_users.alertmail
							FROM `sp_size`
							LEFT JOIN `sp_ryad` ON sp_size.id_ryad = sp_ryad.id
							JOIN `sp_zakup` ON sp_zakup.id = sp_ryad.id_zp
							JOIN `punbb_users` ON sp_zakup.user = punbb_users.id
							WHERE `sp_size`.`id` =$idpost";
                $items = $DB->getAll($query);
				
                if ($items[0]['alertnews'] == 1) {
                    $emailsup = $DB->getOne('SELECT `setting`.`value` FROM `setting` WHERE `setting`.`name`=\'emailsup\'');
					$siteName = $_SERVER['HTTP_HOST'];
					$zakupName = $items[0]['title'];
					$zakupID = $items[0]['id'];



                    $m = new Mail; // начинаем
                    $m->From("$emailsup"); // от кого отправляется почта
                    $m->To($items[0]['email']); // кому адресованно
                    $m->text_html = "text/html";
                    $m->Subject("Новый заказ на сайте " . $_SERVER['HTTP_HOST']);
                    $m->Body("
						Здравствуйте,<br/>
						Это письмо отправлено вам сайтом: <a href=\" " . $siteName . "\">" . $siteName . "</a><br/>
						<p>
						Поступил новый заказ, в закупке \"" . $zakupName . "\" от пользователя \"" . $user->userData['username'] . "\" .
						</p>
						Для просмотра перейдите по ссылке:<br/>
						<a href=\"http://" . $siteName . "/com/org/open/" . $zakupID . "\"> http://" .
						$siteName . "/com/org/open/" . $zakupID . "</a><br/>

						<p>Вы в любое время можете отключить уведомления о новых заказах в настройках вашей закупки</p> ");
                    $m->Priority(4);    // приоритет письма
                    $m->Send();    // а теперь пошла отправка
                }
				
                $oke = 1;
                //$message='Новый заказ успешно добавлен';
            }
            if ($action == 'addo') {
                $text = tolink($text);
                $query = "INSERT INTO sp_ryad (user,id_zp,title,articul,message,price,size,duble,auto,spec)
			              VALUES('" . $zakup[0]['user'] . "','$idpost','$title','$articul','$text','$price','$size','1',0,'0');";
                $DB->execute($query);

                $sql = "SELECT LAST_INSERT_ID()";
                $last_id = $DB->getOne($sql);
                $query = "INSERT INTO sp_size VALUES('','$last_id','" . $size . "','" . $user->get_property('userID') . "','1','$anonim');";
                $DB->execute($query);

                $sql = "SELECT LAST_INSERT_ID()";
                $last_id_size = $DB->getOne($sql);
                $dates = date('y.m.d:h.m');
                $dateunix = time();
                $query = "INSERT INTO sp_order VALUES('','" . $user->get_property('userID') . "','$last_id_size',
			    '$text','','$color','$kolvo',0,'$dates','$uniccod','" . $idpost . "','" . $last_id . "','$dateunix','0', 0);";
                $DB->execute($query);

                // todo в запросе к БД (записи) добавить последнюю цифру - данные для addrDelivery
                // todo добавляем запись данных о наценки за крупногабарит ( 0 между $kolvo и $dates )
                if ($zakup[0]['alertnews'] == 1) {
                    $emailsup = $DB->getOne('SELECT `setting`.`value` 
                    FROM `setting`
                    WHERE `setting`.`name`=\'emailsup\'');
                    $m = new Mail; // начинаем
                    $m->From("$emailsup"); // от кого отправляется почта
                    $m->To($zakup[0]['email']); // кому адресованно
                    $m->text_html = "text/html";
                    $m->Subject("Новый заказ на сайте " . $_SERVER['HTTP_HOST']);
                    $m->Body("
                        Здравствуйте,<br/>
                        Это письмо отправлено вам сайтом: <a href=\"" . $_SERVER['HTTP_HOST'] . "\">" . $_SERVER['HTTP_HOST'] . "</a><br/>
                        <p>
                        Поступил новый заказ, в закупке \"" . $zakup[0]['title'] . "\" от пользователя \"" . $user->userData['username'] . "\".
                        </p>
                        Для просмотра перейдите по ссылке:<br/>
                        <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $zakup[0]['id'] . "\">http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $zakup[0]['id'] . "</a><br/>
                        
                        <p>Вы в любое время можете отключить уведомления о новых заказах в настройках вашей закупки</p>");
                    $m->Priority(3);    // приоритет письма
                    $m->Send();    // а теперь пошла отправка
                }
                $message='Новый заказ успешно добавлен';
                $oke = 1;

            }
            if ($action == 'editorder') {
                $pmessage = null;
                $pmessage_edit = null;
                $query = "UPDATE sp_order SET `message` = '$pmessage', `mess_edit` = '$pmessage_edit',
			              `color` = '$color', `kolvo` = '$kolvo' WHERE `sp_order`.`id` =$idpost LIMIT 1 ;";
                $DB->setQuery($query);
                $DB->query();
                $query = "UPDATE sp_size SET `anonim` = '$anonim' WHERE `sp_size`.`id` =" . $items[0]->id_order . " LIMIT 1 ;";
                $DB->setQuery($query);
                $DB->query();

            }
        } // if err==0
    }
    if (($_GET['section'] == 'delcom') and intval($_GET['value2']) > 0 and $user->get_property('gid') == 25) {
        $sql = "DELETE FROM `comments` WHERE `comments`.`id` = '" . intval($_GET['value2']) . "' LIMIT 1";
        $DB->execute($sql);
        header("Location: /com/org/open/" . $_GET['value']);
    }
    if (($_GET['section'] == 'addpayplus')) {
        $id = intval($_GET['value']);
        $summ = floatval($_GET['summ']);
        $sql = '	SELECT `sp_zakup`.`user`
		FROM `sp_addpay` 
		LEFT JOIN `sp_zakup` ON `sp_zakup`.`id` = `sp_addpay`.`zp_id`
		WHERE `sp_addpay`.`id` = ' . $id . '
		';
        $test = $DB->getAll($sql);
        if ($test[0]['user'] == $user->get_property('userID')) {
            $query = "UPDATE sp_addpay SET `status`=1, `doplata` = '$summ' WHERE `id` = '$id';";            // todo заменить status на 1
            $DB->execute($query);
        }
        header("Location: /com/org/open/" . $_GET['value2']);
        exit;
    }
//todo Добавляем секцию для ввода в БД наценки
    if (($_GET['section'] == 'addextraCharge')) {
        $orderId = intval($_GET['value']);
        $idZp = intval($_GET['value2']);
//        $extraCharge = floatval($_GET['extraCharge']);
        $extraCharge = floatval(str_replace(",", ".", $_GET['extraCharge']));
        $query = "UPDATE sp_order SET `oversize` = '$extraCharge' WHERE `sp_order`.`id` = '$orderId' ";
        $DB->execute($query);
        header("Location: /com/org/vieworder/" . $_GET['value2']);                                   // Возврат на предыдущую страницу
        exit;
    }
    if (!empty($_POST['selcity'])) {
        $city = intval($_POST['city']);
        $region = intval($_POST['region']);
        if ($region > 0 and $city > 0) {
            $sql = "UPDATE `punbb_users` SET 
			`city` = '$city',
			`region` = '$region'
			WHERE `punbb_users`.`id` = " . $user->get_property('userID');
            $DB->execute($sql);
        }
    }
    $sql = ' SELECT `punbb_users`.`city` FROM `punbb_users` WHERE `id` = ' . $user->get_property('userID');
    $checkcity = $DB->getOne($sql);
    if (empty($_GET['section'])) {
        $sql = '	SELECT `sp_zakup`.* , `sp_status`.`name`
		FROM `sp_zakup` 
		LEFT JOIN `sp_status` ON `sp_status`.`id` = `sp_zakup`.`status`
		WHERE `sp_zakup`.`user` = ' . $user->get_property('userID') . '
		ORDER by `sp_status`.`id` ASC';
        $all_zakup = $DB->getAll($sql);
        $registry['percent'] = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'percent\'');
        $query = "SELECT `sp_zakup`.`id`,`sp_zakup`.`title`, `sp_orgorder`.`sum`, `sp_orgorder`.`status` as `status_oo`,
		`sp_status`.`name` as `statname`,
		(SELECT count(sp_order.id) FROM sp_order WHERE `sp_order`.`id_zp` = `sp_zakup`.`id` 
			and `sp_order`.`status`!=0 and `sp_order`.`status`!=2) AS `countorder`
		FROM `sp_orgorder`
		LEFT JOIN `sp_zakup` ON `sp_zakup`.`id`=`sp_orgorder`.`zp_id`
		LEFT JOIN `sp_status` ON `sp_status`.`id` = `sp_zakup`.`status`
		WHERE `sp_zakup`.`user` = '{$user->get_property('userID')}'";
        $registry['orgorder'] = $DB->getAll($query);
        
        $newarr = array();
        $totalprice = 0;
        foreach ($registry['orgorder'] as $item) {
            $query = "
		SELECT sp_order.*, sp_ryad.price
		FROM sp_order 
		JOIN sp_ryad ON sp_ryad.id=sp_order.id_ryad
		WHERE `sp_order`.`id_zp` = '{$item['id']}'
			and `sp_order`.`status`!=0 and `sp_order`.`status`!=2
	 	";
            $ord = $DB->getAll($query);
$query = "SELECT sp_order.*, sp_ryad.price FROM sp_order 
		JOIN sp_ryad ON sp_ryad.id=sp_order.id_ryad
		WHERE `sp_order`.`id_zp` = '{$item['id']}' AND `sp_order`.`user`='{$user->userData['id']}' AND sp_order.status = 1"; /*todo добавить покупетеля = оранизатору И добавить условие только заказы со статусом "включено в счёт"*/
            $orgOrders = $DB->getAll($query);
            $item['summprice'] = 0;
            foreach ($ord as $or) {
                if ($or['kolvo'] == 0) $or['kolvo'] = 1;
                $item['summprice'] += $or['price'] * $or['kolvo'];
            }
$item['orgBuy']=0;
            foreach ($orgOrders AS $orgOrder) {
                $item['orgBuy'] += $orgOrder['price'] * $orgOrder['kolvo'];
            };
            $newarr[] = $item;
            $totalprice += $item['summprice'];
        }
        $registry['orgorder'] = $newarr;
        unset($newarr);

//print_r(	$registry['orgorder']);exit;
    }
    if ($_GET['section'] == 'addpayorg' and intval($_GET['value']) > 0) {
        $id = intval($_GET['value']);
        $query = "SELECT `sp_zakup`.`id`,`sp_zakup`.`title`, `sp_orgorder`.`sum`, `sp_orgorder`.`status` as `status_oo`,
		`sp_status`.`name` as `statname`,
		(SELECT count(sp_order.id) FROM sp_order WHERE `sp_order`.`id_zp` = `sp_zakup`.`id` 
			and `sp_order`.`status`!=0 and `sp_order`.`status`!=2) AS `countorder`,
		(SELECT sum(sp_ryad.price) FROM sp_order 
		        JOIN sp_ryad ON sp_ryad.id=sp_order.id_ryad
			WHERE `sp_order`.`id_zp` = `sp_zakup`.`id` 
			and `sp_order`.`status`!=0 and `sp_order`.`status`!=2) AS `summprice`
		FROM `sp_orgorder`
		LEFT JOIN `sp_zakup` ON `sp_zakup`.`id`=`sp_orgorder`.`zp_id`
		LEFT JOIN `sp_status` ON `sp_status`.`id` = `sp_zakup`.`status`
		WHERE `sp_zakup`.`user` = '{$user->get_property('userID')}' and `sp_zakup`.`id`='$id'";
        $registry['orgorder'] = $DB->getAll($query);
        if (isset($_POST['shopid'])) {
            $idzp = intval($_POST['shopid']);
            $date = time();
            $date_user = PHP_slashes($_POST['date']);
            $amount = floatval($_POST['amount']);
            $card = PHP_slashes($_POST['desc']);
            $sql = "INSERT INTO `sp_addpayorg` (`zp_id`,`user`,`date`,`date_user`,`summ`,`card`) 
	          VALUES ('$idzp','{$user->get_property('userID')}','$date','$date_user','$amount','$card')";
            $DB->execute($sql);
            $sql = "UPDATE `sp_orgorder` SET `status` = '2' WHERE `zp_id` = '$idzp' LIMIT 1";
            $DB->execute($sql);
            $sql = ' SELECT `sp_zakup`.`id`,`sp_zakup`.`title`,`sp_zakup`.`alertcomm`,`punbb_users`.`email` 
			FROM `sp_zakup`
			LEFT JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
			WHERE `sp_zakup`.`id` = ' . $idzp;
            $checkemail = $DB->getAll($sql);
            $emailsup = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'emailsup\'');
            $m = new Mail; // начинаем
            $m->From("$emailsup"); // от кого отправляется почта
            $m->To("$emailsup"); // кому адресованно
            $m->text_html = "text/html";
            $m->Subject("Новое уведомление об оплате выстовленного счета на сайте " . $_SERVER['HTTP_HOST']);
            $m->Body("
                Здравствуйте,<br/>
                Это письмо отправлено вам сайтом: <a href=\"http://" . $_SERVER['HTTP_HOST'] . "\">" . $_SERVER['HTTP_HOST'] . "</a><br/>
                <p>
                Новое уведомление об оплате выстовленного счета, к закупке  \"" . $checkemail[0]['title'] . "\", от пользователя 
                    <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/profile/default/{$user->get_property('userID')}/\">{$user->get_property('username')}</a>.
                </p>
                Для просмотра перейдите по ссылке:<br/>
                <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/partner/index.php?component=orgorders\">http://" . $_SERVER['HTTP_HOST'] . "/partner/index.php?component=orgorders</a><br/>
                
                ");
            $m->Priority(3);    // приоритет письма
            $m->Send();    // а теперь пошла отправка
            header("Location: /com/org/");
        }

    }
// ------------------   Рассылка участникам закупки	----------------------------
    if ($_GET['section'] == 'send' and intval($_GET['value']) > 0) {
        if (isset($_POST['send']) and !empty($_POST['subject']) and !empty($_POST['text']))  // блокировка пустых рассылок ('subject' и 'text')
        {
            $idzp = intval($_GET['value']);
            $subject = strip_tags($_POST['subject']);
            $mess = PHP_slashes($_POST['text']);
            if ($user->get_property('gid') < 25) $user_sql = " and `sp_zakup`.`user` = '" . $user->get_property('userID') . "'"; else $user_sql = '';
            $emailsup = $DB->getOne("SELECT `setting`.`value` FROM `setting` WHERE `setting`.`name`='emailsup'");
            $query = "SELECT `sp_order`.`user`, `punbb_users`.`email`
			FROM `sp_order` 
			LEFT JOIN `punbb_users` ON `punbb_users`.`id`=`sp_order`.`user`
			LEFT JOIN `sp_zakup` ON `sp_zakup`.`id`=`sp_order`.`id_zp`
			WHERE `sp_order`.`id_zp` = '" . intval($_GET['value']) . "' $user_sql
			GROUP BY `sp_order`.`user`";
            $mailarr = $DB->getAll($query);
            $mess = str_replace('="/', '="http://' . $_SERVER['HTTP_HOST'] . '/', $mess);
            $i = 0;
            foreach ($mailarr as $mail) {
                $sql = "INSERT INTO `message` (`from`, `to`, `date`,`subject`,`message`,`view`,`tresh`) 
			 VALUES ('" . $user->get_property('userID') . "', '" . $mail['user'] . "','" . time() . "',
				'$subject','$mess','0','0')";
                $DB->execute($sql);
                if (email_check($mail['email'])):
                    $m = new Mail;
                    $m->From($emailsup);
                    $m->To($mail['email']);
                    $m->Subject($subject);
                    $m->Body($mess);
                    $m->Priority(3);
                    $m->Send();
                    $i++;
                endif;
            }
            $message = "Рыссылка успешно завершена. Разослано $i пользователям в личку";
        }
        if (isset($_POST['send']))  // сообщения об ошибках
        {
            if (empty($_POST['subject'])):$err = 1;
                $message = "ОБ ЧЁМ вы хотели написать? Тему укажите!";endif;
            if (empty($_POST['text'])):$err = 1;
                $message = "ЧТО вы хотели разослать? Текст-то где?";endif;
        }
    }
// ------------------   Рассылка участникам закупки	----------------------------
// ------------------   Рассылка отказникам	----------------------------
    if ($_GET['section'] == 'sendRefuse' and intval($_GET['value']) > 0) {
        if (isset($_POST['send']) || !empty($_POST['subject']) || !empty($_POST['text']))  // блокировка пустых рассылок ('subject' и 'text')
        {
            $idzp = intval($_GET['value']);
            $subject = strip_tags($_POST['subject']);
            $mess = PHP_slashes($_POST['text']);
            if ($user->get_property('gid') < 25) $user_sql = " and `sp_zakup`.`user` = '" . $user->get_property('userID') . "'"; else $user_sql = '';
            $emailsup = $DB->getOne("SELECT `setting`.`value` FROM `setting` WHERE `setting`.`name`='emailsup'");
            $query = "SELECT `sp_order`.`user`, `punbb_users`.`email`
			FROM `sp_order` 
			LEFT JOIN `punbb_users` ON `punbb_users`.`id`=`sp_order`.`user`
			LEFT JOIN `sp_zakup` ON `sp_zakup`.`id`=`sp_order`.`id_zp`
			WHERE `sp_order`.`id_zp` = '" . intval($_GET['value']) . "' $user_sql
			AND `sp_order`.`status` = 2
			GROUP BY `sp_order`.`user`";
            $mailarr = $DB->getAll($query);
            $mess = str_replace('="/', '="http://' . $_SERVER['HTTP_HOST'] . '/', $mess);
            $i = 0;
            foreach ($mailarr as $mail) {
                $sql = "INSERT INTO `message` (`from`, `to`, `date`,`subject`,`message`,`view`,`tresh`) 
			 VALUES ('" . $user->get_property('userID') . "', '" . $mail['user'] . "','" . time() . "',
				'$subject','$mess','0','0')";
                $DB->execute($sql);
                if (email_check($mail['email'])):
                    $m = new Mail;
                    $m->From($emailsup);
                    $m->To($mail['email']);
                    $m->Subject($subject);
                    $m->Body($mess);
                    $m->Priority(3);
                    $m->Send();
                    $i++;
                endif;
            }
            $message = "Рыссылка успешно завершена. Разослано $i пользователям на X";
        }
        if (isset($_POST['send']))  // сообщения об ошибках
        {
            if (empty($_POST['subject'])):$err = 1;
                $message = "Ошибка: Вы не указали тему сообщения";endif;
            if (empty($_POST['text'])):$err = 1;
                $message = "Ошибка: Вы не указали текс сообщения";endif;
        }
    }
// ------------------   Рассылка отказникам	конец ----------------------------
    if ($_GET['section'] == 'editzp' and intval($_GET['value']) > 0) {
        $registry['premoder'] = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'premoder\'');
        $sql = "select * from `office` order by name ASC";
        $office = $DB->getAll($sql);
        if ($_POST['action'] == 'editzp') {
            //print_r($_POST);
            $idzp = intval($_POST['idzp']);
            $title = PHP_slashes(htmlspecialchars(strip_tags($_POST['title'])));
            if (utf8_strlen($title) > 150) $title = utf8_substr2($title, 0, 150) . '...';
            $textarea1 = PHP_slashes($_POST['textarea1']);
            $rekviz = PHP_slashes(strip_tags($_POST['rekviz']));
            $inform = PHP_slashes($_POST[inform]);
            $orgperc = preg_replace("/[^0-9]/", "", $_POST['orgperc']);
            $minimum = preg_replace("/[^0-9]/", "", $_POST['minimum']);
            $delivery = preg_replace("/[^0-9]/", "", $_POST['delivery']);
            $curs = preg_replace("/[^0-9,.]/", "", $_POST['curs']);
            $bonus = preg_replace("/[^0-9]/", "", $_POST['bonus']);
            $level = intval($_POST['level']);
            $cat_zp = $_POST['cat_zp'];
            $orgperc = intval($orgperc);
            $minimum = intval($minimum);
            $minType = intval($_POST['minType']);                                                     // Тип минималки
            $curs = floatval(str_replace(',', '.', $curs));
            $bonus = intval($bonus);
            $checkr = intval($_POST['checkr']);
            $office = serialize($_POST['office']);
            if ($checkr == 1) $russia = serialize($_POST['russia']);
            if ($checkr == 0) $russia = 0;
            $delivery = intval($delivery);
            $status = intval($_POST['status']);
            $alertnews = intval($_POST['alertnews']);
            $alertcomm = intval($_POST['alertcomm']);
            $type = intval($_POST['type']);
            $hot = intval($_POST['hot']);
            $top = intval($_POST['top']);
            $paytype = intval($_POST['paytype']);
            $message = '';
            $soonStop = intval($_POST['soonStop']);
            $dateStop = strval($_POST['dateStop']);
            // Вставлено лично мною !!!!!!!!!!!!!!!!!!!!!!!!
            $price_name1 = PHP_slashes($_POST['price_name1']);
            $price_name2 = PHP_slashes($_POST['price_name2']);
            $price_name3 = PHP_slashes($_POST['price_name3']);
            /////////////////////////////////////////////////
            if ($user->get_property('gid') < 25) $user_sql = "`user` = '" . $user->get_property('userID') . "' and "; else $user_sql = '';
            $testuser = $DB->getOne("SELECT count(id) FROM `sp_zakup` WHERE $user_sql `id`='$idzp'");
            if ($title == '') $message .= 'Ошибка: Вы не заполнили поле "Название".<br/>';
            if (strip_tags($_POST['textarea1']) == '') $message .= 'Ошибка: Вы не заполнили поле "Описание".<br/>';
            //if (intval($orgperc)==0) $message.='Ошибка: Вы не заполнили поле "Оргпроцент".<br/>';
            if (empty($cat_zp[0])) $message .= 'Ошибка: Вы не указали категорию.<br/>';
            if (intval($curs) == 0) $curs = 1;
            if ($idzp == 0) $message .= 'Ошибка: Не выбрана закупка (Если закупки нет в списке, значат её нужно закрепить за собой).<br/>';
            if ($testuser == 0) $message .= 'Ошибка: Вы не являетесь владельцем данной закупки.<br/>';
            if (empty($message)) {
                //сверка статуса для отправки сообщений
                $teststatus = $DB->getOne("SELECT status FROM `sp_zakup` WHERE $user_sql `id`='$idzp'");
                if ($_FILES['photo']['size'] > 0) {
                    $fotopath = $DB->getOne("SELECT foto FROM `sp_zakup` WHERE $user_sql `id`='$idzp'");
                    @unlink($fotopath);
                    $imgpath = save_image_on_server($_FILES['photo'], 'img/uploads/zakup/', $setimg1, $last_id);
                    if (!empty($imgpath[1])) {
                        $foto_sql = "`foto` = '" . $imgpath[1] . "',";
                    }
                }
                $file_sql = '';
                if ($_POST['delfile1']) {
                    $file1path = $DB->getOne("SELECT file1 FROM `sp_zakup` WHERE $user_sql `id`='$idzp'");
                    @unlink($file1path);
                    $file_sql .= "`file1` = '',";
                }
                if ($_POST['delfile2']) {
                    $file2path = $DB->getOne("SELECT file2 FROM `sp_zakup` WHERE $user_sql `id`='$idzp'");
                    @unlink($file2path);
                    $file_sql .= "`file2` = '',";
                }
                if ($_POST['delfile3']) {
                    $file3path = $DB->getOne("SELECT file3 FROM `sp_zakup` WHERE $user_sql `id`='$idzp'");
                    @unlink($file3path);
                    $file_sql .= "`file3` = '',";
                }
                if ($_FILES['file1']['size'] > 0) {
                    $file1path = $DB->getOne("SELECT file1 FROM `sp_zakup` WHERE $user_sql `id`='$idzp'");
                    @unlink($file1path);
                    $setfile = null;
                    $imgpath = save_file_on_server($_FILES['file1'], 'img/uploads/zakup/', $setfile, '1' . $idzp, $file1path);
                    if (!empty($imgpath[1])) {
                        $file_sql .= "`file1` = '" . $imgpath[1] . "',";
                    }
                }
                if ($_FILES['file2']['size'] > 0) {
                    $file2path = $DB->getOne("SELECT file2 FROM `sp_zakup` WHERE $user_sql `id`='$idzp'");
                    @unlink($file2path);
                    $imgpath = save_file_on_server($_FILES['file2'], 'img/uploads/zakup/', $setfile, '2' . $idzp, $file2path);
                    if (!empty($imgpath[1])) {
                        $file_sql .= "`file2` = '" . $imgpath[1] . "',";
                    }
                }
                if ($_FILES['file3']['size'] > 0) {
                    $file3path = $DB->getOne("SELECT file3 FROM `sp_zakup` WHERE $user_sql `id`='$idzp'");
                    @unlink($file3path);
                    $imgpath = save_file_on_server($_FILES['file3'], 'img/uploads/zakup/', $setfile, '3' . $idzp, $file3path);
                    if (!empty($imgpath[1])) {
                        $file_sql .= "`file3` = '" . $imgpath[1] . "',";
                    }
                }
                if ($user->get_property('gid') < 25) $user_sql = "and `sp_zakup`.`user` = " . $user->get_property('userID'); else $user_sql = '';
                $textarea1 = tolink($textarea1);
                // Здесь добавлены строки 'price_name'
                $sql = "UPDATE `sp_zakup` SET 
				`title` = '$title',
				`text` = '$textarea1',
				`inform` = '$inform',
				`level` = '$level',
				`proc` = '$orgperc',
				`min` = '$minimum',
				`minType` = '$minType',
				`curs` = '$curs',
				`bonus` = '$bonus',
				`dost` = '$delivery',
				`russia` = '$russia',
				`status` = '$status',
				`rekviz` = '$rekviz',
				`type` = '$type',
				$foto_sql $file_sql
				`alertnews` = '$alertnews',
				`alertcomm` = '$alertcomm',
				`office` = '$office',
				`hot` = '$hot',
				`top` = '$top',				
				`soonStop` = '$soonStop',
				`dateStop` = '$dateStop',
				`paytype`= '$paytype',
				`price_name1` = '$price_name1',
				`price_name2` = '$price_name2',
				`price_name3` = '$price_name3'
				WHERE `sp_zakup`.`id` ='$idzp' $user_sql";
                $DB->execute($sql);
                //удаляем категории, зетем создаем новые (редактирование закупки)
                $sql = "DELETE FROM `sp_cat_sub` WHERE `sp_cat_sub`.`zakup` = " . $idzp;
                $DB->execute($sql);
                foreach ($cat_zp as $catz):
                    $query = "INSERT INTO sp_cat_sub (`zakup`,`cat`) VALUES('$idzp','$catz');";
                    $DB->execute($query);
                endforeach;
                // рассылка оповещинй на емаил заказчикам
                if ($status > $teststatus) {
                    $sql = "  select `punbb_users`.`email`
					from `sp_order` 
					JOIN `punbb_users` ON `sp_order`.`user`=`punbb_users`.`id`
					where `sp_order`.`id_zp`='" . $idzp . "'
					GROUP by `punbb_users`.`email`";
                    $allemail = $DB->getAll($sql);
                    $email_admin = $DB->getOne('SELECT `setting`.`value` 
				FROM `setting`
				WHERE `setting`.`name`=\'email_admin\'');
                    $allemail[count($allemail)]['email'] = $email_admin; // письмо админу
                    $sql = "  select `sp_status`.`name`
					FROM `sp_status`
					where `sp_status`.`id`='" . $status . "'";
                    $newstatus = $DB->getAll($sql);
                    if (count($allemail) > 0) {
                        $emailsup = $DB->getOne('SELECT `setting`.`value` 
					FROM `setting`
					WHERE `setting`.`name`=\'emailsup\'');
                        foreach ($allemail as $alm):
                            $m = new Mail; // начинаем
                            $m->From("$emailsup"); // от кого отправляется почта
                            $m->To($alm['email']); // кому адресованно
                            $m->text_html = "text/html";
                            $m->Subject("Статус закупки изменён - " . $_SERVER['HTTP_HOST']);
                            $m->Body("
                                Здравствуйте,<br/>
                                Это письмо отправлено вам сайтом: <a href=\"" . $_SERVER['HTTP_HOST'] . "\">" . $_SERVER['HTTP_HOST'] . "</a><br/>
                                <p>
                                Организатор изменил(а) статус закупки \"$title\" на \"" . $newstatus[0]['name'] . "\".
                                </p><br/>
                                <p>Для просмотра закупки перейдите по ссылке:<br/>
                                <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $idzp . "\">http://" . $_SERVER['HTTP_HOST'] . "/com/org/open/" . $idzp . "</a></p>
                                ");
                                                            $m->Priority(3);    // приоритет письма
                            $m->Send();    // а теперь пошла отправка
                        endforeach;
                    }
                }
                header('Location: /com/org/open/' . $_GET['value']);    // после редактирования закупки выбрасывает не в кабинет организатора, а в эту же закупку
                exit;
                $oke = 1;
            }
        }
        if ($user->get_property('gid') < 25) $user_sql = "and `sp_zakup`.`user`='" . $user->get_property('userID') . "'"; else $user_sql = '';
        $sql = "	SELECT `sp_zakup`.* , `sp_status`.`name`,`punbb_users`.`username`,`sp_level`.`name` as `levname`,`cities`.`city_name_ru`,`sp_url_ckeck`.`brend`,`sp_url_ckeck`.`url`
		FROM `sp_zakup`
		LEFT JOIN `sp_status` ON `sp_zakup`.`status`=`sp_status`.`id`
		JOIN `sp_level` ON `sp_zakup`.`level`=`sp_level`.`id`
		JOIN `sp_url_ckeck` ON `sp_zakup`.`id_check`=`sp_url_ckeck`.`id`
		JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
		JOIN `cities` ON `cities`.`id_city`=`punbb_users`.`city`
		WHERE `sp_zakup`.`id`='" . intval($_GET['value']) . "' $user_sql";
//	echo $sql;
        $editzp = $DB->getAll($sql);
//print_r($editzp);
        if (count($editzp) > 0) {
            $catsub = $DB->getAll('SELECT cat FROM sp_cat_sub WHERE zakup=' . intval($_GET['value']));
            foreach ($catsub as $cats) {
                $editzp[0]['cat_zp'][] = $cats['cat'];
            }
        }
        $query = "SELECT * FROM `sp_level`";
        $level = $DB->getAll($query);
        $query = "SELECT * FROM `sp_delivery`";
        $delivery = $DB->getAll($query);
        $query = "SELECT * FROM `sp_status` ORDER BY id ASC";
        $statuslist = $DB->getAll($query);
        $all = $DB->getAll('SELECT * FROM sp_cat WHERE podcat=0');
        $i = 0;
        foreach ($all as $num):
            $cat_zp[$num['id']][0] = $num;
            $i++;
        endforeach;
        $all = $DB->getAll('SELECT * FROM sp_cat WHERE podcat>0');
        $i = 0;
        foreach ($all as $num):
            $cat_zp[$num['podcat']][] = $num;
            $i++;
        endforeach;
        if (count($editzp) == 1)
            if (!empty($editzp[0]['foto'])) {
                $split = explode('/', $editzp[0]['foto']);
                $img_path2 = '/images/' . $split[2] . '/125/100/1/' . $split[3];
            } else $img_path2 = '/' . $theme . 'images/no_photo125x100.png';
        $editzp[0]['office'] = unserialize($editzp[0]['office']);
    }
    if ($_GET['section'] == 'fixed') {
        $sql = 'SELECT * FROM `sp_url_ckeck` WHERE `user` = ' . $user->get_property('userID');
        $myfixed = $DB->getAll($sql);
        if ($_POST['action'] == 'checked') {
            $url = PHP_slashes(htmlspecialchars(strip_tags($_POST['url'])));
            $url = str_replace('http://', '', $url);
            $url = str_replace('www.', '', $url);
            $testcheck = $DB->getAll("
			SELECT `punbb_users`.`id`, `punbb_users`.`username` 
			FROM `sp_url_ckeck` 
			LEFT JOIN `punbb_users` ON `sp_url_ckeck`.`user`=`punbb_users`.`id`
			WHERE `sp_url_ckeck`.`url` = '$url' and `sp_url_ckeck`.`city`='" . $checkcity . "'");
        }
        if ($_POST['action'] == 'fixedadd') {
            $url = PHP_slashes(htmlspecialchars(strip_tags($_POST['url'])));
            $url = str_replace('http://', '', $url);
            $url = str_replace('www.', '', $url);
            $url = str_replace('/', '', $url);    //убираем слэш в адресе
            $brend = PHP_slashes(htmlspecialchars(strip_tags($_POST['brend'])));
            $desc = PHP_slashes(htmlspecialchars(strip_tags($_POST['desc'])));
            $testcheck = $DB->getAll("
			SELECT `punbb_users`.`id`, `punbb_users`.`username` 
			FROM `sp_url_ckeck` 
			LEFT JOIN `punbb_users` ON `sp_url_ckeck`.`user`=`punbb_users`.`id`
			WHERE `sp_url_ckeck`.`url` = '$url' and `sp_url_ckeck`.`city`='" . $checkcity . "'");
            if (empty($url)) $message = 'Укажите адрес сайта закупки (URL).';
            if (count($testcheck) > 0) $message = 'Ошибка:';
            if (empty($message)) {
                $sql = "INSERT INTO `sp_url_ckeck` (`user`,`url`,`desc`,`brend`,`date`,`city`) 
				VALUES ('" . $user->get_property('userID') . "','$url','$brend','$desc','" . time() . "','$checkcity')"; // ORDER BY id DESC LIMIT $flatCount
                $DB->execute($sql);
            } else $_POST['action'] = 'checked';
        }
    }
    if ($_GET['section'] == 'add') {
        $registry['premoder'] = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'premoder\'');
        $sql = "select * from `office` order by name ASC";
        $office = $DB->getAll($sql);
        if ($_POST['action'] == 'addzp') {
            //print_r($_POST);
            $id_check = intval($_POST['id_check']);
            $title = PHP_slashes(htmlspecialchars(strip_tags($_POST['title'])));
            if (utf8_strlen($title) > 150) $title = utf8_substr2($title, 0, 150) . '...';
            $textarea1 = PHP_slashes($_POST['textarea1']);
			$date = time();
            $rekviz = PHP_slashes(strip_tags($_POST['rekviz']));
            $inform = PHP_slashes(htmlspecialchars(strip_tags($_POST[inform])));
            $orgperc = preg_replace("/[^0-9]/", "", $_POST['orgperc']);
            $minimum = preg_replace("/[^0-9]/", "", $_POST['minimum']);
            $delivery = preg_replace("/[^0-9]/", "", $_POST['delivery']);
            $curs = preg_replace("/[^0-9,.]/", "", $_POST['curs']);
            $bonus = preg_replace("/[^0-9]/", "", $_POST['bonus']);
            $level = intval($_POST['level']);
            $cat_zp = $_POST['cat_zp'];
            $orgperc = intval($orgperc);
            $minimum = intval($minimum);
            $minType = intval($_POST['minType']);                // Тип минималки при создании закупки
            $curs = floatval(str_replace(',', '.', $curs));
            $bonus = intval($bonus);
            $delivery = intval($delivery);
            $checkr = intval($_POST['checkr']);
            if ($checkr == 1) $russia = serialize($_POST['russia']);
            if ($checkr == 0) $russia = 0;
            $status = intval($_POST['status']);
            $alertnews = intval($_POST['alertnews']);
            $alertcomm = intval($_POST['alertcomm']);
            $paytype = intval($_POST['paytype']);
            $office = serialize($_POST['office']);
            $hot = intval($_POST['hot']);
            $top = intval($_POST['top']);
            /////////////////////////////////Добавлено лично мною
            $price_name[1] = PHP_slashes($_POST['price_name1']);
            $price_name[2] = PHP_slashes($_POST['price_name2']);
            $price_name[3] = PHP_slashes($_POST['price_name3']);
            ////////////////////////////////////////////////////////
            $type = intval($_POST['type']);
            if ($status > 2) $status = 2;
            $message = '';
            $testtitle = $DB->getOne("SELECT count(id) FROM `sp_zakup` WHERE `title` = '" . $title . "'");
            $testfixed = $DB->getOne("SELECT count(id) FROM `sp_url_ckeck` WHERE `user`='" . $user->get_property('userID') . "' and `id` = '" . $id_check . "'");
            if ($testtitle > 0) $message = 'Ошибка: Закупка с таким названием уже существует<br/>';
            if ($testfixed == 0) $message .= 'Ошибка: Закупка не закреплена за вами<br/>';
            if ($title == '') $message .= 'Ошибка: Вы не заполнили поле "Название".<br/>';
            if (strip_tags($_POST['textarea1']) == '') $message .= 'Ошибка: Вы не заполнили поле "Описание".<br/>';
            //if (intval($orgperc)==0) $message.='Ошибка: Вы не заполнили поле "Оргпроцент".<br/>';
            if (empty($cat_zp[0])) $message .= 'Ошибка: Вы не указали категорию.<br/>';
            if (intval($curs) == 0) $curs = 1;
            if ($id_check == 0) $message .= 'Ошибка: Не выбрана закупка (Если закупки нет в списке, значат её нужно закрепить за собой).<br/>';
            if (empty($message)) {
                $textarea1 = tolink($textarea1);
                $query = "INSERT INTO sp_zakup (`user`,`title`,`text`,`inform`,`level`,`proc`,`min`,`minType`,`curs`,`bonus`,
						`dost`,`status`,`foto`,`alertnews`,`alertcomm`,`id_check`,`russia`,`date`,`rekviz`,`type`,`office`,`paytype`,`hot`,`top`) 
				VALUES(
				'" . $user->get_property('userID') . "','$title','$textarea1','$inform','$level','$orgperc',
				'$minimum','$minType','$curs','$bonus','$delivery','$status','','$alertnews','$alertcomm','$id_check','$russia',$date,'$rekviz','$type','$office','$paytype','$hot','$top');";
                $DB->execute($query);
                $sql = "SELECT LAST_INSERT_ID()";
                $last_id = $DB->getOne($sql);
                //save_image_on_server($_FILES['photo'],'img/uploads/zakup/',$setimg1,'newname','delpath');
                $imgpath = save_image_on_server($_FILES['photo'], 'img/uploads/zakup/', $setimg1, $last_id);
                if (!empty($imgpath[1])) {
                    $sql = "UPDATE `sp_zakup` SET 
					`foto` = '" . $imgpath[1] . "'
					WHERE `sp_zakup`.`id` = " . $last_id;
                    $DB->execute($sql);
                }
                if ($_FILES['file1']['size'] > 0) {
                    $setfile = null;
                    $imgpath = save_file_on_server($_FILES['file1'], 'img/uploads/zakup/', $setfile, '1' . $last_id);
                    if (!empty($imgpath[1])) {
                        $sql = "UPDATE `sp_zakup` SET 
					`file1` = '" . $imgpath[1] . "'
					WHERE `sp_zakup`.`id` = " . $last_id;
                        $DB->execute($sql);
                        /////////////////////////////////////////////////
                        $sql = "UPDATE `sp_zakup` SET 
					`price_name1` = '" . $price_name[1] . "'
					WHERE `sp_zakup`.`id` = " . $last_id;
                        $DB->execute($sql);

                        /////////////////////////////////////////////////
                    }
                }
                if ($_FILES['file2']['size'] > 0) {
                    $imgpath = save_file_on_server($_FILES['file2'], 'img/uploads/zakup/', $setfile, '2' . $last_id);
                    if (!empty($imgpath[1])) {
                        $sql = "UPDATE `sp_zakup` SET 
					`file2` = '" . $imgpath[1] . "'
					WHERE `sp_zakup`.`id` = " . $last_id;
                        $DB->execute($sql);
                        ///////////////////////////////////////////////////
                        $sql = "UPDATE `sp_zakup` SET 
					`price_name2` = '" . $price_name[2] . "'
					WHERE `sp_zakup`.`id` = " . $last_id;
                        $DB->execute($sql);

                        //////////////////////////////////////////////////
                    }
                }
                if ($_FILES['file3']['size'] > 0) {
                    $imgpath = save_file_on_server($_FILES['file3'], 'img/uploads/zakup/', $setfile, '3' . $last_id);
                    if (!empty($imgpath[1])) {
                        $sql = "UPDATE `sp_zakup` SET 
					`file3` = '" . $imgpath[1] . "'
					WHERE `sp_zakup`.`id` = " . $last_id;
                        $DB->execute($sql);
                        //////////////////////////////////////////////////
                        $sql = "UPDATE `sp_zakup` SET 
					`price_name3` = '" . $price_name[3] . "'
					WHERE `sp_zakup`.`id` = " . $last_id;
                        $DB->execute($sql);
                        //////////////////////////////////////////////////
                    }
                }
                foreach ($cat_zp as $catz):
                    $query = "INSERT INTO sp_cat_sub (`zakup`,`cat`) VALUES('$last_id','$catz');";
                    $DB->execute($query);
                endforeach;
                if ($status == 2) {
                    $emailsup = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'emailsup\'');
                    $email_admin = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'email_admin\'');
                    $m = new Mail; // начинаем
                    $m->From("$emailsup"); // от кого отправляется почта
                    $m->To($email_admin); // кому адресованно
                    $m->text_html = "text/html";
                    $m->Subject("Новая закупка готова к открытию - " . $_SERVER['HTTP_HOST']);
                    $m->Body("
                        Здравствуйте,<br/>
                        Это письмо отправлено вам сайтом: <a href=\"" . $_SERVER['HTTP_HOST'] . "\">" . $_SERVER['HTTP_HOST'] . "</a><br/>
                        <p>
                        На сайте появилась новая закупка готовая к открытию \"" . $title . "\".
                        </p>
                        Для просмотра и модерирования перейдите по ссылке:<br/>
                        <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/partner/index.php?component=zakup\">http://" . $_SERVER['HTTP_HOST'] . "/partner/index.php?component=zakup</a><br/>
                        ");
                                            $m->Priority(3);    // приоритет письма
                    $m->Send();    // а теперь пошла отправка
                }
                header('location: /com/org');
                $oke = 1;
            }
        }
        $query = "	SELECT `sp_url_ckeck`.*, `sp_zakup`.`id_check`,`sp_zakup`.`status`
			FROM `sp_url_ckeck` 
			LEFT JOIN `sp_zakup` ON `sp_url_ckeck`.`id`=`sp_zakup`.`id_check`
			
			WHERE `sp_url_ckeck`.`user`='" . $user->get_property('userID') . "' ORDER BY `sp_url_ckeck`.`brend` ASC";
        $total_zp = $DB->getAll($query);
        //WHERE `sp_url_ckeck`.`user`=".$user->get_property('userID');
        $query = "SELECT * FROM `sp_delivery`";
        $delivery = $DB->getAll($query);
        $query = "SELECT * FROM `sp_level`";
        $level = $DB->getAll($query);
        $all = $DB->getAll('SELECT * FROM sp_cat WHERE podcat=0');
        $i = 0;
        foreach ($all as $num):
            $cat_zp[$num['id']][0] = $num;
            $i++;
        endforeach;
        $all = $DB->getAll('SELECT * FROM sp_cat WHERE podcat>0');
        $i = 0;
        foreach ($all as $num):
            $cat_zp[$num['podcat']][] = $num;
            $i++;
        endforeach;

    }
    if ($_POST['fixeddel'] == 2) {
        $testcheck = $DB->getOne("
	SELECT count(`sp_url_ckeck`.`id`)
	FROM `sp_url_ckeck` 
	WHERE `sp_url_ckeck`.`id` = '" . intval($_GET['value']) . "' 
		and `sp_url_ckeck`.`user`='" . $user->get_property('userID') . "'");
        if ($testcheck > 0) {
            $query = "SELECT `id`, `foto` FROM `sp_zakup` WHERE `user`='" . $user->get_property('userID') . "' 
			and `id_check` = " . intval($_GET['value']);
            $zakup = $DB->getAll($query);
            if ($zakup[0]['id'] > 0) {
                $query = "SELECT id FROM `sp_ryad` WHERE `id_zp` = " . $zakup[0]['id']; // ORDER BY id DESC LIMIT $flatCount
                $ryad = $DB->getAll($query);
                foreach ($ryad as $rd) {
                    $query = "SELECT id FROM `sp_size` WHERE `id_ryad` = " . $rd['id']; // ORDER BY id DESC LIMIT $flatCount
                    $size = $DB->getAll($query);
                    foreach ($size as $it) {// удаляем все заказы в этой закупке
                        $query = "DELETE FROM `sp_order` WHERE `id_order` = " . $it['id'];
                        $DB->execute($query);
                    }
                    //удаляем все размеры из рядов этой закупки
                    $query = "DELETE FROM `sp_size` WHERE `id_ryad` = " . $rd['id'];
                    $DB->execute($query);
                }
                //удаляем все ряды
                $query = "DELETE FROM `sp_ryad` WHERE `id_zp` = " . $zakup[0]['id'];
                $DB->execute($query);
                //удаляем закупку
                $query = "DELETE FROM `sp_zakup` WHERE `id` = " . $zakup[0]['id'];
                $DB->execute($query);
                //удаляем категории
                $sql = "DELETE FROM `sp_cat_sub` WHERE `sp_cat_sub`.`zakup` = " . $zakup[0]['id'];
                $DB->execute($sql);
                //echo $zakup[0]['foto'];
                @unlink($zakup[0]['foto']);
            }
            //удаляем из закрепленных
            $sql = "DELETE FROM `sp_url_ckeck` WHERE `sp_url_ckeck`.`id` = " . intval($_GET['value']);
            $DB->execute($sql);

        }
        header('Location: /com/org/fixed');
    }
    if ($_POST['fixeddel'] == 1) {
        header('Location: /com/org/fixed');
    }

//    Дублирование закупки
    if (!empty($_GET['value']) and $user->get_property('gid') >= 23 and ($_GET['section'] == 'reopenduble' || $_GET['section'] == 'reopendubord')) {
        $query = "SELECT `id`, `user` FROM `sp_zakup` WHERE `id` = " . intval($_GET['value']);
        $zakup = $DB->getAll($query);

        if ($zakup[0]['id'] > 0 and ($user->get_property('userID') == $zakup[0]['user'] or $user->get_property('gid') == 25)) {
            $id = intval($_GET['value']);
            $query = "CREATE TEMPORARY TABLE foo AS SELECT * FROM sp_zakup WHERE id = $id;";
            $DB->execute($query);

			$date =  time();
            $query = "UPDATE foo SET id=NULL, status=1, date=$date;";
            if ($_GET['section'] == 'reopendubord') {
                $query = "UPDATE foo SET id=NULL, status=3;";
            }
            $DB->execute($query);

            $query = "INSERT INTO sp_zakup SELECT * FROM foo;";
            $DB->execute($query);

            $newid = $DB->id;
            $query = "DROP TABLE foo";
            $DB->execute($query);

            $id = intval($_GET['value']);
            $query = "CREATE TEMPORARY TABLE foo AS SELECT * FROM sp_cat_sub WHERE zakup = $id;";
            $DB->execute($query);

            $query = "UPDATE foo SET zakup=$newid;";
            $DB->execute($query);

            $query = "INSERT INTO sp_cat_sub SELECT * FROM foo;";
            $DB->execute($query);

            $query = "DROP TABLE foo";
            $DB->execute($query);

            $sql = "SELECT title, foto, file1, file2, file3 FROM sp_zakup WHERE id ='$newid'";
            $tt = $DB->getAll($sql);

            if (strpos($tt[0]['title'], 'Выкуп:')) {
                $t1 = explode('Выкуп:', $tt[0]['title']);
                $baseName = $t1[0];
                $t1 = intval(trim($t1[1])) + 1;
                $tt[0]['title'] = $baseName . 'Выкуп: ' . $t1;
                }
            else
                $tt[0]['title'] = $tt[0]['title'] . ' Выкуп: 2';
                $tt[0]['title'] = PHP_slashes($tt[0]['title']);
                $sql_f = '';

//Дублирование картинок закупки
            if ($tt[0]['foto']) {
                $path_info = pathinfo($tt[0]['foto']);

                $temp = $path_info['filename'];
                $initBaseName = explode('.', $temp);

                $sourceFile = ($path_info['dirname'] . '/' . $initBaseName[0] . '.' .$path_info['extension']);
                $newFile = ($path_info['dirname'] . '/' . $newid . '.' .$path_info['extension']);
                copy($sourceFile , $newFile);                                                                           //Копирование файла картинки закупки
                $sql_f .= ", `foto` = '$newFile'";
                }


            /*for ($a = 1; $a <= 3; $a++) {
                if ($tt[0]['file' . $a]) {
                    $path_info = pathinfo($tt[0]['file' . $a]);

//                    $temp = $path_info;

                    $nf = str_replace($path_info['extension'], '', $tt[0]['file' . $a]);
                    $nf = $baseName . '.' . $newid . '.' . $path_info['extension'];
                    @copy($path_info['dirname' . $a], $nf);
                    $sql_f .= ", `file{$a}` = '$nf'";
                }
            }*/
            $sql = "UPDATE sp_zakup SET title = '{$tt[0]['title']}' $sql_f WHERE id = '$newid'";
            $DB->execute($sql);

            $new_id_zp = $newid;
            $sql = "UPDATE sp_zakup SET soonStop = 0 WHERE id = '$newid'";                                              // При дублировании закупки убираем статус "Скоро стоп"
            $DB->execute($sql);

            $sql = "UPDATE sp_zakup SET dateStop = Null WHERE id = '$newid'";                                           // При дублировании закупки убираем дату стопа
            $DB->execute($sql);

            $sql = "UPDATE sp_zakup SET inform = Null WHERE id = '$newid'";                                             // При дублировании закупки убираем информацию
            $DB->execute($sql);

            $sql = "SELECT * FROM sp_ryad WHERE id_zp = $id;";
            $idryad = $DB->getAll($sql);

            $i = 0;
            $sql_id = '';
            foreach ($idryad as $it) {
                $i++;
                $sql_id .= $it['id'];
                if ($i < count($idryad)) $sql_id .= ',';
            }

            $sql = "SELECT * FROM sp_size WHERE id_ryad IN ($sql_id) and duble=1 ORDER BY id ASC;";
            $sizeArr = $DB->getAll($sql);

            $NewSizeArr = array();
            foreach ($sizeArr as $it) {
                $NewSizeArr[$it['id_ryad']][$it['id']] = $it;
            }
            if ($_GET['section'] == 'reopendubord') {
                $sql = "SELECT * FROM sp_order WHERE id_zp = '{$zakup[0]['id']}' and status!=1;";
                $orderArr = $DB->getAll($sql);
                $i = 0;
                $sql_id_order = '';
                $NewOrderArr = [];
                $arr_id_order = [];//список id в массиве
                foreach ($orderArr as $it) {
                    $i++;
                    $sql_id_order .= $it['id_order'];
                    $arr_id_order[] = $it['id_order'];
                    if ($i < count($orderArr)) $sql_id_order .= ',';
                    $it['id_zp'] = $newid;
                    $NewOrderArr[$it['id_order']] = $it;
                }
                $sql = "UPDATE sp_size SET user=NULL WHERE id IN (" . $sql_id_order . ")";
                $DB->execute($sql);

                $sql = "SELECT MAX(`id`) FROM `sp_size`; ";
                $new_id_size = $DB->getOne($sql);
            }
            foreach ($idryad as $it) {
                $it['message'] = PHP_slashes($it['message']);
                $it['mess_edit'] = PHP_slashes($it['mess_edit']);
                $it['size'] = PHP_slashes($it['size']);
                $it['title'] = PHP_slashes($it['title']);
                $it['articul'] = PHP_slashes($it['articul']);
                $it['tempOff'] = PHP_slashes($it['tempOff']);
                $sql = "INSERT INTO sp_ryad (`user`,`id_zp`,`title`,`articul`,`message`,`mess_edit`,`price`,`size`,`duble`,`auto`,`spec`,
					    `position`,`photo`,`cat`,`tempOff`)
                        VALUES ('{$it['user']}','{$newid}','{$it['title']}','{$it['articul']}','{$it['message']}','{$it['mess_edit']}',
                        '{$it['price']}','{$it['size']}',
                        '1','{$it['auto']}','{$it['spec']}','{$it['position']}','{$it['photo']}','{$it['cat']}', '{$it['tempOff']}')";  // Добавляем "временно нет в наличии" tempOff
                $DB->execute($sql);

//echo $sql.'<br/>';
                $newidr = $DB->id;
                $filelist = array();
                $filelist2 = array();
                $path = "fmanager/uploads/zakup/{$id}/ryad/{$it['id']}/";
                $newpath = "fmanager/uploads/zakup/{$new_id_zp}/ryad/{$newidr}/";
                if ($handle = opendir($path)) {
                    while ($entry = readdir($handle)) {
                        if (is_file($path . $entry)) {
                            $filelist[] = $path . $entry;
                            $filelist2[] = $path . 'thumbnail/' . $entry;
                            if (!is_dir($newpath)) {
                                @mkdir("fmanager/uploads/zakup/{$new_id_zp}", 0777);
                                @mkdir("fmanager/uploads/zakup/{$new_id_zp}/ryad", 0777);
                                @mkdir("fmanager/uploads/zakup/{$new_id_zp}/ryad/{$newidr}", 0777);
                                @mkdir("fmanager/uploads/zakup/{$new_id_zp}/ryad/{$newidr}/thumbnail", 0777);
                            }
                            copy($path . $entry, $newpath . $entry);
                            copy($path . 'thumbnail/' . $entry, $newpath . 'thumbnail/' . $entry);
                        }
                    }
                    closedir($handle);
                }

                if ($_GET['section'] == 'reopendubord') {
                    $i = 0;
                    foreach ($NewSizeArr[$it['id']] as $NewSizeItem) {
                        $i++;
                        $min_size_temp = null;
                        if ($min_size_temp > $NewSizeItem['id'] or $i == 1) $min_size_temp = $NewSizeItem['id'];
                    }
                    $new_id_size++;
                    $NNewSizeArr = array();
                    foreach ($NewSizeArr[$it['id']] as $k => $v) {
                        if (!in_array($k, $arr_id_order)) $v['user'] = '';
                        $v['id'] = $new_id_size + ($v['id'] - ($min_size_temp - 1));
                        $NNewSizeArr[$it['id']][$k] = $v;
                        $NewOrderArr[$k]['id_ryad'] = $newidr;
                        if (is_array($NewOrderArr[$k])) $NewOrderArr[$k]['id_order'] = $new_id_size + ($NewOrderArr[$k]['id_order'] - ($min_size_temp - 1));
                    }
                    $NewSizeArr = $NNewSizeArr;

                }
                foreach ($NewSizeArr[$it['id']] as $NewSizeItem) {
                    if ($_GET['section'] == 'reopendubord') {
                        $news_size_id = $NewSizeItem['id'];
                        $news_size_user = $NewSizeItem['user'];
                    } else {
                        $news_size_id = '';
                        $news_size_user = '';
                    }

                    $sql = "INSERT INTO sp_size (`id`,`id_ryad`,`id_zp`,`name`,`user`,`duble`)
					        VALUES ('$news_size_id','$newidr',$newid,'{$NewSizeItem['name']}','$news_size_user','1')";
                    $DB->execute($sql);

                    $new_id_size = $DB->id;
                }

            }

 //todo Дублирование закупки с переносом невыкупленных заказов
            if ($_GET['section'] == 'reopendubord') {
                foreach ($NewOrderArr as $NewOrderItem) {
                    $sql = "INSERT INTO sp_order (`id`,`user`,`id_order`,`message`,`mess_edit`,`color`,`kolvo`,`oversize`,`date`,`uniqcod`,`id_zp`,
					        `id_ryad`,`dateunix`,`status`,`addrDelivery`)
					        VALUES ('','{$NewOrderItem['user']}','{$NewOrderItem['id_order']}','{$NewOrderItem['message']}','{$NewOrderItem['mess_edit']}',					                
					                '{$NewOrderItem['color']}','{$NewOrderItem['kolvo']}', 000, '{$NewOrderItem['date']}','{$NewOrderItem['uniqcod']}',
					                '{$NewOrderItem['id_zp']}','{$NewOrderItem['id_ryad']}','{$NewOrderItem['dateunix']}','{$NewOrderItem['status']}', 000)";
                    if ($NewOrderItem['user'])
                        $DB->execute($sql);                                                                             // todo Вставляем закзы, кроме статуса=1 (включено в счёт), в новую закупку
                    //todo Добавляем в INTO столбцы oversize и addDelivery
                    //todo Заменить в VALUES значение 000

                }
                $sql = "DELETE FROM sp_order WHERE id_zp = '{$zakup[0]['id']}' and status!=1;";
                $DB->execute($sql);                                                                                     // todo Удаляем все заказы, кроме статуса=1 (включено в счёт) из текущей закупки
            }


            $sql = "SELECT id, photo FROM sp_ryad WHERE id_zp ='$new_id_zp'";
            $tt = $DB->getAll($sql);
            foreach ($tt as $t) {
                $sql_f = '';
                if ($t['photo'] and ((!strpos(' ' . $t['photo'], 'http://') and !strpos(' ' . $t['photo'], 'https://')) || strpos(' ' . $t['photo'], $_SERVER['HTTP_HOST']))) {
                    $path_info = pathinfo($t['photo']);
/*                    $nf = str_replace($path_info['extension'], '', $t['photo']);
                    $nf = $nf . $new_id_zp . '.' . $path_info['extension'];*/

                    $newFile = ($path_info['dirname'] . '/' . 'r' . $t['id'] . '.' . $path_info['extension']);

                    copy($t['photo'], $newFile);                                                                        // Копирование файлов картинок рядов в закупке
                    $sql_f .= " `photo` = '$newFile'";
                    $sql = "UPDATE sp_ryad SET $sql_f WHERE id = '{$t['id']}'";
                    $DB->execute($sql);

                }
            }
            $query = "SELECT * FROM `sp_cat_sub` WHERE `zakup` = " . $id; // ORDER BY id DESC LIMIT $flatCount
            $sp_cat_sub = $DB->getAll($query);
            foreach ($sp_cat_sub as $item) {
                $query = "INSERT INTO `sp_cat_sub` VALUES('','$newid','{$item['cat']}');";
                $DB->execute($query);
            }

        }
        header('Location: /com/org/');
    }
    if (!empty($_GET['value']) and $user->get_property('gid') >= 23 and $_GET['section'] == 'reopen') {
        $query = "SELECT `id`, `user` FROM `sp_zakup` WHERE `id` = " . intval($_GET['value']);
        $zakup = $DB->getAll($query);
        if ($zakup[0]['id'] > 0 and ($user->get_property('userID') == $zakup[0]['user'] or $user->get_property('gid') == 25)) {
            $query = "SELECT id FROM `sp_ryad` WHERE `id_zp` = " . $zakup[0]['id']; // ORDER BY id DESC LIMIT $flatCount
            $ryad = $DB->getAll($query);
            foreach ($ryad as $rd) {
                $query = "SELECT id FROM `sp_size` WHERE `id_ryad` = " . $rd['id']; // ORDER BY id DESC LIMIT $flatCount
                $size = $DB->getAll($query);
                foreach ($size as $it) {//
                    $query = "DELETE FROM `sp_order` WHERE `id_order` = " . $it['id'];
                    $DB->execute($query);
                }
                $query = "UPDATE `sp_size` SET  `user` = '0', `anonim`='0'
					 WHERE `id_ryad` = " . $rd['id'];
                $DB->execute($query);
            }
            $query = "UPDATE `sp_zakup` SET `status` = '3'
					 WHERE `id` = " . intval($_GET['value']);
            $DB->execute($query);
            $query = "UPDATE `sp_addpay` SET `status` = '0'
					 WHERE `zp_id` = " . intval($_GET['value']);
            $DB->execute($query);
        }
        $query = "DELETE FROM `sp_addpay` 
			WHERE `sp_addpay`.`zp_id` = '{$zakup[0]['id']}' ";
        $DB->execute($query);
        $query = "DELETE FROM `sp_addpayorg` 
			WHERE `sp_addpayorg`.`zp_id` = '{$zakup[0]['id']}' ";
        $DB->execute($query);
        $query = "DELETE FROM `sp_orgorder` 
			WHERE `sp_orgorder`.`zp_id` = '{$zakup[0]['id']}' ";
        $DB->execute($query);
        header('Location: /com/org/open/' . intval($_GET['value']));
        exit;
    }
    if (!empty($_GET['value']) and $user->get_property('gid') == 25 and $_GET['section'] == 'delzp') {
        $query = "SELECT `id`, `foto` FROM `sp_zakup` WHERE `id` = " . intval($_GET['value']);
        $zakup = $DB->getAll($query);
        if ($zakup[0]['id'] > 0) {
            $query = "SELECT id FROM `sp_ryad` WHERE `id_zp` = " . $zakup[0]['id']; // ORDER BY id DESC LIMIT $flatCount
            $ryad = $DB->getAll($query);
            foreach ($ryad as $rd)
            {
                $query = "SELECT id FROM `sp_size` WHERE `id_ryad` = " . $rd['id']; // ORDER BY id DESC LIMIT $flatCount
                $size = $DB->getAll($query);
                foreach ($size as $it) {
                    $query = "DELETE FROM `sp_order` WHERE `id_order` = " . $it['id'];                                  // удаляем все заказы в этой закупке
                    $DB->execute($query);
                }

                $query = "DELETE FROM `sp_size` WHERE `id_ryad` = " . $rd['id'];                                        //удаляем все размеры из рядов этой закупки
                $DB->execute($query);
            }

            $query = "DELETE FROM `sp_ryad` WHERE `id_zp` = " . $zakup[0]['id'];                                        //удаляем все ряды
            $DB->execute($query);

            $query = "DELETE FROM `sp_zakup` WHERE `id` = " . $zakup[0]['id'];                                          //удаляем закупку
            $DB->execute($query);

            $sql = "DELETE FROM `sp_cat_sub` WHERE `sp_cat_sub`.`zakup` = " . $zakup[0]['id'];                          //удаляем категории
            $DB->execute($sql);

            $query = "DELETE FROM `sp_addpay` WHERE `zp_id` = " . $zakup[0]['id'];                                      //удаляем платежки
            $DB->execute($query);

            @unlink($zakup[0]['foto']);                                                                                 //удаляем файлы
            clear_dir('cache/');
        }
        header('Location: /');
    }
    if (!empty($_GET['value']) and $user->get_property('gid') == 25 and $_GET['section'] == 'delryad') {
        $query = "SELECT `id`, `foto` FROM `sp_zakup` WHERE `id` = " . intval($_GET['value']);
        $zakup = $DB->getAll($query);
        if ($zakup[0]['id'] > 0) {
            $query = "SELECT id FROM `sp_ryad` WHERE `id_zp` = " . $zakup[0]['id']; // ORDER BY id DESC LIMIT $flatCount
            $ryad = $DB->getAll($query);
            foreach ($ryad as $rd) {
                $query = "SELECT id FROM `sp_size` WHERE `id_ryad` = " . $rd['id']; // ORDER BY id DESC LIMIT $flatCount
                $size = $DB->getAll($query);
                foreach ($size as $it) {// удаляем все заказы в этой закупке
                    $query = "DELETE FROM `sp_order` WHERE `id_order` = " . $it['id'];
                    $DB->execute($query);
                }
                //удаляем все размеры из рядов этой закупки
                $query = "DELETE FROM `sp_size` WHERE `id_ryad` = " . $rd['id'];
                $DB->execute($query);
            }
            //удаляем все ряды
            $query = "DELETE FROM `sp_ryad` WHERE `id_zp` = " . $zakup[0]['id'];
            $DB->execute($query);
            clear_dir('cache/');
        }
        header('Location: /com/org/open/' . intval($_GET['value']));
    }
    if ($_GET['section'] == 'delr') {
        // скрипт удаления рядка
        $idpost = intval($_GET['value2']);
        $idduble = intval($_GET['value']);
        $query = "SELECT * FROM `sp_ryad` WHERE `id` = " . $idduble;
        $items_duble = $DB->getAll($query);
        if (empty($idduble) OR count($items_duble) == 0) $message .= 'Вы пытаетесь удалить несуществующий рядок<br/>';
        if ($items_duble[0]['user'] <> $user->get_property('userID') AND $user->get_property('gid') <> 25) $message .= 'У вас недостаточно прав для совершения данного действия.<br/>';
        if (empty($message)) {
            $query = "SELECT * FROM `sp_size` WHERE `id_ryad` = " . $idduble; // ORDER BY id DESC LIMIT $flatCount
            $items = $DB->getAll($query);
            foreach ($items as $item) {
                $query = "DELETE FROM `sp_order` WHERE `id_order` = " . $item['id'];
                $DB->execute($query);
            }
            $query = "DELETE FROM `sp_size` WHERE `sp_size`.`id_ryad` = $idduble";
            $DB->execute($query);
            $query = "DELETE FROM `sp_ryad` WHERE `sp_ryad`.`id` = $idduble LIMIT 1";
            $DB->execute($query);
            header("Location: /com/org/open/" . $idpost);
        }
    }
    if ($_GET['section'] == 'delrz') {
        $idr = intval($_GET['value']);
        if ($user->get_property('gid') < 25) $noadmin = "and `sp_zakup`.`user`=" . $user->get_property('userID');
        $query = "SELECT `sp_order`.*, `punbb_users`.`email`,`sp_zakup`.`title`,`sp_zakup`.`alertnews`
		  FROM `sp_order` 
		  LEFT JOIN `sp_zakup` ON `sp_order`.`id_zp`=`sp_zakup`.`id`
		  JOIN `punbb_users` ON `sp_zakup`.`user`=`punbb_users`.`id`
		  WHERE `sp_order`.`id` = '" . $idr . "' $noadmin";
        $items_duble = $DB->getAll($query);
        if (empty($_GET['value']) OR count($items_duble) == 0) $message .= 'Вы пытаетесь удалить несуществующий заказ<br/>';
        if (empty($message)) {
            $query = "UPDATE sp_size SET `user`='0' WHERE id = " . $items_duble[0]['id_order'];
            $DB->execute($query);
            $query = "DELETE FROM `sp_order` WHERE `sp_order`.`id` = '$idr'";
            $DB->execute($query);
            header("Location: /com/org/vieworder/{$items_duble[0]['id_zp']}");
            exit;
        }
        header("Location: /");
        exit;
    }
    if ($_GET['section'] == 'addpay') {
        $id = intval($_GET['value']);
        if ($user->get_property('gid') < 25) $noadmin = "and `sp_zakup`.`user`='{$user->get_property('userID')}'";
        $query = "SELECT `sp_addpay`.*, `sp_zakup`.`title`
		  FROM `sp_addpay` 
		  LEFT JOIN `sp_zakup` ON `sp_addpay`.`zp_id`=`sp_zakup`.`id`
		  WHERE `sp_addpay`.`id` = '{$id}' $noadmin";
        $zakup = $DB->getAll($query);
        if (count($zakup) > 0) {
            $query = "UPDATE sp_addpay SET `status`='1' WHERE id = '$id'";
            $DB->execute($query);
            $email = $DB->getOne("SELECT `punbb_users`.`email`
			FROM `sp_addpay`
			LEFT JOIN `punbb_users` ON `sp_addpay`.`user`=`punbb_users`.`id`
			WHERE `punbb_users`.`id` = '{$zakup[0]['user']}' ");
            if (email_check($email) && email_check(($emailsup))) {
                $emailsup = $DB->getOne('SELECT `setting`.`value` 
				FROM `setting`
				WHERE `setting`.`name`=\'emailsup\'');
                $m = new Mail; // начинаем
                $m->From("$emailsup"); // от кого отправляется почта
                $m->To($email); // кому адресованно
                $m->text_html = "text/html";
                $m->Subject("Оплата заказа подтверждена - " . $_SERVER['HTTP_HOST']);
                $m->Body("
                    Здравствуйте,<br/>
                    Это письмо отправлено вам сайтом: <a href=\"" . $_SERVER['HTTP_HOST'] . "\">" . $_SERVER['HTTP_HOST'] . "</a><br/>
                    <p>
                    Озганизатор подтвердил(а) оплату заказа \"{$zakup[0]['title']}\".
                    </p><br/>
                    <p>Для просмотра заказа перейдите по ссылке:<br/>
                    <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/basket/\">http://" . $_SERVER['HTTP_HOST'] . "/com/basket/</a></p>
                    ");
                $m->Priority(3);    // приоритет письма
                $m->Send();    // а теперь пошла отправка
            }
            header("Location: /com/org/open/{$zakup[0]['zp_id']}");
            exit;
        }
        header("Location: /");
        exit;
    }
    if ($_GET['section'] == 'delpay') {
        $id = intval($_GET['value']);
        if ($user->get_property('gid') < 25) $noadmin = "and `sp_zakup`.`user`='{$user->get_property('userID')}'";
        $query = "SELECT `sp_addpay`.*, `punbb_users`.`email`,`sp_zakup`.`title`
		  FROM `sp_addpay` 
		  LEFT JOIN `sp_zakup` ON `sp_addpay`.`zp_id`=`sp_zakup`.`id`
		  LEFT JOIN `punbb_users` ON `sp_addpay`.`user`=`punbb_users`.`id`
		  WHERE `sp_addpay`.`id` = '{$id}' $noadmin";
        $zakup = $DB->getAll($query);
        if (count($zakup) > 0) {
            $query = "DELETE FROM sp_addpay WHERE id = '$id' LIMIT 1";
            $DB->execute($query);
            $fromemail = $DB->getOne("SELECT value FROM `setting` WHERE `name`='emailsup'");
            $emailadmin = $DB->getOne("SELECT value FROM `setting` WHERE `name`='email_admin'");
            $m = new Mail; // начинаем
            $m->text_html = 'text/html';
            $m->From($fromemail); // от кого отправляется почта
            $m->To($zakup[0]['email']); // кому адресованно
            $m->Subject('Оплата НЕ подтверждена - ' . $domain);
            $textsms = "<p>Ваше уведомление об оплате к закупке 
                <a href=\"http://$domain/com/org/open/{$zakup[0]['zp_id']}\">{$zakup[0]['title']}</a> 
                НЕ подтверждено. Оплатите заказ и отправте уведомление повторно.</p><p>
                <a href=\"http://$domain\">$domain</a></p>";
            $m->Body($textsms);
            $m->Priority(3);    // приоритет письма
            $m->Send();    // а теперь пошла отправка
            header("Location: /com/org/open/{$zakup[0]['zp_id']}");
            exit;
        }
        header("Location: /");
        exit;
    }
    if ($_GET['section'] == 'double') {
        // скрипт дублирования рядка
        $idpost = intval($_GET['value2']);
        $idduble = intval($_GET['value']);
        $query = "SELECT * FROM `sp_ryad` WHERE `id` = " . $idduble; // ORDER BY id DESC LIMIT $flatCount
        $items_duble = $DB->getAll($query);
        if (empty($idduble) OR count($items_duble) == 0) $message .= 'Вы пытаетесь дублировать несуществующий рядок<br/>';
        if ($items_duble[0]['user'] <> $user->get_property('userID') AND $user->get_property('gid') <> 25) $message .= 'У вас недостаточно прав для совершения данного действия.<br/>';
        if (empty($message)) {
            $new_duble = $items_duble[0]['duble'] + 1;
            $query = "UPDATE sp_ryad SET `duble`=$new_duble WHERE id = " . $items_duble[0]['id'];
            $DB->execute($query);
            $query = "SELECT * FROM `sp_size` WHERE `id_ryad` = " . $items_duble[0]['id'] . " AND duble = '" . $items_duble[0]['duble'] . "'";
            $items_old_s = $DB->getAll($query);
            foreach ($items_old_s as $item_os) {
//                $query = "INSERT INTO sp_size VALUES('','" . $item_os['id_ryad'] . "','" . $item_os['name'] . "', '','" . ($item_os['duble'] + 1) . "','');";
            }
            $query = "INSERT INTO sp_size VALUES('','" . $item_os['id_ryad'] ."','" . $item_os['id_zp'] . "','" . $item_os['name'] . "', 0 ,'" . ($item_os['duble'] + 1) . "','');";
            $DB->execute($query);

            header("Location: /com/org/ryad/$idpost/$idduble");
//            header("Location: /com/org/open/" . $idpost);
        }
    }
    if ($_GET['section'] == 'editr') {
        $id_editr = intval($_GET['value']);
        if ($id_editr == 0) $message = 'Не выбран ряд для редактирования';
        if (empty($message)) {
            $query = "SELECT * FROM `sp_ryad` WHERE `id` = " . $id_editr;
            $items = $DB->getAll($query);
            if (count($items) == 0) $message .= 'Вы пытаетесь редактировать не существующий ряд.';
            if ($items[0]['user'] <> $user->get_property('userID') AND $user->get_property('gid') <> 25) $message .= 'У вас недостаточно прав для совершения данного действия.<br/>';
            if (count($items) == 1)
                if (!empty($items[0]['photo'])) {
                    if (strpos($items[0]['photo'], 'ttp://') || strpos($items[0]['photo'], 'ttps://')) {
                        $img_path2 = $items[0]['photo'];
                    } else {
                        $split = explode('/', $items[0]['photo']);
                        $img_path2 = '/images/' . $split[2] . '/125/100/1/' . $split[3];
                    }
                } else $img_path2 = '';//'/'.$theme.'images/no_photo229x190.png';
        }
    }
    if ($_GET['section'] == 'edito') {
        $id_editr = intval($_GET['value']);
        $idzp = intval($_GET['value2']);
        $kolvo = intval($_POST['kolvo']);
        if ($_POST['action'] == 'edito') {
            $text = PHP_slashes($_POST['message']);
            $query = "UPDATE `sp_order` SET
			`message` = '$text',
			`kolvo` = '$kolvo'
			WHERE `id` ='$id_editr' LIMIT 1 ;";
//echo $query;exit;
            $DB->execute($query);
            header("Location: /com/org/vieworder/" . $idzp);
            exit;

        }
        if ($id_editr == 0) $message = 'Не выбран заказ для редактирования';
        if (empty($message)) {
            $query = "SELECT * FROM `sp_order` WHERE `id` = " . $id_editr;
            $order = $DB->getAll($query);
            $query = "SELECT * FROM `sp_ryad` WHERE `id` = '{$order[0]['id_ryad']}'";
            $items = $DB->getAll($query);
            if (count($items) == 0 || count($order) == 0) $message .= 'Вы пытаетесь редактировать не существующий заказ.';
            if ($items[0]['user'] <> $user->get_property('userID') AND $user->get_property('gid') <> 25) $message .= 'У вас недостаточно прав для совершения данного действия.<br/>';
            if (count($items) == 1)
                if (!empty($items[0]['photo'])) {
                    if (strpos($items[0]['photo'], 'ttp://') || strpos($items[0]['photo'], 'ttps://')) {
                        $img_path2 = $items[0]['photo'];
                    } else {
                        $split = explode('/', $items[0]['photo']);
                        $img_path2 = '/images/' . $split[2] . '/125/100/1/' . $split[3];
                    }
                } else $img_path2 = '';//'/'.$theme.'images/no_photo229x190.png';
        }

    }
    if ($_GET['section'] == 'edpos' and empty($_POST['action'])) {
        $idpost = intval($_GET['value2']);
        $edpos = intval($_GET['value']);
        $query = "SELECT * FROM `sp_ryad` WHERE `id` = $edpos"; // ORDER BY id DESC LIMIT $flatCount
        $items = $DB->getAll($query);
        if ($user->get_property('userID') <> $items[0]['user'] AND $user->get_property('gid') <> 25) $message .= 'У вас недостаточно прав для совершения данного действия.';
        if (empty($message)) {
            $query = "SELECT * FROM `sp_size` WHERE `id_ryad` = " . $edpos . " AND `duble`=1  ORDER BY id ASC";
            $items_size = $DB->getAll($query);
        }
        $query = "SELECT * FROM `sp_ryad` WHERE `id` = " . $edpos . " ";
        $items_v = $DB->getAll($query);
        $vars = explode(',', $items_v[0]->size);
        if (count($vars) == 1) $tp = 'Пример количества: 3';
        if (count($vars) > 1) $tp = 'Пример размеров: 40,41,41,42';
    }
    if ($_GET['section'] == 'edpos' and $_POST['action'] == 'edpos') {
        $idpost = intval($_POST['idpost']);
        $edpos = intval($_POST['edpos']);
        $size = htmlspecialchars(strip_tags($_POST['size']));
        $query = "SELECT * FROM `sp_ryad` WHERE `id` = $edpos";
        $items = $DB->getAll($query);
        if ($user->get_property('userID') <> $items[0]['user'] AND $user->get_property('gid') <> 25) $message .= 'У вас недостаточно прав для совершения данного действия.';
        if (empty($message)) {
            $vars2 = explode(',', $_POST['size']);
//echo count($vars);exit;
            if ((count($vars) == 1 and $items[0]['size']) || count($vars2) == 1) {
                for ($a = 1; $a <= $items[0]['duble']; $a++)
                    for ($i = 0; $i < intval($size); $i++) {
                        $query = "INSERT INTO sp_size VALUES('','$edpos','','','$a','');";
                        $DB->execute($query);
                    }
            }
            if (count($vars) > 1 || count($vars2) > 1) {
                $size = explode(',', $size);
                for ($a = 1; $a <= $items[0]['duble']; $a++)
                    for ($i = 0; $i < count($size); $i++) {
                        $query = "INSERT INTO sp_size VALUES('','$edpos','" . $size[$i] . "','','$a','');";
                        $DB->execute($query);
                    }
            }
            header("Location: /com/org/edpos/$edpos/$idpost");
        }
    }
    if ($_GET['section'] == 'delpos') {
        $delr = intval($_GET['value']);
        $ryad = intval($_GET['value2']);
        $zakup = intval($_GET['value3']);
        if ($delr == 0 OR $ryad == 0 OR $zakup == 0) $message .= 'У вас недостаточно прав для совершения данного действия.';
        $query = "SELECT * FROM `sp_size` WHERE `id` = " . $delr;
        $items_size = $DB->getAll($query);
        $query = "SELECT * FROM `sp_ryad` WHERE `id` = " . $items_size[0]['id_ryad'];
        $items = $DB->getAll($query);
        if ($user->get_property('userID') <> $items[0]['user'] AND $user->get_property('gid') <> 25) $message .= 'У вас недостаточно прав для совершения данного действия.';
        if (count($items) == 0) $message .= 'Ошибка: Выбранной вами позиции не существует';
        if (empty($message)) {
            for ($i = 1; $i <= $items[0]['duble']; $i++) {
                $query = "SELECT * FROM `sp_size` WHERE `id_ryad` = '" . $items[0]['id'] . "' AND `name`='" . $items_size[0]['name'] . "' AND `duble`='$i' LIMIT 1";
                $items_size = $DB->getAll($query);
                $query = "DELETE FROM `sp_size` WHERE `id`='" . $items_size[0]['id'] . "';";
                $DB->execute($query);
                $query = "DELETE FROM `sp_order` WHERE `id_order`='" . $items_size[0]['id'] . "';";
                $DB->execute($query);
            }
            $filelist = array();
            $path = "fmanager/uploads/zakup/{$items_duble[0]['id_zp']}/ryad/{$items_duble[0]['id']}/";
            if ($handle = opendir($path)) {
                while ($entry = readdir($handle)) {
                    if (is_file($path . $entry)) {
                        @unlink($path . $entry);
                    }
                }
                closedir($handle);
            }
            $filelist = array();
            $path2 = "fmanager/uploads/zakup/{$items_duble[0]['id_zp']}/ryad/{$items_duble[0]['id']}/thumbnail/";
            if ($handle = opendir($path2)) {
                while ($entry = readdir($handle)) {
                    if (is_file($path2 . $entry)) {
                        @unlink($path2 . $entry);
                    }
                }
                closedir($handle);
            }
            @rmdir($path2);
            @rmdir($path);
            header("Location: /com/org/edpos/$ryad/$zakup");
        }
    }
    if ($_GET['section'] == 'tender') {
        $query = "SELECT count(id) FROM `sp_add_org` WHERE `user` = '" . $user->get_property('userID') . "' LIMIT 1";
        $testcount = $DB->getOne($query);
    }
    if ($_GET['section'] == 'tender' and intval($_POST['tender']) == 1) {
        $country = intval($_POST['country']);
        $region = intval($_POST['region']);
        $city = intval($_POST['city']);
        $opyt = intval($_POST['opyt']);
        $post = intval($_POST['post']);
        $site = PHP_slashes(htmlspecialchars(strip_tags($_POST['site'])));
        $phone = PHP_slashes(htmlspecialchars(strip_tags($_POST['phone'])));
        $name = PHP_slashes(htmlspecialchars(strip_tags($_POST['name'])));
        $phone = str_replace('+', '', $phone);
        $rules1 = intval($_POST['rules1']);
        $rules2 = intval($_POST['rules2']);
        $query = "SELECT count(id) FROM `sp_add_org` WHERE `user` = '" . $user->get_property('userID') . "' LIMIT 1";
        $testcount = $DB->getOne($query);
        if ($testcount >= 3) $message .= 'Ошибка: Вы уже подали три заявки. Свяжитесь с администрацией.<br/>';
        if ($user->get_property('gid') <> 18) $message = 'Ошибка: У вас недостаточно прав для вступление в группу "Организаторы"<br/>';
        if (!preg_match('/^\d{10}$/', $phone) and !preg_match('/^\d{11}$/', $phone) and !preg_match('/^\d{12}$/', $phone)) $phone = '';
        if (empty($phone)) $message .= 'Ошибка: Вы не заполнили поле "Номер моб. телефона"<br/>';
        if (empty($name)) $message .= 'Ошибка: Вы не заполнили поле "Ф.И.О"<br/>';
        if ($country == 0) $message .= 'Ошибка: Вы не указали страну<br/>';
        if ($region == 0) $message .= 'Ошибка: Вы не указали регион<br/>';
        if ($city == 0) $message .= 'Ошибка: Вы не указали город<br/>';
        if ($rules1 == 0) $message .= 'Ошибка: Вы должны согласиться с правилами<br/>';
        if ($rules2 == 0) $message .= 'Ошибка: Вы должны согласиться с условиями<br/>';
        if (empty($message)) {
            $pass = generate_password(4);
            $query = "SELECT * FROM `punbb_users` WHERE `id` = '" . $user->get_property('userID') . "' LIMIT 1";
            $userdata = $DB->getAll($query);
            $query = "INSERT INTO sp_add_org (`user`,`status`,`country`,`region`,`city`,`opyt`,`post`,`site`,`phone`,`name`,`pass`)
		      VALUES('" . $user->get_property('userID') . "','1','$country','$region','$city','$opyt','$post','$site','$phone','$name','$pass');";
            $DB->execute($query);
            $oke = 1;
            $idtend = $DB->id;
            $text_sms = 'Проверочный код: ' . $pass;
            $fromemail = $DB->getOne("SELECT value FROM `setting` WHERE `name`='emailsup'");
            $emailadmin = $DB->getOne("SELECT value FROM `setting` WHERE `name`='email_admin'");
            $m = new Mail; // начинаем
            $m->text_html = 'text/plain';
            $m->From($fromemail); // от кого отправляется почта
            $m->To('post@websms.ru'); // кому адресованно
            $m->Subject('');
            $textsms = "user={$registry['sms_login']}
pass={$registry['sms_pass']}
fromPhone={$registry['sms_from']}
tels=$phone
mess=$text_sms";
            $m->Body($textsms);
            $m->Priority(3);    // приоритет письма
            $m->Send();    // а теперь пошла отправка
            $m = new Mail; // начинаем
            $m->text_html = 'text/html';
            $m->From($fromemail); // от кого отправляется почта
            $m->To($emailadmin); // кому адресованно
            $m->Subject('Новая заявка в группу Организаторы - ' . $domain);
            $textsms = "
От: $name (<a href=\"http://$domain/com/profile/default/{$userdata[0]['id']}\">{$userdata[0]['username']}</a>)<br/>
Элю. почта: {$userdata[0]['email']}<br/>
Телефон: $phone<br/>
Код: $text_sms";
            $m->Body($textsms);
            $m->Priority(3);    // приоритет письма
            $m->Send();    // а теперь пошла отправка
            foreach ($_FILES as $key => $imgfile):
                $i++;
                $rand = rand(1000, 9999);
                if ($imgfile['size'] > 0):
                    $imgpath = save_image_on_server($imgfile, 'img/uploads/tender/', $setimg1, $idtend . '_tend_' . $i . '_' . $rand);
//print_r($imgpath);exit;
                    if (!empty($imgpath[1])) {
                        //if($key=='imgmaps')$first=2;else $first=0;
                        $path = str_replace('../', '', $imgpath[1]);
                        $query_map = "INSERT INTO sp_add_org_img (`tend`,`path`)
							VALUE ('$idtend','$path')";
                        $DB->execute($query_map);
                    }
                endif;
            endforeach;

        }
    }
    if ($_GET['section'] == 'tender' and intval($_POST['tender']) == 2) {
        $code = PHP_slashes(htmlspecialchars(strip_tags($_POST['code'])));
        $query = "SELECT count(id) FROM `sp_add_org` WHERE `user` = '" . $user->get_property('userID') . "' and `pass`='$code' LIMIT 1";
        $testcount = $DB->getOne($query);
        if ($testcount <> 1) $message .= 'Ошибка: Вы указали не верный код.<br/>';
        if ($user->get_property('gid') <> 18) $message = 'Ошибка: У вас недостаточно прав для вступление в группу "Организаторы"<br/>';
        $oke = 1;
        if (empty($message)) {
            $query = "UPDATE `sp_add_org` SET
			`activate` = '1'
			WHERE `user` ='" . $user->get_property('userID') . "' and `pass`='$code' LIMIT 1 ;";
            $DB->execute($query);
            $query = "SELECT * FROM `punbb_users` WHERE `id` = '" . $user->get_property('userID') . "' LIMIT 1";
            $userdata = $DB->getAll($query);
            $fromemail = $DB->getOne("SELECT value FROM `setting` WHERE `name`='emailsup'");
            $emailadmin = $DB->getOne("SELECT value FROM `setting` WHERE `name`='email_admin'");
            $m = new Mail; // начинаем
            $m->text_html = 'text/html';
            $m->From($fromemail); // от кого отправляется почта
            $m->To($userdata[0]['email']); // кому адресованно
            $m->Subject('Заявка в группу Организаторы принята - ' . $domain);
            $textsms = "<p>Спасибо! Ваша заявка приянта к расмотрению. Мы оповестим вас о нашем решении по Эл. почте или СМС.</p><p><a href=\"http://$domain\">$domain</a></p>";
            $m->Body($textsms);
            $m->Priority(3);    // приоритет письма
            $m->Send();    // а теперь пошла отправка
            $oke = 2;
        }
    }
    if ($_GET['section'] == 'subs' and intval($_GET['value']) != 0 and $user->get_property('userID') != 0) {
        $userId = $user->get_property('userID');
        $zp_id = intval($_GET['value']);
        if ($_GET['value2']) {
            $table = 51;
            $nws = intval($_GET["value2"]);
        } else {
            $table = 5;
            $nws = intval($_GET["value"]);
        }
        $sql = "SELECT count(id) FROM subs WHERE `id_post` = '$nws' and `user` = '$userId' and `table` ='$table'";
        $test = $DB->getOne($sql);
        if ($test > 0) {
            $sql = "DELETE FROM subs WHERE `id_post` = '$nws' and `user` = '$userId' and `table` ='$table'";
            $DB->execute($sql);
        } else {
            $maxIdcomments = $DB->getOne("
		SELECT MAX(`comments`.`id`)
		FROM `comments` 
		WHERE `comments`.`news`= '{$nws}' and `comments`.`table`='{$table}'
		");
            $sql = "INSERT INTO subs (`user`,`table`,`lastcomm`,`id_post`)
		VALUES ('$userId','$table','$maxIdcomments','$nws');";
            $DB->execute($sql);
        }
        if ($_GET['value2'])
            header("Location: /com/org/ryad/{$_GET['value']}/{$_GET['value2']}/");
        else
            header("Location: /com/org/open/{$_GET['value']}/");
        exit;

    }
endif;
if ($_GET['section'] == 'export' and $user->get_property('gid') >= 23 and intval($_GET['value']) > 0) {
    $id = intval($_GET['value']);
    $type = intval($_GET['value2']);
    $userData = [];
	
//    if ($type == 1) $order = " and o.status>=0 and o.status!=7 and o.status!=2 and r.tempOff = 0 ORDER BY r.title ASC";  //поставщику
    if ($type == 1) $order = " and (o.status=8 or o.status=1) and r.tempOff = 0 ORDER BY r.title ASC";  //поставщику, только заказы со статусом "в обработке"
    if ($type == 2) $order = "and (o.status =1 or o.status=9) ORDER BY uo.username ASC"; //o.date сорт по заказам - r.title, сорт по пользователям - uo.username
    if ($type == 3) $order = "and (o.status =1 or o.status=9)";
    if ($type == 4) $order = "ORDER BY uo.username DESC";
    if ($type == 5) $order = "GROUP BY uo.username";    //по пользователям
    if ($type == 6) $order = " and o.status = 8 ORDER BY r.title ASC";    //Экспорт только новых заказов
    if ($type == 7) $order = " and o.status>=0 and o.status!=7 and o.status!=2 ORDER BY r.title ASC";  //поставщику по группам товаров (подсчёт коробками)
	
    $sql = "SELECT o.id, o.user, o.kolvo, o.status as orderStatus, o.id_zp, s.name, s.name as size, r.id as rid, r.message, r.title, r.price, r.articul, r.duble, r.tempOff,
              o.kolvo, z.title as tzak, z.user as userzp, st.name as statname,u.username,  uo.username as unorder, uo.phone
            FROM sp_order o
            JOIN sp_size s ON o.id_order=s.id
            JOIN sp_ryad r ON r.id=s.id_ryad
            JOIN sp_zakup z ON o.id_zp=z.id
            JOIN sp_status st ON z.status=st.id
            JOIN punbb_users u ON z.user=u.id
            JOIN punbb_users uo ON o.user=uo.id
            WHERE z.id='$id' $order";
    $allorder = $DB->getAll($sql);
	
    if ($allorder[0]['userzp'] <> $user->get_property('userID') and $user->get_property('gid') <> 25) exit;

    $sql = "SELECT sum(r.price*o.kolvo*z.curs)
            FROM sp_order o
            JOIN sp_size s ON o.id_order=s.id
            JOIN sp_ryad r ON r.id=s.id_ryad
            JOIN sp_zakup z ON o.id_zp=z.id
            JOIN punbb_users u ON z.user=u.id
            JOIN punbb_users uo ON o.user=uo.id
            WHERE z.id='$id' and o.status>=1 and o.status!=2 and o.status!=7 ";
    $totalprice = $DB->getOne($sql);

    $query = "SELECT sp_addpay.*
			FROM `sp_addpay` 
			WHERE sp_addpay.zp_id = '$id' ORDER BY sp_addpay.id DESC";
    $addpay = $DB->getAll($query);

    $addpayN = [];
    foreach ($addpay as $item) {
        $addpayN[$item['user']] = $item;
    }
    if (count($allorder) > 0):
        if ($allorder[0]['userzp'] <> $user->get_property('userID') and $user->get_property('gid') <> 25) exit;
        // Первоначальные данные для экспорта
        //if($type==1) $out="#ID;Заказчик(ID);Тел;Закупка;Товар;Дополнительно;Кол-во;Цвет;Размер;Артикул;Дата;Цена;Цена+Орг;Оплата;Доставка;Итого\n";
			if ($type == 1) $out = "Заказчик(ID);Товар;Артикул;Кол-во;Коробки;Минималка\n";            // по товарам
			if ($type == 7) $out = "Товар;Артикул;Кол-во;Минималка\n";                         // по группам товаров
			if ($type == 6) $out = "Заказчик(ID);Товар;Артикул;Кол-во;Минималка\n";            // по товарам  только новые
			if ($type == 2) $out = "Товар;Артикул;Кол-во;Закащик;\n";                          // по пользователям
			if ($type == 4) $order = "ORDER BY uo.username DESC";
		
        $kolvo = 0;
        foreach ($allorder as $num):
            if ($addpayN[$num['user']]['status'] > 0) $stO = 'Оплачен'; else $stO = '';
            if (intval($num['dateunix']) == 0):
                $ms = explode(':', $num['date']);
                $md = explode('.', $ms[0]);
                $dt = "{$md[2]}.{$md[1]}.20{$md[0]} {$ms[1]}";
            else: $dt = date('d.m.Y H:i', $num['dateunix']);
            endif;

            $num['color'] = str_replace(';', ',', htmlspecialchars_decode($num['color']));
            $num['title'] = str_replace(';', ',', htmlspecialchars_decode($num['title']));
            $num['tzak'] = str_replace(';', ',', htmlspecialchars_decode($num['tzak']));
            $num['articul'] = str_replace(';', ',', htmlspecialchars_decode($num['articul']));
            $num['message'] = strip_tags(str_replace(array(';', ''), ',', htmlspecialchars_decode($num['message'])));
            $num['color'] = str_replace('\'', ',', htmlspecialchars_decode($num['color']));
            $num['title'] = str_replace('\'', ',', htmlspecialchars_decode($num['title']));
			$num['title'] = preg_replace("/[^A-zА-яёЁ0-9\.\,\_\-\s]+/u", "",$num['title']);          /*Убираем лишние символы из названия. Из-за них не работает экспорт*/
            $num['tzak'] = str_replace('\'', ',', htmlspecialchars_decode($num['tzak']));
            $num['articul'] = str_replace('\'', ',', htmlspecialchars_decode($num['articul']));
            $num['duble'] = str_replace('\'', ',', htmlspecialchars_decode($num['duble']));              /*Вытаскиваем количество рядов, т.е. коробок*/
			$num['size'] = preg_replace("/[^A-zА-яёЁ0-9\.\,\_\-\s]+/u", "",$num['size']);           /*Убираем лишние символы из поля размера. Из-за них не работает экспорт*/
            if ($num['kolvo'] == 0) $num['kolvo'] = 1;
            $num['price'] = $num['price'] * $num['kolvo'] * $num['curs'];
            $num['price2'] = $num['price'] + round($num['price'] / 100 * $num['proc']);
            $num_dost = round(($num['dost'] / 100) * (($num['price'] * $num['curs'] * $num['kolvo']) / ($totalprice / 100)), 1);
            $num['itogo'] = $num_dost + $num['price2'];
            // экспорт по товарам
            /* Первоначальные данные для экспорта...
		if($type==1) $out.="{$num['id']};{$num['unorder']};{$num['phone']};{$num['tzak']}. Орг:{$num['username']};{$num['title']};{$num['message']};{$num['kolvo']};{$num['color']};{$num['size']};{$num['articul']};{$dt};{$num['price']};{$num['price2']};$stO;{$num_dost};{$num['itogo']}\n";
*/
            // экспорт по товарам {$num['size']}
            if ($type == 1) $out .= "{$num['unorder']};{$num['title']};{$num['articul']};{$num['kolvo']};{$num['duble']};{$num['size']}\n";

            // экспорт по пользователям
            if ($type == 2) $out .= "{$num['title']};{$num['articul']};{$num['kolvo']};{$num['unorder']}\n";

            // этикетки
            if ($type == 3) $userData[$num['user']][] = $num;

            //экспорт для раздачи
            if ($type == 4) $out .= "{$num['unorder']};{$num['phone']};{$num['tzak']}. Орг:{$num['username']};\n";
            //
            if ($type == 5) $out .= "{$num['unorder']};{$num['phone']};\n";

            if ($type == 6) $out .= "{$num['unorder']};{$num['title']};{$num['articul']};{$num['kolvo']};{$num['size']}\n";
            // экспорт по товарам {$num['size']}

//            if ($type == 7) $out .= "{$num['title']};{$num['articul']};{$num['kolvo']};{$num['size']}\n";
			
            $kolvo = $kolvo + $num['kolvo'];
        endforeach;
        if ($type == 3) {
                $out = ' <style>
                        .etiket {}
                        .etiket td {border:1px dashed #000;padding:3px;}
                        </style>
            <table class="etiket">
                            <tr>';
                $i = 0;
                foreach ($userData as $k => $v) {
                    $i++;
                    $userID = $k;
                    $count = 0; // кол-во заказов
                    $tovar = '';
            $ordStat = $num['orderStatus'];
            $ordId = $num['id'];
                foreach ($v as $num) {
                    $count++;
                    $tovar .= "<small>{$count}. {$num['title']} -- <b>{$num['kolvo']}</b></small><br/>";
                }
                    $out .=
                        "<td valign=\"top\"><b>{$num['unorder']}
                             </b><br/><br/>
                              {$num['tzak']}. 
                            <br/><b><small>Организатор: {$num['username']}</small></b>
                            <br/><br/>
                            $tovar
                         </td>";
                    if ($i % 3 === 0) $out .= "</tr><tr>";
                }

                $out .= '</tr></table>';
                echo $out;
                exit;
        }


//		$out.=";;\nКол-во товаров:;$kolvo\n";
//		$out.="Кол-во заказов:;".count($allorder)."\n";
///		$out.="Сумма:;$totalprice {$registry['valut_name']}.\n";
//		$totalprice=$totalprice+round($totalprice/100*$allorder[0]['proc']);
//		$out.="Сумма+Орг %:;$totalprice {$registry['valut_name']}.";
//		$out.="Доставка:;{$allorder[0]['dost']} {$registry['valut_name']}.";
//		$out.="Итого:;".($allorder[0]['dost']+$totalprice)." {$registry['valut_name']}.";
        $dt = date('d.m.Y_H:s');
        header("Content-type: application/octet-stream");
        //header("Content-Disposition: attachment; filename=\"sp.$dt.csv\"");  // Название файла
        //header("Content-Disposition: attachment; filename=\"sp_export.csv\"");  // Название файла
        header("Content-Disposition: attachment; filename=\"Export.csv\"");  // Название файла
        $ar = array('À' => 'A', 'à' => 'a', 'Á' => 'A', 'á' => 'a', 'Â' => 'a', 'â' => 'a',
            'Ã' => 'a', 'ã' => 'a', 'Ä' => 'A', 'ä' => 'a', 'Å' => 'A', 'å' => 'a',
            'Æ' => 'a', 'æ' => 'a', 'Ç' => 'C', 'ç' => 'c', 'Ð' => 'E', 'ð' => 'e',
            'È' => 'E', 'è' => 'e', 'É' => 'E', 'é' => 'e', 'Ê' => 'E',
            'ê' => 'e', 'Ë' => 'E', 'ë' => 'e', 'Ì' => 'I', 'ì' => 'i', 'Í' => 'I',
            'í' => 'i', 'Î' => 'I', 'î' => 'i', 'Ï' => 'I', 'ï' => 'i', 'Ñ' => 'N',
            'ñ' => 'n', 'Ò' => 'O', 'ò' => 'o', 'Ó' => 'O', 'ó' => 'o', 'Ô' => 'O',
            'ô' => 'o', 'Õ' => 'O', 'õ' => 'o', 'Ö' => 'O', 'ö' => 'o', 'Ø' => 'O',
            'ø' => 'o', 'Œ' => 'O', 'œ' => 'o', 'ß' => 's', 'Þ' => 'T', 'þ' => 't',
            'Ù' => 'U', 'ù' => 'u', 'Ú' => 'U', 'ú' => 'u', 'Û' => 'U', 'û' => 'u',
            'Ü' => 'U', 'ü' => 'u', 'Ý' => 'Y', 'ý' => 'y', 'Ÿ' => 'Y', 'ÿ' => 'y');
        $out = strtr($out, $ar);
        $out = iconv('UTF-8', 'windows-1251', $out);
        echo $out;
        exit;
    else:
        echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body>
Нет подтвержденных заказов для экспорта. Потвердите заказы.</body></html>';
    endif;
    exit;
}


