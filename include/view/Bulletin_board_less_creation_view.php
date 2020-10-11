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
			     width :100vw;
			     text-align :center;
			     margin :0 auto;
			     }
                　
			     h3{
			         font-weight :normal;
			     }
			     h4{
			         margin :1vh;
			     }
			     ol{
			     display :flex;
			     align-items :center;
			     flex-direction :column;
			     padding :0;
			     }
			     .less_button{
			     margin-right :10px;
			     margin-bottom :10px
			     }
			     .hide{
			     display :none;
			     }
			     footer{
			     clear:both;
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
    			<?php if(isset($thread_id) === TRUE):?>
    				<h2><?php echo $thread_name ;?></h2>
    				<h3><?php echo $less_content[0]['comment'];?></h3>
    				<?php unset($less_content[0])?>
    				<ol>
    					<?php foreach($less_content as $value):?>
            				<li>
            					<p><?php echo $value['user_name']?></p>
            					<h4><?php echo $value['comment']?></h4>
            				</li>
        				<?php endforeach;?>
    				</ol>
    				<form method="post">
    					<textarea name="comment"rows="5" cols="50" id="less" placeholder="なにからべんきょうする"></textarea>
    					<input type="hidden" name="post_identification" value="from_less">
    					<p><input type="submit" value="レス作成"class="less_button"></p>
    				</form>
    				<form method="post">
    				<input type="hidden" name="post_identification" value="delete_thread">
    				<input type="submit" value="スレッド削除" class="<?php echo $author ;?>">
    				</form>
				<?php endif ;?>
			</main>
			<footer>
				<a href="Bulletin_board_thread_creation.php">スレッド一覧へ</a>
				<a href="Bulletin_board_logout.php">ログアウト</a>
				<p>©️2020 Hiro</p>
			</footer>
		</body>
	</html>