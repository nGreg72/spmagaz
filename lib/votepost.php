<?php

if($_POST['class']=='votepost') {
	if($_POST['event']=='ball') {
	$ball=intval($_POST['ball']);

	$ex=explode('|',$_POST['url']);

	$id=intval($ex[0]);
	$test=intval($ex[1]);

	$sql="SELECT karma, voteusers FROM punbb_users WHERE id='$id' LIMIT 1";
	$vote=$DB->getAll($sql);
//	echo $sql;

	if($vote[0]['voteusers']>'')$arrUsers=unserialize($vote[0]['voteusers']); else $arrUsers=array();
	$date = date('dmY');
//print_r($arrUsers);
	$fail=0;
	foreach($arrUsers as $item) {
		if($item['user']==$user->get_property('userID') and $item['date']==$date)$fail=1;
	}
	if($user->get_property('userID')==$id)$fail=1;

	if($fail==0)
		{
		$newarr=array();$sc=0;
		foreach($arrUsers as $item) {
			if($item['user']==$user->get_property('userID')) {
                               $item['date']=$date;$sc=1;
			}
			$newarr[]=$item;
		}

		if($sc==0)$newarr[]=array('user' => $user->get_property('userID'), 'date' => $date);

		$ball=$vote[0]['karma']+$ball;

                $newarr=serialize($newarr);
//echo '='.count($vote);exit;
	        $sql="UPDATE punbb_users SET `karma`='$ball', `voteusers`='$newarr' WHERE id='{$id}'";
		$DB->execute($sql);

		} else $ball=$vote[0]['karma'];
	if($ball>0)$ball="+$ball";
	echo $ball;
	}
exit;
}
