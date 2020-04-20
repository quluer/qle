<?php
defined('IN_IA') or exit('Access Denied');

$config = array();

$config['db']['master']['host'] = '127.0.0.1';
$config['db']['master']['username'] = 'root';
$config['db']['master']['password'] = 'root';
$config['db']['master']['port'] = '8889';
$config['db']['master']['database'] = 'qle';
$config['db']['master']['charset'] = 'utf8';
$config['db']['master']['pconnect'] = 0;
$config['db']['master']['tablepre'] = 'ims_';



//$config['db']['master']['host'] = '47.114.101.220';
//$config['db']['master']['username'] = 'quluer_top';
//$config['db']['master']['password'] = '5e5HeZ5eWJJPMbBi';
//$config['db']['master']['port'] = '888';
//$config['db']['master']['database'] = 'quluer_top';
//$config['db']['master']['charset'] = 'utf8';
//$config['db']['master']['pconnect'] = 0;
//$config['db']['master']['tablepre'] = 'ims_';


$config['db']['slave_status'] = false;
$config['db']['slave']['1']['host'] = '';
$config['db']['slave']['1']['username'] = '';
$config['db']['slave']['1']['password'] = '';
$config['db']['slave']['1']['port'] = '3307';
$config['db']['slave']['1']['database'] = '';
$config['db']['slave']['1']['charset'] = 'utf8';
$config['db']['slave']['1']['pconnect'] = 0;
$config['db']['slave']['1']['tablepre'] = 'ims_';
$config['db']['slave']['1']['weight'] = 0;

$config['db']['common']['slave_except_table'] = array('core_sessions');

// --------------------------  CONFIG COOKIE  --------------------------- //
$config['cookie']['pre'] = '9bea_';
$config['cookie']['domain'] = '';
$config['cookie']['path'] = '/';

// --------------------------  CONFIG SETTING  --------------------------- //
$config['setting']['charset'] = 'utf-8';
$config['setting']['cache'] = 'redis';
$config['setting']['timezone'] = 'Asia/Shanghai';
$config['setting']['memory_limit'] = '256M';
$config['setting']['filemode'] = 0644;
$config['setting']['authkey'] = 'e93f12ac';
$config['setting']['founder'] = '1';
$config['setting']['development'] = 0;
$config['setting']['referrer'] = 0;

// --------------------------  CONFIG REDIS  --------------------------- //
$config['setting']['redis']['server'] = '127.0.0.1';//本地服务器，如果是远程服务器就用服务器的ip
$config['setting']['redis']['port'] = 6379;//微擎官方是6379，但是人人商城官方为了安全的建议是63790，记得这个端口要在防火墙打开
$config['setting']['redis']['pconnect'] = 0;
$config['setting']['redis']['timeout'] = 1;
$config['setting']['redis']['requirepass'] = '';

// --------------------------  CONFIG UPLOAD  --------------------------- //
$config['upload']['image']['extentions'] = array('gif', 'jpg', 'jpeg', 'png');
$config['upload']['image']['limit'] = 5000;
$config['upload']['attachdir'] = 'attachment';
$config['upload']['audio']['extentions'] = array('mp3');
$config['upload']['audio']['limit'] = 5000;

// --------------------------  CONFIG MEMCACHE  --------------------------- //
$config['setting']['memcache']['server'] = '';
$config['setting']['memcache']['port'] = 11211;
$config['setting']['memcache']['pconnect'] = 1;
$config['setting']['memcache']['timeout'] = 30;
$config['setting']['memcache']['session'] = 1;

// --------------------------  CONFIG PROXY  --------------------------- //
$config['setting']['proxy']['host'] = '';
$config['setting']['proxy']['auth'] = '';