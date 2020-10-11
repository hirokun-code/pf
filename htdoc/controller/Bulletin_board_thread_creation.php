<?php

include_once '../../include/model/Bulletin_board_function.php';
$date_now = null;
$err_msg = [];
$msg_content = null;
$msg = [];
$thread_author = null;

//ログインしていない場合はログインページへ
session_start();
if(isset($_SESSION['user_name']) !== TRUE){
    header('Location:Bulletin_board_user_login.php');
    exit;
}

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];
// var_dump($user_name);
// var_dump($user_id);
// var_dump($_SESSION['user_id']);

//postされたスレッド名をDBへ登録
if(get_server() === 'POST'){
    if(isset($_POST['thread_name']) === TRUE){
        $thread_name = $_POST['thread_name'];
    }
    if(isset($_POST['comment']) === TRUE){
        $comment = $_POST['comment'];
    }
    //２文字以上の文字数かどうか
    $thread_name = word_count_check($thread_name);
    $comment = word_count_check($comment);
    if($thread_name === null){
        $err_msg[] = 'スレッド名を２文字以上で入力してください';
    }
    if($comment === null){
        $err_msg[] = 'スレッド内容を２文字以上で入力してください';
    }
//     var_dump($thread_name);

    //エラーがなければスレッド登録
    if(count($err_msg) === 0){
        if($link = db_connect()){
            mysqli_autocommit($link, false);
            $date_now = date('Y/n/d H:i:s');
            //同名スレッド確認
            $thread_name_serch = "SELECT thread_name, count, thread_id FROM thread WHERE thread_name = '$thread_name'";
            //同名スレッドがなければDBへ新規登録
            if(!$duplicate_info = db_select($link, $thread_name_serch)){
                $thread_entry = "INSERT INTO thread(thread_name, writing_date, user_id, count)
                                 VALUES('$thread_name', '$date_now','$user_id', 1)";
//                 var_dump($thread_entry);
                if(db_insert($link, $thread_entry)){
                    //レス内容DBへ登録
                    $thread_id = mysqli_insert_id($link);
                    $less_content = "INSERT INTO less(thread_id, comment, writing_date, user_id)
                                     VALUES('$thread_id', '$comment', '$date_now', '$user_id')";

                    if(!db_insert($link, $less_content)){
                        $err_msg[] = 'レス作成に失敗しました';
                    }
                } else {
                    $err_msg[] = '新規スレッド作成失敗しました';
                }
                if(count($err_msg) === 0){
                    mysqli_commit($link);
                    $msg[] = '新規スレッドを作成に成功しました';
                } else {
                    mysqli_rollback($link);
                }
            }
            //同名スレッドの場合末に番号を振る
            else {
                $thread_id_past = $duplicate_info[0]['thread_id'];
                $thread_id_past = intval($thread_id_past);
//                 var_dump($thread_id);
                mysqli_autocommit($link, false);
                $count = $duplicate_info[0]['count'];
//                 var_dump($duplicate_info[0]['count']);
                $count = intval($count);
                $count += 1;
//                 var_dump($count);
                $thread_name = $duplicate_info[0]['thread_name'];

//                 var_dump($thread_name);
                $thread_name_update = $thread_name . "($count)";
                $thread_entry = "INSERT INTO thread(thread_name, writing_date, user_id)
                                 VALUES('$thread_name_update', '$date_now','$user_id')";
//                 var_dump($thread_entry);
                if(db_insert($link, $thread_entry)){
                    $msg[] = '新規スレッド作成しました';
//                     var_dump($thread_id);
                    $thread_id_now = mysqli_insert_id($link);

                    $thread_update = "UPDATE thread SET count = $count WHERE thread_id = '$thread_id_past'";
//                     var_dump($thread_update);
                    if(!db_update($link,$thread_update)){
                        $err_msg[] = '回数更新失敗';
                    } else {
                        //レスをDBへ登録
                        $less_content = "INSERT INTO less(thread_id, comment, writing_date, user_id)
                                     VALUES('$thread_id_now', '$comment', '$date_now', '$user_id')";

                        if(!db_insert($link, $less_content)){
                            $err_msg[] = 'レス作成に失敗しました';
                        }
                    }
                } else {
                    $err_msg[] = '新規スレッド作成失敗しました';
                }
                if(count($err_msg) === 0){
                    mysqli_commit($link);
                    $msg[] = 'スレッド名が重複しているため' . $thread_name_update.  'に変更し作成しました';
                } else {
                    mysqli_rollback($link);
                }
            }
            mysqli_close($link);
        } else {
            $err_msg[] = 'DB接続失敗';
        }
    }
}

//スレッド一覧

$link = db_connect();
if(!mysqli_connect_error()){
    $thread_list = 'SELECT thread_id, thread_name, writing_date FROM thread ORDER BY writing_date DESC';
    if(!$thread_list = db_select($link, $thread_list)){
        $msg_content = 'スレッドはありません';
    }
}
mysqli_close($link);
// var_dump($err_msg);
// var_dump($msg);





include_once '../../include/view/Bulletin_board_thread_creation_view.php';
?>