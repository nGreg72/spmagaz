<?php
class antiDdos
{
    // �����
    public $debug = false;
    // ���������� ��� �������� ������ ����������� ��������
    public $dir = 'tmp/';
    // ����� icq ��������������
    public $icq = '1308715';
    // ��������� ��� ����������� �����
    public $off_message = '��������� ���������, ����������, ���������.';
    // �������������� �����������
    private $indeficator = null;
    // ��������� ��� ����, �������� �������, ����� ������������ - {ICQ}, {IP}, {UA}, {DATE}
    public $ban_message = '�� ���� ������������� antiddos ��������.
                           ���� ��� ������ ���������� � ��������������, icq of admin: {ICQ}
                           <hr>(c) rcheCMS, ��� IP - {IP}(<i>{UA}</i>), date - {DATE}';
    // ������� ���������� ���� � ���������
    public $exec_ban = 'iptables -A INPUT -s {IP} -j DROP';
    // ��� ������ �� �����:
    /* ��������� �������� $ddos 1-5: 
    | 1. ������� �������� �� �����, �� ���������(����������)   
    | 2. ������� �������� ����� $_GET antiddos � meta refresh   
    | 3. ������ �� ����������� WWW-Authenticate   
    | 4. ������ ���������� �����, ���� �� �����������!!!   
    | 5. ��������� ���� ���� �������� ������� ������� �� �������, ���� �� �����������!!!   
    */ 
    var $ddos = 1;
    // ����� ������ ��������� �����, �� strpos()
    private $searchbots = array('googlebot.com', 'yandex.ru', 'ramtel.ru', 'rambler.ru', 'aport.ru', 'sape.ru', 'msn.com', 'yahoo.net');
    // ��������� ���������� ������ ��� ������ �������
    private $attack = false;
    private $is_bot = false;
    private $ddosuser;
    private $ddospass;
    private $load;
    public $maxload = 80;
    
