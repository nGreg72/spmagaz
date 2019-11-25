<?php
/**
 * PHP Class to user access (login, register, logout, etc)
 * 
 * <code><?php
 * include('access.class.php');
 * $user = new flexibleAccess();
 * ? ></code>
 * 
 * For support issues please refer to the webdigity forums :
 *				http://www.webdigity.com/index.php/board,91.0.html
 * or the official web site:
 *				http://phpUserClass.com/
 * ==============================================================================
 * 
 * @version $Id: access.class.php,v 0.93 2008/05/02 10:54:32 $
 * @copyright Copyright (c) 2007 Nick Papanotas (http://www.webdigity.com)
 * @author Nick Papanotas <nikolas@webdigity.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License (GPL)
 * 
 * ==============================================================================

 */

/**
 * Flexible Access - The main class
 * 
 * @param string $dbName
 * @param string $dbHost 
 * @param string $dbUser
 * @param string $dbPass
 * @param string $dbTable
 */

class flexibleAccess{
  /*Settings*/
  /**
   * The database that we will use
   * var string
   */

  var $dbName = 'database';
  /**
   * The database host
   * var string
   */
  var $dbHost = 'localhost';
  /**
   * The database port
   * var int
   */
  var $dbPort = 3306;
  /**
   * The database user
   * var string
   */
  var $dbUser = 'user';
  /**
   * The database password
   * var string
   */
  var $dbPass = 'password';
  /**
   * The database table that holds all the information
   * var string
   */
  var $dbTable  = 'users';
  /**
   * The session variable ($_SESSION[$sessionVariable]) which will hold the data while the user is logged on
   * var string
   */
  var $sessionVariable = 'userSessionValue';
  /**
   * Those are the fields that our table uses in order to fetch the needed data. The structure is 'fieldType' => 'fieldName'
   * var array
   */
  var $tbFields = array(
  	'userID'=> 'userID', 
  	'login' => 'username',
  	'pass'  => 'password',
  	'email' => 'email',
  	'active'=> 'active'
  );
	/**
   * When user wants the system to remember him/her, how much time to keep the cookie? (seconds)
   * var int
   */
  var $remTime = 2592000;//One month
  /**
   * The name of the cookie which we will use if user wants to be remembered by the system
   * var string
   */
  var $remCookieName = 'ckSavePass';
  /**
   * The cookie domain
   * var string
   */
  var $remCookieDomain = '';
  /**
   * The method used to encrypt the password. It can be sha1, md5 or nothing (no encryption)
   * var string
   */
  var $passMethod = 'sha1';
  /**
   * Display errors? Set this to true if you are going to seek for help, or have troubles with the script
   * var bool
   */
  var $displayErrors = false;
  /*Do not edit after this line*/
  var $userID;
  var $dbConn;
  var $userData=array();
  /**
   * Class Constructure
   * 
   * @param string $dbConn
   * @param array $settings
   * @return void
   */

  function __construct($dbConn = '', $settings = '')
  {
	    if ( is_array($settings) ){
		    foreach ( $settings as $k => $v ){
				    if ( !isset( $this->{$k} ) ) die('Property '.$k.' does not exists. Check your settings.');
				    $this->{$k} = $v;
			}
	    }
	    $this->remCookieDomain = $this->remCookieDomain == '' ? $_SERVER['HTTP_HOST'] : $this->remCookieDomain;
	    $this->dbConn = ($dbConn=='')? mysqli_connect($this->dbHost.':'.$this->dbPort, $this->dbUser, $this->dbPass):$dbConn;
//	    $this->dbConn = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName, $this->dbPort);
	    if ( !$this->dbConn ) die(mysqli_error($this->dbConn));
	    mysqli_select_db($this->dbConn,$this->dbName );
		mysqli_query('SET NAMES utf8');
		mysqli_query('SET CHARACTER SET utf8');
		mysqli_query('SET COLLATION_CONNECTION="utf8_general_ci"');

	    if( !isset( $_SESSION ) ) session_start();
	    if ( !empty($_SESSION[$this->sessionVariable]) )
	    {
		    $this->loadUser( $_SESSION[$this->sessionVariable] );
	    }
	    //Maybe there is a cookie?
	    if ( isset($_COOKIE[$this->remCookieName]) && !$this->is_loaded()){
	      //echo 'I know you<br />';
	      $u = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
	      $this->login($u['uname'], $u['password']);
	    }
  }
  
