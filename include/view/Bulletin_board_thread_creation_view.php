<!DOCTYPE html>
	<html>
		<head>
			<title>ポートフォリオな掲示板スレッド作成ページ</title>
			<meta charset="utf-8">
			<link rel="stylesheet" href="style.css">
			<meta http-equiv="X-UA-Compatible" content="IE=edge" />
			<meta name="description"content="エンジニアになりたい初学者がスキルアプールのために作成した掲示板です">
			<style>
                body{
                list-style-type:square;
			     width :100vw;
			     text-align :center;
			     }
                .thread_link{
                display :flex;
                flex-wrap :wrap;
                justify-content :center;
                margin-bottom :5vh;
                }
                .button {
                background-color:transparent;
                border :none;
                text-decoration:underline;

                }
                .button:hover{
                text-decoration:none;
                background :#FAEBD7;
                }
                .button:focus{
                outline :0;
                }
			</style>
		</head>
		<body>
			<header>
				<h1>ポートフォリオな掲示板</h1>
			</header>

			<main>
				<div class="msg">
    				<?php if(count($err_msg) !== 0):?>
        				<?php foreach($err_msg as $value):?>
        					<p><?php echo $value?></p>
        				<?php endforeach;?>
        			<?php endif ?>
        			<?php if(count($msg) !== 0):?>
        				<?php foreach($msg as $value):?>
        					<p><?php echo $value ?></p>
        				<?php endforeach;?>
    				<?php endif ?>
    			</div>
    			<h2>スレッド作成</h2>
    			<form method="post">
    				<p><label for="thread">スレッド名：</label>
    				<input type="text" name="thread_name" placeholder="べんきょうについて" id="thread"></p>
    				<p><label for="less">スレッド内容：</label></p>
    				<p><textarea name="comment"rows="5" cols="50" id="less" placeholder="なにからべんきょうする"></textarea></p>
    				<button type="submit" >スレッド作成</button>
    			</form>
    			<h2>スレッド一覧</h2>
    			<?php if(isset($msg_content) === TRUE):?>
    				<p><?php echo $msg_content; ?></p>
    			<?php else : ?>
				<div class="thread_link">
    			<?php foreach($thread_list as $value):?>
        				<form method="post" action="./Bulletin_board_less_creation.php">
        						<input type="hidden" name="thread_id" value="<?php echo $value['thread_id']?>">
        						<input type="hidden" name="thread_name" value="<?php echo $value['thread_name'];?>">
        						<input type="hidden" name="post_identification" value="from_thread">
        						<input class="button"type="submit" value="<?php echo $value['thread_name'];?>">
        				</form>
    			<?php endforeach; ?>
				</div>
    			<?php endif ;?>
			</main>
			<footer>
				<a href="Bulletin_board_logout.php">ログアウト</a>
				<p>©️2020 Hiro</p>
			</footer>
		</body>
	</html>