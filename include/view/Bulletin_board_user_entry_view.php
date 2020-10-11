<!DOCTYPE html>
	<html>
		<head>
			<title>ポートフォリオな掲示板ユーザ新規登録ページ</title>
			<meta charset="utf-8">
			<link rel="stylesheet" href="style.css">
			<meta http-equiv="X-UA-Compatible" content="IE=edge" />
			<meta name="description"content="エンジニアになりたい初学者がスキルアプールのために作成した掲示板です">
			<script>
				function passwd_display_change(){
					if(passwd_display.checked){
						user_passwd.setAttribute('type','text');
					} else {
						user_passwd.setAttribute('type','password');
					}
				}
				window.onload = function () {
					var user_passwd = document.getElementById('user_passwd');
// 					console.log(user_passwd);
					var passwd_display = document.getElementById('passwd_display');
// 					console.log(passwd_display);
					passwd_display.addEventListener('change', passwd_display_change, false);
				}
			</script>
			<style>
			     body{
			     width :100vw;
			     text-align :center;
			     }

			</style>
		</head>
		<body>
			<header>
				<h1>ポートフォリオな掲示板</h1>
				<h2>ユーザ新規登録</h2>
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

			<form method="post">
				<p><label>ユーザ名：<input type="text" name="user_name"placeholder="山田太郎"></label></p>
				<p><label>パスワード：<input type="password" id="user_passwd" name="user_passwd" placeholder="Ii12345"></label></p>
				<p>パスワードを表示する<input type="checkbox" id="passwd_display"></p>
				<p><input type="submit" value="新規登録"></p>
			</form>

			</main>
			<footer>
				<a href="Bulletin_board_user_login.php">ログインページへ</a>
				<p>©️2020 Hiro</p>
			</footer>
		</body>
	</html>