  /**
  	* Login function
  	* @param string $uname
  	* @param string $password
  	* @param bool $loadUser
  	* @return bool
  */
/*  function login($uname, $password, $remember = false, $loadUser = true)
  {
    	$uname    = $this->escape($uname);
    	$password = $originalPassword = $this->escape($password);
		switch(strtolower($this->passMethod)){
		  case 'sha1':
		  	$password = "SHA1('$password')"; break;
		  case 'md5' :
		  	$password = "MD5('$password')";break;
		  case 'nothing':
		  	$password = "'$password'";
		}
		$res = $this->query("SELECT * FROM `{$this->dbTable}` 
		WHERE `{$this->tbFields['login']}` = '$uname' AND `{$this->tbFields['pass']}` = $password LIMIT 1",__LINE__);
		if ( @mysql_num_rows($res) == 0)
			return false;
		if ( $loadUser )
		{
			$this->userData = mysql_fetch_array($res);
			$this->userID = $this->userData[$this->tbFields['userID']];
			$_SESSION[$this->sessionVariable] = $this->userID;
			if ( $remember ){
			  $cookie = base64_encode(serialize(array('uname'=>$uname,'password'=>$originalPassword)));
			  $a = setcookie($this->remCookieName, 
			  $cookie,time()+$this->remTime, '/', $this->remCookieDomain);
			}
		}
		return true;
  }
*/  
  function login($uname, $password, $remember = false, $loadUser = true)
  {
    	$uname    = $this->escape($uname);
    	$password = $originalPassword = $this->escape($password);
		/*switch(strtolower($this->passMethod)){
		  case 'sha1':
		  	$password = "SHA1('$password')"; break;
		  case 'md5' :
		  	$password = "MD5('$password')";break;
		  case 'nothing':
		  	$password = "'$password'";
		}*/
        $res1 = $this->query("SELECT * FROM `punbb_users` WHERE `username` = '$uname' LIMIT 1",__LINE__);
		if ( mysqli_num_rows($res1) == 0)
			return false;

		$this->userData = mysqli_fetch_array($res1);
		$pasm = $password;
		$password=sha1($this->userData['salt'].sha1($password));

		if(intval($_REQUEST['loginza'])==1) $sql_p = "`password` = '$pasm'"; else $sql_p = "`password` = '$password'";

		$res2 = $this->query("SELECT * FROM `punbb_users` WHERE `username` = '$uname' and $sql_p LIMIT 1",__LINE__);
		if ( mysqli_num_rows($res2) == 0)
			return false;

		if ( $loadUser )
		{

			if($this->userData['group_id']==0)
				{
				$query = "UPDATE `punbb_users` SET `group_id`='3' WHERE `id`='{$this->userData['id']}'";
				$this->query($query);
				}

			$this->userData = mysqli_fetch_array($res2);

			$this->userData['userID']=$this->userData['id'];
			$this->userData['gid']=$this->userData['group_id'];
			$this->userData['city']=$this->userData['city'];
			$this->userData['username']=$this->userData['username'];
			$this->userData['region']=$this->userData['region'];
			if($this->userData['gid']==1)$this->userData['gid']=25;
			if($this->userData['gid']==3)$this->userData['gid']=18;
			if($this->userData['gid']==4)$this->userData['gid']=24;
			if($this->userData['gid']==5)$this->userData['gid']=23;
		// forum login
			header('P3P: CP="CUR ADM"');

			$cookie_name = 'forum_cookie_5110d1';
			$cookie_domain = '';
			$cookie_path = '/';
			$cookie_secure = 0;
			$expire = time() + 2592000;//1209600;
			$user_id=$this->userData['userID'];
			$form_password_hash=$this->userData['password'];
			$salt=$this->userData['salt'];
			$value=base64_encode($user_id.'|'.$form_password_hash.'|'.$expire.'|'.sha1($salt.$form_password_hash.sha1($salt.sha1($expire))));

			if (version_compare(PHP_VERSION, '5.2.0', '>='))
				setcookie($cookie_name, $value, $expire, $cookie_path, $cookie_domain, $cookie_secure, true);
			else
				setcookie($cookie_name, $value, $expire, $cookie_path.'; HttpOnly', $cookie_domain, $cookie_secure);
		
			$this->userID = $this->userData[$this->tbFields['userID']];

			$_SESSION[$this->sessionVariable] = $this->userID;
			if ( $remember ){
			  $cookie = base64_encode(serialize(array('uname'=>$uname,'password'=>$originalPassword)));
			  $a = setcookie($this->remCookieName, 
			  $cookie,time()+$this->remTime, '/', $this->remCookieDomain);
			}
		}
		return true;
  }
  
