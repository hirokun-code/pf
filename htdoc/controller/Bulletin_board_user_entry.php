<?php

include_once '../../include/model/Bulletin_board_function.php';

$user_name = null;
$user_passwd = null;
$err_msg = [];
$msg = [];
//ログイン済の場合はカテゴリへ
session_start();
if(isset($_SESSION['user_name']) === TRUE){
    header('Location:Bulletin_board_category.php');
    exit;
}

//postされたユーザ名・パスワード名のチェック
if(get_server() === 'POST'){
    if(isset($_POST['user_name']) === TRUE){
        $user_name = $_POST['user_name'];
    }
    if(isset($_POST['user_passwd']) === TRUE){
        $user_passwd = $_POST['user_passwd'];
    }
    //ユーザ名6文字以上空白を除く、エスケープ
    $user_name = user_check($user_name);
    //パスワード６文字以上１８文字以内の英数字アルファベット大文字小文字含める、エスケープ
    $user_passwd = pass_check($user_passwd);
//     var_dump($user_name);
//        var_dump($user_passwd);
    if($user_name === null){
        $err_msg[] = 'ユーザ名を６文字以上で入力してください';
    }
    if($user_passwd === null){
        $err_msg[] = 'パスワードを６文字以上１８文字以内の英数字アルファベット大文字小文字含めて入力してください(空白を含めない）';
    }
    //パスワードハッシュ化
    $user_passwd = password_hash($user_passwd, PASSWORD_DEFAULT);
//     var_dump($user_name);
//     var_dump($user_passwd);
    //エラーがなければユーザ登録
    if(count($err_msg) === 0){
        if($link = db_connect()){
            //同名ユーザの確認なければDBへ登録
            $user_info_serch = "SELECT user_name, user_passwd FROM user_info
            WHERE user_name = '$user_name'";
//             var_dump($user_info_serch);
            if(!$user_info = db_select($link, $user_info_serch)){
                $user_entry = "INSERT INTO user_info(user_name, user_passwd)
                               VALUES('$user_name', '$user_passwd')";

//                 var_dump($user_entry);
                if(db_insert($link, $user_entry)){
                    $_SESSION['user_id'] = mysqli_insert_id($link);
                    $_SESSION['user_name'] = $user_name;
                    header('Location:Bulletin_board_thread_creation.php');
                    exit;
                } else {
                    $err_msg[] = '登録失敗';
                }
            } else {
                $err_msg[] = '同名のユーザ名があります違うユーザ名に変更してください';
            }
            mysqli_close($link);
        } else {
            $err_msg[] = 'DB接続失敗';
        }
    }
}
// var_dump($err_msg);
// var_dump($msg);

include_once '../../include/view/Bulletin_board_user_entry_view.php';
