<?php
// include_once '../../conf/const.php';
// // include_once './conf/const.php';
const HOST  = '';
const USERNAME = '';
const PASSWORD = '';
const DBNAME = '';

function get_server(){
    return $_SERVER['REQUEST_METHOD'];
}
//ユーザ名チェック(６文字以上）
function user_check($tmp){
    $tmp = mb_convert_kana(strval($tmp), 's');
    $tmp = preg_replace('/ |　/','',$tmp);
    if(ctype_space($tmp) === FALSE){
        if(mb_strlen($tmp) >= 6){
            $tmp = htmlspecialchars($tmp, ENT_QUOTES,'UTF-8');
            return $tmp;
        }

    } else {
        return null;
    }
}
//パスワードチェック（６文字以上１８文字以内の英数字アルファベット大文字小文字含める）
function pass_check($tmp){
    if(preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z\-]{6,18}$/', $tmp) === 1){
        $tmp = htmlspecialchars($tmp, ENT_QUOTES,'UTF-8');
        return $tmp;
    } else {
        return null;
    }
}
//数字チェック整数のみ
function number_check($tmp){
    $tmp = mb_convert_kana($tmp, 'n');
    if(preg_match('/[0-9]+/', $tmp) === 1){
        $tmp = htmlspecialchars($tmp, ENT_QUOTES,'UTF-8');
        return $tmp;
    } else {
        return null;
    }
}
//２文字以上かまた空白意外
function word_count_check($tmp){
    $tmp = mb_convert_kana(strval($tmp), 's');
    $tmp = preg_replace('/ |　/','',$tmp);
    //     var_dump($tmp);
    if(ctype_space($tmp) === FALSE){
        if(mb_strlen($tmp) >= 2){
            $tmp = htmlspecialchars($tmp, ENT_QUOTES,'UTF-8');
            //         var_dump($str);
            return $tmp;
        }

    } else {
        return null;
    }
}
//データベースへアクセス
function db_connect(){
   $link =  mysqli_connect(HOST, USERNAME, PASSWORD, DBNAME);
   if($link){
       mysqli_set_charset($link,'utf8');
       return $link;
   } else {
       return FALSE;
   }
}
//データベースへ検索
function db_select($link, $sql){
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) !== 0){
            while($row = mysqli_fetch_array($result)){
                $select_info[] = $row;
                //             var_dump($row);
            }
            mysqli_free_result($result);
            return $select_info;
        } else {
            return FALSE;
        }

    }
}
//データベースへデータ追加
function db_insert($link, $sql){
    if(!mysqli_query($link, $sql)){
        return FALSE;
    } else {
        return TRUE;
    }
}
//データベース更新
function db_update($link,$sql){
    if(!mysqli_query($link,$sql)){
        return FALSE;
    } else {
        return TRUE;
    }
}
//データベース削除
function db_delete($link,$sql){
    if(!mysqli_query($link,$sql)){
        return FALSE;
    } else {
        return TRUE;
    }
}