  /**
  	* Logout function
  	* param string $redirectTo
  	* @return bool
  */
  function logout($redirectTo = '')
  {
    setcookie($this->remCookieName, '', time()-3600, '/', $this->remCookieDomain);

    $cookie_name = 'forum_cookie_5110d1';
	$cookie_domain = '';
	$cookie_path = '/';
	$cookie_secure = 0;
	$expire = -1;//time() - 2592000;//1209600;

	setcookie($cookie_name, '', $expire, $cookie_path, $_SERVER['HTTP_HOST']);
	setcookie($cookie_name, '', $expire, $cookie_path, $cookie_domain);
	setcookie($this->remCookieName, '', $expire, $cookie_path, $_SERVER['HTTP_HOST']);
	setcookie($this->remCookieName, '', $expire, $cookie_path, $cookie_domain);

	if (!version_compare(PHP_VERSION, '5.2.0', '>='))
		setcookie($cookie_name, '', $expire, $cookie_path.'; HttpOnly', $cookie_domain, $cookie_secure);

    $_SESSION[$this->sessionVariable] = '';
    $_SESSION[$this->remCookieName] = '';
    $this->userData = '';
    if ( $redirectTo != '' && !headers_sent()){
	   header('Location: '.$redirectTo );
	   exit;//To ensure security
	}
  }
  /**
  	* Function to determine if a property is true or false
  	* param string $prop
  	* @return bool
  */
  function is($prop){
  	return $this->get_property($prop)==1?true:false;
  }
  
    /**
  	* Get a property of a user. You should give here the name of the field that you seek from the user table
  	* @param string $property
  	* @return string
  */
  function get_property($property)
  {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    if (!isset($this->userData[$property])) $this->error('Unknown property <b>'.$property.'</b>', __LINE__);
    return $this->userData[$property];
  }
  /**
  	* Is the user an active user?
  	* @return bool
  */
  function is_active()
  {
    return $this->userData[$this->tbFields['active']];
  }
  
