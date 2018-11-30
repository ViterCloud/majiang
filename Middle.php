<?php
    session_start();
    require_once 'connect.php';
    require_once 'function.php';

    /* 配置连接参数 */
    $config = array(
        'type' => 'mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '123456',
        'database' => 'majiang',
        'port' => '3306'
    );
    /* 连接数据库 */
    $mysql = new mysql();
    $mysql->connect($config);

    $request = $_REQUEST['action'];

    $allow_action = array('start', 'player_join', 'match_end');

    switch($allow_action){
        case 'start':
            action_start();
            break;
        case 'player_join':
            action_player_join();
            break;
        case 'match_end':
            action_match_end();
            break;
    }

?>
