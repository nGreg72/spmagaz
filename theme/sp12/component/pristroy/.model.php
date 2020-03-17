<? defined('_JEXEC') or die('Restricted access');

if ($user->userData['group_id'] == 1 and (!empty($_POST['add']) or !empty($_POST['edit']))) {
    $text = PHP_slashes($_POST['textarea1']);
    $title = PHP_slashes(htmlspecialchars(strip_tags($_POST['title'])));
//    $cat = intval($_POST['cat']);
    $price = PHP_slashes(htmlspecialchars(strip_tags($_POST['price'])));
    $size = PHP_slashes(htmlspecialchars(strip_tags($_POST['size'])));
    $quantity = PHP_slashes(htmlspecialchars(strip_tags($_POST['quantity'])));
    $cause = PHP_slashes(htmlspecialchars(strip_tags($_POST['cause'])));
//    $photo = intval($_FILES['photo']['name']);
    $photo = $_FILES['photo']['name'];
    $buy = $_POST['buy'];
//    if($cat==0) $message_cat='<- Вы укажите ка категорию.';
    if (empty($cause)) $message_cause = '<- Укажите причину.';
    if (empty($title)) $message_title = '<- Укажите название.';
    if (empty($price)) $message_price = '<- Укажите цену.';
    if (empty($message_cat) AND empty($message_price) AND empty($message_title) AND empty($message_cause)) {
        if (!empty($_POST['add']))
            if (empty($quantity)) {
                $quantity = 1;
            }

        if ($_FILES['photo']['size'] > 0) {
//            $fileName = explode('.', $_FILES['photo']['name']);

            $firstName = $_POST['title'];
            $imgpath_r = save_image_on_server($_FILES['photo'], 'img/uploads/pristroy/', $setimg1, $firstName, 'this');
            $photo = $imgpath_r[1];
//            $photo = strval($_POST['title']. '.' .$fileName[1]);

            /*            if (!empty($imgpath_r[1]))
                        {
                            $sql = "UPDATE `sp_pristroy` SET
                                `photo` = '" . $imgpath_r[1] . "'
                                WHERE `sp_ryad`.`id` = " . $lid;
                            $DB->execute($sql);
                        }*/
        }

        $sql = "INSERT INTO `sp_pristroy` (`user`,`title`,`text`,`off`,`date`,`price`,`size`,`quantity`,`cause`, `photo`)
			        VALUE ('" . $user->get_property('userID') . "','$title','$text', 0,'" . time() . "','$price','$size','$quantity','$cause', '$photo')";

        if ($user->get_property('gid') != 25) $us = "and `sp_pristroy`.`user`='" . $user->get_property('userID') . "'"; else $us = '';
        if (!empty($_POST['edit']))
            $sql = "UPDATE `sp_pristroy` SET
                    `title` = '$title',
                    `text` = '$text',
                    `price` = '$price',
                    `size` = '$size',
                    `quantity` = '$quantity',
                    `cause` = '$cause',
                    `photo` = '$photo'
                     WHERE `sp_pristroy`.`id` ='" . intval($_POST['edit']) . "' $us	 LIMIT 1 ;";
        $DB->execute($sql);
    }
    header('location: /com/pristroy/');
}


