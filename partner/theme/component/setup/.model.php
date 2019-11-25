<?if ($user->get_property('userID')>0):

	$all = $DB->getAll('SELECT * FROM punbb_users WHERE id='.$user->get_property('userID'));


	if ($_POST['update']==1) {

	$realname=PHP_slashes(htmlspecialchars(strip_tags($_POST['realname'])));
	$phone=PHP_slashes(htmlspecialchars(strip_tags($_POST['phone'])));
	$email=$_POST['email'];
	if(!email_check($email))$message="Ошибка: Указан не верный E-Mail адрес.";

	if ($_POST['del_ava']==1) {
		$filetypes = array('jpg', 'gif', 'png');
		foreach ($filetypes as $cur_type):
			@unlink('../forum/img/avatars/'.$user->get_property('userID').'.'.$cur_type);
		endforeach;
		}

	if ($_POST['pwd']>'' and ($_POST['pwd']==$_POST['pwd2'])) 
		{
		$password=sha1($all[0]['salt'].sha1($_POST['pwd']));
		$SQL_PWD="`password` = '$password',";
		}
	if ($_POST['pwd']>'' and ($_POST['pwd']<>$_POST['pwd2'])) $message="Ошибка: Вероятно вы пытаетесь сменить пароль. Поля \"Пароль\" и \"Повторить пароль\" должны содержать одинаковую информацию. Если же вы не желаете менять пароль, то проследите чтобы при сохранении данных профиля эти поля были пустыми.";

	if ($message=='') {
  	   if ($_FILES["photo"]["size"]>0) 
		{
		if (is_uploaded_file($_FILES['photo']['tmp_name'])) 
			{
			$filename = $_FILES['photo']['tmp_name'];
			$ext = substr($_FILES['photo']['name'], 
				1 + strrpos($_FILES['photo']['name'], "."));
			if (filesize($filename) > $max_image_size) 
				{
				$message="Ошибка: Размер фото не может превышать: $max_image_size Kb";
				} elseif (!in_array($ext, $valid_types)) 
					{
					$message="Ошибка: Данный формат фото не поддерживается. <p>Выберите для загрузки фото в формате: GIF, JPG, PNG</p>";
					} else 
					{
		 			$size = GetImageSize($filename);
		 			if (($size) && ($size[0] < $max_image_width) 
						&& ($size[1] < $max_image_height)) {
						//@unlink($user->get_property('photo'));
						$dir="../forum/img/avatars";
						$newname=rand(100000,99999999);
						//while (file_exists($dir."/$newname".'_ava'.".$ext"))
						//	$newname=rand(100000,99999999);
						if (!is_dir($dir)) {@mkdir($dir, 0777, true);}
						@unlink($dir."/".$user->get_property('userID').".*");
						if (@move_uploaded_file($filename, $dir."/".$user->get_property('userID').".$ext")) {
						if (!is_dir($dir)) {@mkdir($dir, 0755, true);}
						//$path='../'.$dir."/$newname".'_ava'.".$ext";
						//$SQL_PHOTO=" `photo` = '$path', ";
						$message="Фото профиля обновлено.";
						} else {
							$message='Ошибка: Не удалось загрузить фото на сервер. Код: 0197838';
							}
						} else {
							$errN=4;
							$message="Ошибка: Разрешение фото не может превышать: $max_image_width x $max_image_height";
							}
					}
			}/* else {
                         $SQL_PHOTO='';
			}*/
		} 
	$profile=$_POST['profile'];
	foreach ($profile as $key => $val):
		$save_data[$key]=PHP_slashes(htmlspecialchars($val));
	endforeach;
	$profile=serialize($save_data);	
	$sql="UPDATE `punbb_users` SET
		`email` = '$email', 
		`phone` = '$phone', 
		`realname` = '$realname',
		$SQL_PWD
		`profile` = '".$profile."'
	 	WHERE `id` ='".$user->get_property('userID')."' LIMIT 1 ;";
	   $DB->execute($sql);
	   $message.='<br/>Данные профиля успешно обновлены';

	$all = $DB->getAll('SELECT * FROM punbb_users WHERE id='.$user->get_property('userID'));
	   }
	}


	$profile=$DB->getAll("SELECT * FROM profile ORDER BY num ASC");
	$profile_val=$all[0]['profile'];
	$profile_val=unserialize($profile_val);
	foreach ($profile as $val)
		{
		$type=explode('|',$val['type']);
		if ($type[0]=='input') $form[]='<input class="inputbox" type="text" name="profile['.$val['id'].']" value="'.$profile_val[$val['id']].'" />';
		if ($type[0]=='textarea') $form[]='<textarea class="inputbox" name="profile['.$val['id'].']">'.$profile_val[$val['id']].'</textarea>';
		if ($type[0]=='select') 
			{
			$select='<select class="inputbox" name="profile['.$val['id'].']">';
			$i=0;
			foreach ($type as $t)
				{
				if($i==0):$i++;continue;endif;
				$i++;
				if($t==$profile_val[$val['id']]) $sel='selected'; else $sel=''; 
				$select.='<option value="'.$t.'" '.$sel.'>'.$t.'</option>';
				}
			$select.='</select>';
			$form[]=$select;
			}
		$name[]=$val['desc'];
		}

endif;
