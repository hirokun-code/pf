<?php
include_once '../../include/model/Bulletin_board_function.php';
$date_now = null;
$err_msg = [];
$msg_content = [];
$msg = [];
$thread_author = FALSE;
//ログインしていない場合はログインページへ
session_start();
if(isset($_SESSION['user_name']) !== TRUE){
    header('Location:Bulletin_board_user_login.php');
    exit;
}
//ログインユーザ情報
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];




if(get_server() === 'POST'){
    if(isset($_POST['post_identification']) === TRUE){
        $post_identification = $_POST['post_identification'];
    }
    //ポストがスレッドからの場合
    if($post_identification === 'from_thread'){
        if(isset($_POST['thread_id']) === TRUE){
            $thread_id = $_POST['thread_id'];
        }
        if(isset($_POST['thread_name']) === TRUE){
            $thread_name = $_POST['thread_name'];
//             var_dump($thread_name);
        }
        if(!$thread_id = number_check($thread_id)){
            $err_msg[] = 'IDエラー';
        }
        if(!$thread_name = word_count_check($thread_name)){
            $err_msg[] = 'タイトルエラー';
        }
        $_SESSION['thread_id'] = $thread_id;
        $_SESSION['thread_name'] = $thread_name;
    }
    //ポストがレスから場合
    if($post_identification === 'from_less'){
        if($link = db_connect()){
            mysqli_autocommit($link, false);
            if(isset($_POST['comment']) === TRUE){
                $comment = $_POST['comment'];
            }
            if(!$comment = word_count_check($comment)){
                $err_msg[] = '2文字以上で入力してください';
            }
            $date_now = date('Y/n/d H:i:s');
            $thread_id = $_SESSION['thread_id'];
            $thread_name = $_SESSION['thread_name'];
            $less_create = "INSERT INTO less(thread_id, comment, writing_date, user_id)
                            VALUES('$thread_id', '$comment', '$date_now', '$user_id')";
            if(!$less_create = db_insert($link, $less_create)){
                $err_msg[] = 'レス追加失敗1';
            } else {
                $thread_writing_date_up = "UPDATE thread SET writing_date = '$date_now' WHERE thread_id = '$thread_id'";
//                 print_r($thread_writing_date_up);
                if(!db_update($link, $thread_writing_date_up)){
                    $err_msg[] = 'レス追加失敗2';
                }
            }
        } else {
            $err_msg[] = 'DB接続エラー';
        }
        if(count($err_msg) === 0){
            mysqli_commit($link);
            $msg[] = 'レス作成成功';
        } else {
            mysqli_rollback($link);
        }
        mysqli_close($link);
    }
    //ポストがスレッド削除ならば
    if($post_identification === 'delete_thread'){
        if($link = db_connect()){
            mysqli_autocommit($link, $false);
            $thread_id = $_SESSION['thread_id'];
            $thread_delete = "SELECT thread_name, user_id FROM thread WHERE
                              thread_id = '$thread_id' and user_id = '$user_id'";
            if(db_select($link, $thread_delete)){
                $thread_delete = "DELETE less FROM less WHERE thread_id = '$thread_id'";
                if(db_delete($link, $thread_delete)){
                    $thread_delete = "DELETE thread FROM thread WHERE thread_id = '$thread_id'";
                    if(!db_delete($link, $thread_delete)){
                        $err_msg[] = 'thread削除失敗';
                    }
                } else {
                    $err_msg[] = 'less削除失敗';
                }
            } else {
                $err_msg[] = 'スレッド削除失敗';
            }
            if(count($err_msg) === 0){
                mysqli_commit($link);
                header('Location:Bulletin_board_thread_creation.php');
                exit;
            } else {
                mysqli_rollback($link);
            }
            mysqli_close($link);

        } else {
            $err_msg[] = 'DB接続失敗';
        }
    }



    //レス一覧を取得
    if($link = db_connect()){
        $less_content = "SELECT less.thread_id, less.comment, less.writing_date, user_info.user_name FROM less
                             JOIN user_info ON less.user_id = user_info.user_id WHERE
                             less.thread_id = '$thread_id' ORDER BY writing_date";
//         print_r($less_content);
        if(!$less_content = db_select($link, $less_content)){
            $err_msg[] = 'レス一覧エラー';
        }
    } else {
        $err_msg[] = 'DB接続エラー';
    }
    mysqli_close($link);

} else {
    header('Location:Bulletin_board_thread_creation.php');
    exit;
}

//スレッド主確認
if($link = db_connect()){
    $thread_author = "SELECT thread_id, user_id FROM thread WHERE
                      user_id = '$user_id' and thread_id = '$thread_id'";
    if(!$thread_author = db_select($link, $thread_author)){
        $author = 'hide';
    }
} else {
    $err_msg[] = 'DB接続エラー';
}

include_once '../../include/view/Bulletin_board_less_creation_view.php';