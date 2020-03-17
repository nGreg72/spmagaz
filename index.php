<?php
/**
|==========================================================|
|========= @copyright Chernyshov Roman, 2011-2014 ========|
|================= @website: www.spykt.ru =================|
|========= @license: GNU GPL V3, file: license.txt ========|
|==========================================================|
 */


# Monitoring users IP
#$fp = fopen('ip.txt', 'a+');
#fwrite($fp, $_SERVER['REMOTE_ADDR'].' - '.date("d.m.Y H.i.s",time())." ".$_SERVER['PHP_SELF']."\n");


if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

if (version_compare(PHP_VERSION, '7.0.0','>=')) include 'mysql.php';
require_once('config.php');

if($antiddos):
  require_once('lib/antiddos.class.php');
  $ad = new antiDdos(false);
  $ad->dir = 'tmp/';
  $ad->ddos = 2;
  $ad->start();
endif;

if ($timer_generate) {require_once('lib/timer.class.php');$timer = new timer();$timer->start_timer();}
require_once('sys/functions.php');
if (count($_GET)>0 OR count($_POST)>0) require_once('sys/get.control.php');
require_once('lib/access.class.php');
require_once('lib/mail.class.php');
require_once('lib/dbsql.class.php');
require_once('lib/class.get.image.php');
//require_once('lib/cache.class.php');
require_once('lib/markhtml.php');

if ($component=='rss') require_once 'lib/rss.class.php';


$user=new flexibleAccess('',$settings);
$DB=new DB_Engine('mysql', $settings['dbHost'], $settings['dbUser'], $settings['dbPass'], $settings['dbName']);
$DB->show_err=FALSE;

require_once('lib/votepost.php');
online_check();getLicense();

if ($_POST['export']==1):$export=1;include('export.php');endif;

if ( $_GET['logout'] == 1 ) $user->logout('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
if ( !$user->is_loaded())
	{if ( isset($_REQUEST['uname']) && isset($_REQUEST['pwd'])){
	  if ( !$user->login($_REQUEST['uname'],$_REQUEST['pwd'],$_REQUEST['remember'] )){
	    $err=2;
		 header('Location: /com/login/');
	  }else header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);}
	} else 
	if (!$user->is_active() AND $_GET['code']=='') $err=1; 

@include($theme.'.model.php');

if($component=='')$com_path='frontpage';else $com_path=$component;
if($section=='')$sec_path='default';else $sec_path=$section;
$contents_view=$theme.'component/'.$com_path.'/'.$sec_path.'.php';
if(!file_exists($contents_view)) {$contents_view=$theme.'component/error/default.php';$exists=FALSE;} else $exists=TRUE;
if(!$exists)$model='frontpage';else$model=$com_path;

$model_path=$theme.'component/'.$model.'/.model.php';;
if(file_exists($model_path))include($model_path);

$page_title=$com_path;
if ($other_internal and !empty($component) and $exists and !$PrivateAccess) 
	require_once $theme.'internal.php'; 
	elseif($PrivateAccess and !$user->is_loaded()) require_once $theme.'login.php';
	else require_once $theme.'index.php';

if ($timer_generate) {
	echo 'queries: '.count($DB->sqls).'<br/>';
	echo 'time queries: '.$DB->AllTimeQueries.'<br/>';
	echo 'time generate: '.round($firstTime = $timer->end_timer(),5).'s';
	if($_GET['debug']==1)
		{
		require_once('lib/dbug.class.php');
		new dbug($DB->sqls);
		}
	}