    function __construct($debug)
    {
        @session_start() or die('session_start() filed!');
        $this->indeficator = md5(sha1('botik' . strrev(getenv('HTTP_USER_AGENT'))));
        $this->ban_message = str_replace(['{ICQ}', '{IP}', '{UA}', '{DATE}'],
                                         [$this->icq, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], date('d.m.y H:i')],
                                         $this->ban_message
                                        );
        if (preg(ip2long($_SERVER['REMOTE_ADDR']), file_get_contents($this->dir . 'banned_ips')))
            die($this->ban_message);
        $this->exec_ban = str_replace('{IP}', $_SERVER['REMOTE_ADDR'], $this->exec_ban);
        $this->debug = $debug;
        if(!function_exists('sys_getloadavg'))
        {
            function sys_getloadavg()
            {
                return [0,0,0];
            }
        }
        $this->load = sys_getloadavg();
        if(!$this->sbots())
        {
            $this->attack = true;
            $f = fopen($this->dir . ip2long($_SERVER["REMOTE_ADDR"]), "a"); 
            fwrite($f, "query\n"); 
            fclose($f); 
        }
    }
    
    /**
    * ����� ������ ���������
    **/
    function start()
    {
        if($this->attack == false)
            return;
        switch($this->ddos)
        {
            case 1:
                $this->addos1();
                break;
            case 2:
                $this->addos2();
                break;
            case 3:
                $this->ddosuser = substr(ip2long($_SERVER['REMOTE_ADDR']), 0, 4);
                $this->ddospass = substr(ip2long($_SERVER['REMOTE_ADDR']), 4, strlen(ip2long($_SERVER['REMOTE_ADDR'])));
                $this->addos3();
                break;
            case 4:
                die($this->off_message);
                break;
            case 5:
                if ($this->load[0] > $this->maxload) 
                {
                    header('HTTP/1.1 503 Too busy, try again later'); 
                    die('<h1>503 Server too busy.</h1><hr><small><i>Server too busy. Please try again later. Apache server on ' . $_SERVER['HTTP_HOST'] . ' at port 80 with <a href="http://forum.xaknet.ru/">ddos protect</a></i></small>');
                } 
                break;
            default:
                break;
        }
        if ($_COOKIE['ddos'] == $this->indeficator) 
            @unlink($this->dir . ip2long($_SERVER["REMOTE_ADDR"])); 
    }
    
    /**
    * ������� ��������� �� �������� �� ������ ��������� �����
    **/
    function sbots()
    {
        $tmp = [];
        foreach($this->searchbots as $bot)
        {
            $tmp[] = strpos(gethostbyaddr($_SERVER['REMOTE_ADDR']), $bot) !== false;
            if($tmp[count($tmp) - 1] == true)
            {
                $this->is_bot = true;
                break;
            }
        }
        return $this->is_bot;
    }
    
    /**
    * ������� ����
    **/
    private function ban()
    {
        if (! system($this->exec_ban))
        { 
            $f = fopen($this->dir . 'banned_ips', "a"); 
            fwrite($f, ip2long($_SERVER['REMOTE_ADDR']) . '|'); 
            fclose($f); 
        }
        die($this->ban_message); 
    }
    /**
    * ������ ��� ������
    **/
    function addos1()
    {
        if (empty($_COOKIE['ddos']) or !isset($_COOKIE['ddos'])) 
        { 
            $counter = @file($this->dir . ip2long($_SERVER["REMOTE_ADDR"]));
            setcookie('ddos', $this->indeficator, time() + 3600 * 24 * 7 * 356); // ������ ���� �� ���.
            if (count($counter) > 10) { 
                if (! $this->debug)
                    $this->ban(); 
                else 
                    die("�����������."); 
            } 
            if (! $_COOKIE['ddos_log'] == '1') 
            { 
                if (! $_GET['antiddos'] == 1) 
                { 
                    setcookie('ddos_log', '1', time() + 3600 * 24 * 7 * 356); //���� �� ������������ ��������� ��������. 
                    if(headers_sent())
                        die('Header already sended, check it, line '.__LINE__);
                    header("Location: /?antiddos=1"); 
                } 
            } 
        } elseif ($_COOKIE['ddos'] !== $this->indeficator) 
        { 
            if (! $this->debug) 
                $this->ban(); 
            else 
                die("�����������."); 
        } 
    }
    
    /**
    * ������ ��� ������
    **/
    function addos2()
    {
        if (empty($_COOKIE['ddos']) or $_COOKIE['ddos'] !== $this->indeficator) 
        {
            if (empty($_GET['antiddos'])) 
            { 
                if (! $_COOKIE['ddos_log'] == '1') 
                    //�������� ���� �� ������ � ����� ��� ��� ������ 
                    die('<meta http-equiv="refresh" content="0;URL=?antiddos=' . $this->indeficator . '" />'); 
            } elseif ($_GET['antiddos'] == $this->indeficator) 
            { 
                setcookie('ddos', $this->indeficator, time() + 3600 * 24 * 7 * 356); 
                setcookie('ddos_log', '1', time() + 3600 * 24 * 7 * 356); //���� ������ ��� ��� ���� �� ������������ ��������� ��������. 
            } 
            else 
            { 
                if (!$this->debug) 
                    $this->ban(); 
                else 
                { 
                    echo "May be shall not transform address line?"; 
                    die("�����������."); 
                } 
            } 
        } 
    }
    
    /**
    * ������ ��� ������
    **/
    function addos3()
    {
        if (! isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $this->ddosuser || $_SERVER['PHP_AUTH_PW'] !== $this->ddospass) 
        { 
            header('WWW-Authenticate: Basic realm="Vvedite parol\':  ' . $this->ddospass . ' | Login: ' . $this->ddosuser . '"'); 
            header('HTTP/1.0 401 Unauthorized'); 
            if (! $this->debug) 
                $this->ban(); 
            else  
                die("�����������."); 
            die("<h1>401 Unauthorized</h1>"); 
        }
    }
}
?> 