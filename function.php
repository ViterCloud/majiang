<?php
    require_once 'connect.php';

    function getConnect(){
        $config = array(
            'host' => 'localhost',
            'username' => 'root',
            'password' => '123456',
            'database' => 'majiang'
        );
        return new Mysql($config['host'], $config['username'], $config['password'], $config['database']);
    }

    function action_start(){
        $bottom_point = intval($_REQUEST['point']) <= 0 ? 1 : intval($_REQUEST['point']);
        $player = array();
        for ($i=0; $i < 4; $i++) {
            $p = randomNum();
            while (in_array($p, $player)) {
                $p = randomNum();
            }
            array_push($player, $p);
        }
        $player = array('east' => $player[0], 'south' => $player[1], 'weat' => $player[2], 'north' => $player[3], 'point' => $bottom_point);
        $data = array_merge($player, array('created_at' => time()));
        $mysql = getConnect();
        $table = $mysql -> insert('table', $data);
        if($table > 0){
            jsonBack(array_merge($player, array('code' => 1, 'table' => $table, 'point' => $bottom_point)));
        }
        jsonBack(array('code' => -1, 'msg' => 'error!'));
    }

    function action_player_join(){
        $table = intval($_REQUEST['table']);
        $player = intval($_REQUEST['player']);
        unset($_SESSION['player']);
        $mysql = getConnect();
        $data = $mysql -> getOne("SELECT * FROM table WHERE id = {$table}");
        $info = array('table' => $table, 'point' => $data['point'], 'number' => $player);
        if($data){
            if(intval($data['east']) == $player){ array_merge($info, array('seat' => 'east', 'seat_cn' => '东')); }
            if(intval($data['south']) == $player){ array_merge($info, array('seat' => 'south', 'seat_cn' => '南')); }
            if(intval($data['weat']) == $player){ array_merge($info, array('seat' => 'weat', 'seat_cn' => '西')); }
            if(intval($data['north']) == $player){ array_merge($info, array('seat' => 'north', 'seat_cn' => '北')); }
            $_SESSION['player'] = $info;
            jsonBack(array_merge($info, array('code' => 1)));
        }
        jsonBack(array('code' => -1, 'msg' => 'error!'));
    }

    function action_match_end(){
        $table = intval($_REQUEST['table']);
        $player = intval($_REQUEST['player']);
        $multiple = isset($_REQUEST['multiple']) ? intval($_REQUEST['multiple']) : 0 ;
        $result = intval($_REQUEST['result']);              //array('无','赢','输')
        $result_type = intval($_REQUEST('result_type'));    //array('无','自摸','点炮')
        $win_type = isset($_REQUEST['win_type']) ? $_REQUEST['win_type'] : null;          // 0 | 1~
        $info = $_SESSION['player'];
        if($table == intval($info['table']) && $player == intval($info['number'])){
            $mysql = getConnect();
            $record = $mysql -> getOne("SELECT `id`,`lock` FROM match check = 0 and lock = 0 Order By created_at DESC");
            if($record){
                $mysql -> update('match', array('lock' => 1, 'updated_at' => time()), 'id = '.$record['id']);
                $data = array('table' => $table, $info['seat'] => $multiple, 'updated_at' => time());
                if($result == 1){
                    $data = array_merge($data, array('winner' => $info['seat']));
                }else if($result == 2){
                    $data = array_merge($data, array('loser' => $info['seat']));
                }
                if($win_type != null){
                    $wt = getWinType($win_type);
                    $data = array_merge($data, array('win_type' => $wt['name'], 'multiple' => $wt['multiple']));
                }
                if($result_type == 1){
                    $data = array_merge($data, array('lose_type' => '自摸'));
                }else if($result_type == 2){
                    $data = array_merge($data, array('lose_type' => '点炮'));
                }
                if($mysql -> update('match', $data, 'id = '.$record['id'])){
                    if(settlement($mysql -> getOne("SELECT * FROM match WHERE id = {$record['id']} Order By created_at DESC");)){
                        if($mysql -> update('match', array('lock' => 0, 'updated_at' => time()), 'id = '.$record['id'])){
                            jsonBack(array('code' => 1, 'msg' => 'success'));
                        }
                        jsonBack(array('code' => -1, 'msg' => 'error!'));
                    }
                    jsonBack(array('code' => -1, 'msg' => 'settlement error!'));
                }
                jsonBack(array('code' => -1, 'msg' => 'update player data error!'));
            }
            jsonBack(array('code' => -1, 'msg' => 'no record!'));
        }
        jsonBack(array('code' => -1, 'msg' => 'error account!'));
    }

    function getWinType($type){

    }

    function settlement($data){
        if($data['east'] != '' && $data['south'] != '' && $data['weat'] != '' && $data['north'] != ''){
            $mysql = getConnect();
            $table_data = $mysql -> getOne("SELECT * FROM table WHERE id = {$data['table']}");
            $sum_data = array('east' => 0, 'south' => 0, 'weat' => 0, 'north' => 0);
            if(intval($data['east']) > 0){
                $soure = intval($data['east']) * intval($table_data['point']);
                $sum_data['east'] += $soure;
                $sum_data['south'] -= $soure;
                $sum_data['weat'] -= $soure;
                $sum_data['north'] -= $soure;
            }
            if(intval($data['south']) > 0){
                $soure = intval($data['south']) * intval($table_data['point']);
                $sum_data['east'] -= $soure;
                $sum_data['south'] += $soure;
                $sum_data['weat'] -= $soure;
                $sum_data['north'] -= $soure;
            }
            if(intval($data['weat']) > 0){
                $soure = intval($data['weat']) * intval($table_data['point']);
                $sum_data['east'] -= $soure;
                $sum_data['south'] -= $soure;
                $sum_data['weat'] += $soure;
                $sum_data['north'] -= $soure;
            }
            if(intval($data['north']) > 0){
                $soure = intval($data['north']) * intval($table_data['point']);
                $sum_data['east'] -= $soure;
                $sum_data['south'] -= $soure;
                $sum_data['weat'] -= $soure;
                $sum_data['north'] += $soure;
            }
        }
        return true;
    }

    function jsonBack($data){
        die(json_encode($data));
    }

    function randomNum(){
        return rand(1000, 9999);
    }

?>