/*$query = 'SELECT * FROM sp_cat WHERE podcat=0';
$all = getAllcache($query, 6000);
$all = $DB->getAll('SELECT * FROM sp_cat WHERE podcat=0');
$i = 0;
foreach ($all as $num):
    $cat_zp[$num['id']][0] = $num;
    $i++;
endforeach;
$query = 'SELECT * FROM sp_cat WHERE podcat>0';
$all = getAllcache($query, 6000);
$i = 0;
foreach ($all as $num):
    $cat_zp[$num['podcat']][] = $num;
    $i++;
endforeach;*/
if (empty($_GET['section']) or $_GET['section'] == 'default') {
/*    $sort_catz = intval($_GET['value']);
    if ($sort_catz > 0) {
        $query = "SELECT * FROM `sp_cat` WHERE `id`=" . intval($sort_catz);
        $testcats = $DB->getAll($query);
        if ($testcats[0]['podcat'] == 0) {
            $query = "SELECT * FROM `sp_cat` WHERE `podcat`=" . $testcats[0]['id'];
            $allpodcats = $DB->getAll($query);
            $catselect = 'WHERE (';
            $i = 0;
            foreach ($allpodcats as $ac):$i++;
                $catselect .= "`sp_pristroy`.`off`='" . $ac['id'] . "'";
                if ($i < count($allpodcats)) $catselect .= ' OR ';
            endforeach;
            $catselect .= ')';
            $sort_catz_scroll = $testcats[0]['id'];
        } else {
            $catselect = "WHERE `sp_pristroy`.`off`='" . intval($sort_catz) . "'";
            $sort_catz_scroll = $testcats[0]['podcat'];
        }
    }*/

    if ($_GET['value'] == 'my') $catselect = "WHERE `sp_pristroy`.`user`='" . $user->get_property('userID') . "'";
    $page = intval($_GET['value2']);
    // Переменная хранит число сообщений выводимых на станице
    $num = 12;
    // Извлекаем из URL текущую страницу
    if ($page == 0) $page = 1;
    // Определяем общее число сообщений в базе данных
    $posts = $DB->getOne("SELECT count(`sp_pristroy`.`id`) 
				FROM `sp_pristroy` 
				LEFT JOIN `punbb_users` ON `sp_pristroy`.`user`=`punbb_users`.`id`
				
				$catselect");
    // Находим общее число страниц
    $total = intval(($posts - 1) / $num) + 1;
    // Определяем начало сообщений для текущей страницы
    $page = intval($page);
    // Если значение $page меньше единицы или отрицательно
    // переходим на первую страницу
    // А если слишком большое, то переходим на последнюю
    if (empty($page) or $page < 0) $page = 1;
    if ($page > $total) $page = $total;
    // Вычисляем начиная к какого номера
    // следует выводить сообщения
    $start = $page * $num - $num;
    $link_url = '/com/pristroy/default/' . intval($_GET['value']);
    if ($page != 1) $pervpage = '<a href="' . $link_url . '/-1">первая...</a> 
                               <a href="' . $link_url . '/' . ($page - 1) . '">предыдущая...</a> ';
    // Проверяем нужны ли стрелки вперед
    if ($page != $total) $nextpage = '  <a href="' . $link_url . '/' . ($page + 1) . '">следующая...</a>
                                   <a href="' . $link_url . '/' . $total . '">последняя...</a> ';
    // Находим две ближайшие станицы с обоих краев, если они есть
    if ($page - 2 > 0) $page2left = ' <a href="' . $link_url . '/' . ($page - 2) . '">' . ($page - 2) . '</a>  ';
    if ($page - 1 > 0) $page1left = '<a href="' . $link_url . '/' . ($page - 1) . '">' . ($page - 1) . '</a>  ';
    if ($page + 2 <= $total) $page2right = '  <a href="' . $link_url . '/' . ($page + 2) . '">' . ($page + 2) . '</a>';
    if ($page + 1 <= $total) $page1right = '  <a href="' . $link_url . '/' . ($page + 1) . '">' . ($page + 1) . '</a>';
    $sql = "SELECT `sp_pristroy`.*, `punbb_users`.`username`
            FROM `sp_pristroy` 
            LEFT JOIN `punbb_users` ON `sp_pristroy`.`user`=`punbb_users`.`id`
            
            ORDER BY date DESC
            LIMIT $start, $num";
    $hvastics = $DB->getAll($sql);
}
if ($_GET['section'] == 'open' or $_GET['section'] == 'edit') {
    $sql = "SELECT `sp_pristroy`.*, `punbb_users`.`username`
            FROM `sp_pristroy` 
            LEFT JOIN `punbb_users` ON `sp_pristroy`.`user`=`punbb_users`.`id`
            
            WHERE `sp_pristroy`.`id`=" . intval($_GET['value']) ." ";
    $hvastics = $DB->getAll($sql);
}

function send_message($id)
{
    global $DB;
    $sql = "SELECT punbb_users.email 
		FROM comments
		LEFT JOIN punbb_users ON comments.user = punbb_users.id
		WHERE comments.news = '$id' and table='7'";
    $emails = $DB->getAll($sql);
    $emailsup = $DB->getOne('SELECT `setting`.`value` 
			FROM `setting`
			WHERE `setting`.`name`=\'emailsup\'');
    $emails[-1]['email'] = $DB->getOne("SELECT punbb_users.email 
		FROM sp_pristroy
		LEFT JOIN punbb_users ON sp_pristroy.user = punbb_users.id
		WHERE sp_pristroy.id = '$id'");
    foreach ($emails as $email) {
        if (email_check($email['email'])) {
            $sb = "Новый комментарий к пристрою на сайте " . $_SERVER['HTTP_HOST'];
            $bt = "Здравствуйте,<br/>
<p>К пристрою оставлен новый комментарий, возможно вам это интересно.</p>
Для просмотра комментария или ответа перейдите по ссылке:<br/>
<a href=\"http://" . $_SERVER['HTTP_HOST'] . "/com/pristroy/open/" . $id . "/\">http://" . $_SERVER['HTTP_HOST'] . "/com/pristroy/open/" . $id . "/</a><br/>
";
            $m = new Mail; // начинаем
            $m->From("$emailsup"); // от кого отправляется почта
            $m->To($email['email']); // кому адресованно
            $m->text_html = "text/html";
            $m->Subject($sb);
            $m->Body($bt);
            $m->Priority(3);    // приоритет письма
            $m->Send();    // а теперь пошла отправка
        }
    }

}

if (!empty($_POST['add-comm'])):
    session_start();
    if (intval($_POST['login']) == 1):
        if ($user->get_property('userID') > 0):
            $err = 0;
            $mess = PHP_slashes(htmlspecialchars($_POST['message']));
            if (utf8_strlen($mess) == 0):$message = "Введите текст комментария";
                $err = 1;endif;
            if ($err == 0):
                send_message($hvastics[0]['id']);
                $sql = "UPDATE `punbb_users` SET `karma`='0' WHERE `karma` IS NULL AND `id` =" . $user->get_property('userID');
                $DB->execute($sql);

                $sql = "UPDATE `punbb_users` SET `karma`=`karma`+'1' WHERE `punbb_users`.`id`=" . $user->get_property('userID');
                $DB->execute($sql);

                $sql = "INSERT INTO `comments` (`news`,`user`,`message`,`date`,`table`)
				        VALUE ('" . $hvastics[0]['id'] . "','" . $user->get_property('userID') . "','$mess','" . time() . "','7')";
                $DB->execute($sql);

            endif;
        else:
            $message = "Ошибка добавления комментария. Обратитесь за помощью к администратору.";
        endif;
    else:
        if ($_SESSION['captha_text'] == $_POST['capcha']):
            $err = 0;
            $name = PHP_slashes(htmlspecialchars($_POST['name']));
            $email = PHP_slashes(htmlspecialchars($_POST['email']));
            $web = PHP_slashes(htmlspecialchars($_POST['web']));
            $mess = PHP_slashes(htmlspecialchars($_POST['message']));
            if (utf8_strlen($mess) == 0):$message = "Введите текст комментария";
                $err = 1;endif;
            if (email_check($email) and $err == 0):
                send_message($hvastics[0]['id']);
                $sql = "INSERT INTO `comments` (`news`,`user`,`name`,`email`,`web`,`message`,`date`,`table`)
					    VALUE ('" . $hvastics[0]['id'] . "','0','$name','$email','$web','$mess','" . time() . "','7')";
                $DB->execute($sql);
            endif;
        else:
            $message = "Ошибка: Вы ввели неправильный код с картинки";
        endif;
    endif;
    $scroll = '100000000';
endif;
if (!empty($_GET['delcom']) and intval($_GET['delcom']) > 0 and $user->get_property('gid') == 25):
    $sql = "DELETE FROM `comments` WHERE `comments`.`id` = " . intval($_GET['delcom']) . " LIMIT 1";
    $DB->execute($sql);
    header("Location: /com/pristroy/open/" . $hvastics[0]['id']);
endif;
if ($_GET['section'] == 'open') {
    $all_comments = $DB->getAll('
		SELECT `comments`.*, `punbb_users`.`id` as `userID`, `punbb_users`.`username` 
		FROM `comments` LEFT JOIN `punbb_users` ON `comments`.`user`=`punbb_users`.`id`
		WHERE `comments`.`news`= \'' . intval($_GET['value']) . '\' and `comments`.`table`=7');
}
if ($_GET['section'] == 'del') {
    if ($user->get_property('gid') != 25) $us = "and `sp_pristroy`.`user`='" . $user->get_property('userID') . "'"; else $us = '';
    $sql = "DELETE FROM `sp_pristroy` WHERE `sp_pristroy`.`id` = '" . intval($_GET['value']) . "' $us LIMIT 1";
    $test = $DB->execute($sql);
    if (count($test) > 0) {
        $extension = array('.jpg', '.jpeg', '.gif', '.png');
        foreach ($extension as $ext) {
            @unlink('img/uploads/pristroy/' . intval($_GET['value']) . $ext);
        }
    }
    header("Location: /com/pristroy/");
}

if ($_GET['section'] == 'open') {

    $owner              =   $hvastics[0]['user'];
    $idp                =   $hvastics[0]['id'];
    $title              =   $hvastics[0]['title'];

    $price              =   $hvastics[0]['price'];
    $customer_id        =   $user->userData['id'];
    $customer_name      =   $user->userData[2];
    $owner_quantity     =   $hvastics[0]['quantity'];
    $custom_quantity    =   abs($_POST['customer_qnt']);

        $query="SELECT `id`, `customer_id`, `customer_name`, `quantity`, `date`, `paid` FROM `sp_pristroy_order` WHERE `id_pristroy`=" .$_GET['value']. " ORDER BY `sp_pristroy_order`.`id` ASC ";
        $ordered = $DB->getAll($query);

    $summ=0;
        foreach ($ordered AS $order){
         $summ = $summ + $order['quantity'];
        };

$left = $owner_quantity - $summ;                                           // остаток товара. разность между кол-вом у продавца и общим кол-вом заказов
    if ($_POST['action'] == 'customer_buy') {
        if (empty($custom_quantity)) {$custom_quantity = 1;}               // если юзер заказывает допустимое кол-ва товара, вносим заказ в базу
        if ($custom_quantity <= $left) {
            $sql = "INSERT INTO `sp_pristroy_order` (`owner`,`id_pristroy`,`title`,`date`,`price`,`customer_id`,`customer_name`,`quantity`,`paid`)
			        VALUE ('$owner','$idp','$title','" . time() . "','$price','$customer_id','$customer_name','$custom_quantity', 0)";
            $DB->execute($sql);

            header("Location: /com/pristroy/open/". $_GET['value']);
//            $ok=1;
        } else $message = "Столько товара нет в наличии!!!";                     //если юзер заказывает большее кол-во, чем есть у продавца, выдаём ошибку
    }
};

if ($_GET['section'] == 'delOrder') {
    $query = "SELECT `id_pristroy` FROM `sp_pristroy_order` WHERE `sp_pristroy_order`.`id` = ". $_GET['value'];
    $id_pr=$DB->getOne($query);


    $sql = "DELETE FROM `sp_pristroy_order` WHERE `sp_pristroy_order`.`id` = '" . intval($_GET['value']) . "' $us LIMIT 1";
    $delete = $DB->execute($sql);

    header("Location: /com/pristroy/open/".$id_pr);
};