  /**
   * Is the user loaded?
   * @ return bool
   */
  function is_loaded()
  {
    return empty($this->userID) ? false : true;
  }
  /**
  	* Activates the user account
  	* @return bool
  */
  function activate()
  {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    if ( $this->is_active()) $this->error('Allready active account', __LINE__);
    $res = $this->query("UPDATE `{$this->dbTable}` SET {$this->tbFields['active']} = 1 
	WHERE `{$this->tbFields['userID']}` = '".$this->escape($this->userID)."' LIMIT 1");
    if (@mysqli_affected_rows() == 1)
	{
		$this->userData[$this->tbFields['active']] = true;
		return true;
	}
	return false;
  }
  /*
   * Creates a user account. The array should have the form 'database field' => 'value'
   * @param array $data
   * return int
   */  
  function insertUser($data){
    if (!is_array($data)) $this->error('Data is not an array', __LINE__);
    switch(strtolower($this->passMethod)){
	  case 'sha1':
	  	$password = "SHA1('".$data[$this->tbFields['pass']]."')"; break;
	  case 'md5' :
	  	$password = "MD5('".$data[$this->tbFields['pass']]."')";break;
	  case 'nothing':
	  	$password = $data[$this->tbFields['pass']];
	}
    foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";
    $data[$this->tbFields['pass']] = $password;
    $this->query("INSERT INTO `{$this->dbTable}` (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")");
    return (int)mysqli_insert_id($this->dbConn);
  }
  /*
   * Creates a random password. You can use it to create a password or a hash for user activation
   * param int $length
   * param string $chrs
   * return string
   */

/*  function randomPass($length=10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm'){
    for($i = 0; $i < $length; $i++) {$pwd .= $chrs{mt_rand(0, strlen($chrs)-1)};
    }
    return $pwd;
  }*/
  ////////////////////////////////////////////
  // PRIVATE FUNCTIONS
  ////////////////////////////////////////////
  
  /**
  	* SQL query function
  	* @access private
  	* @param string $sql
  	* @return string
  */
  function query($sql, $line = 'Uknown')
  {
    //if (defined('DEVELOPMENT_MODE') ) echo '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
	$res = mysqli_query($this->dbConn, $sql);
	if ( !res )
		$this->error(mysqli_error($this->dbConn), $line);
	return $res;
  }

  /**
  	* A function that is used to load one user's data
  	* @access private
  	* @param string $userID
  	* @return bool
  */
/*  function loadUser($userID)
  {
	$res = $this->query("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['userID']}` = '".$this->escape($userID)."' LIMIT 1");
    if ( mysql_num_rows($res) == 0 )
    	return false;
    $this->userData = mysql_fetch_array($res);
    $this->userID = $userID;
    $_SESSION[$this->sessionVariable] = $this->userID;
    return true;
  }*/
  function loadUser($userID)
  {
	$res = $this->query("SELECT * FROM `punbb_users` WHERE `id` = '".$this->escape($userID)."' LIMIT 1");
    if ( mysqli_num_rows($res) == 0 )
    	return false;
    $this->userData = mysqli_fetch_array($res);
    $this->userID = $userID;

	$this->userData['userID']=$this->userData['id'];
	$this->userData['gid']=$this->userData['group_id'];
	$this->userData['city']=$this->userData['city'];
	$this->userData['username']=$this->userData['username'];
	$this->userData['region']=$this->userData['region'];
	if($this->userData['gid']==1)$this->userData['gid']=25;
	if($this->userData['gid']==3)$this->userData['gid']=18;
	if($this->userData['gid']==4)$this->userData['gid']=24;
	if($this->userData['gid']==5)$this->userData['gid']=23;

    $this->userData['active']=1;

    $_SESSION[$this->sessionVariable] = $this->userID;
    return true;
  }

  /**
  	* Produces the result of addslashes() with more safety
  	* @access private
  	* @param string $str
  	* @return string
  */  
  function escape($str) {
    $str = get_magic_quotes_gpc()?stripslashes($str):$str;
    $str = mysqli_real_escape_string($this->dbConn, $str);
    return $str;
  }
  
  /**
  	* Error holder for the class
  	* @access private
  	* @param string $error
  	* @param int $line
  	* @param bool $die
  	* @return bool
  */  
  function error($error, $line = '', $die = false) {
    if ( $this->displayErrors )
    	echo '<b>Error: </b>'.$error.'<br /><b>Line: </b>'.($line==''?'Unknown':$line).'<br />';
    if ($die) exit;
    return false;
  }
}
?>