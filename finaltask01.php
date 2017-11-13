<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>STEP1 最終課題:掲示板を作ろう</title>
	<?php 
		//データベース接続
		if($_SERVER['SERVER_NAME']=="localhost"){
			$mysqli = new mysqli("localhost","root","","finaltask01");//host,account,passwd,db_name
		}else{
			$mysqli = new mysqli("localhost","yahiro","oyasumideshi","yahiro");
		}
		if($mysqli->connect_error){
			print("データベースに接続できません。".$mysqli->connect_error);
			exit(1);
		}

		//ブラックリストはここで追加、IPアドレスでチェックを行う、ループバックは::1(なぜかv6)
		$blacklist = array("192.168.2.250");//例として1つ荒らしっぽい書き込みを加えて、排除してみた
	?>
	<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>


<h1>▼掲示板▼</h1>
<p style="width:80%;margin-left:10%;text-align: center;">*コメント欄の情報は必須です。</p>


<!--投稿フォーム-->
<form method="POST" action="finaltask01.php">
<ul>
<li class="in_name">
	<label for="in_name">名前</label>
	<input name="name" placeholder="名無しさん">
</li>
<li class="in_title">
	<label for="in_title">タイトル</label>
	<input name="title" placeholder="">
</li>
<li class="in_email">
	<label for="in_email"> E-MAIL</label>
	<input name="email">
</li>
<li class="in_comment">
	<label for="in_comment">コメント*</label>
	<textarea name="comment"></textarea>
</li>
<?php
if(!in_array($_SERVER["REMOTE_ADDR"],$blacklist)){
?>
<li>
	<input id="submit" type="submit" value="送信">
</li>
<?php
}
?>
</ul>
</form>


<?php
//データベース書き込み
	if(!in_array($_SERVER["REMOTE_ADDR"],$blacklist)){
		if(isset($_POST["name"],$_POST["comment"],$_POST["title"],$_POST["email"])&&$_POST["comment"]!=""){//名前とコメントがある場合、かつ入力が空でない場合
			if($_POST["name"]==""){
				$_POST["name"]="名無しさん";
			}
			if($_POST["title"]==""){
				$_POST["title"]="タイトルなし";
			}
			$stmt = $mysqli->prepare("INSERT INTO forum (title,name,email,comment,ipaddr) VALUES (?, ?, ?, ?, ?)");//？部分はあとから代入(プリペアドステートメント)
			$stmt->bind_param("sssss",$_POST["title"],$_POST["name"],$_POST["email"],$_POST["comment"],$_SERVER['REMOTE_ADDR']);//文字列5つ代入(sはstring、iはint、dはdouble、bはbinary)
			$stmt->execute();//実行
		}
	}else{
		print("<p style='color:red;text-align:center;'>あなたの使用しているIPアドレスからは書き込みを行えません。</p>");
	}

//データベース参照、表示
	$result = $mysqli->query("SELECT * FROM forum ORDER BY time DESC");
	if($result){
		while($row = $result->fetch_object()){
			if(in_array($row->ipaddr,$blacklist))continue;//ブラックリストに入っているipのデータは除く
			$name = htmlspecialchars($row->name);
			$title = htmlspecialchars($row->title);
			$comment = htmlspecialchars($row->comment);
			$time = htmlspecialchars($row->time);
			print(nl2br("<div class='box'><div class='title'>▼$title</div><div class='comment'>$comment</div><div class='footer'>( $name | $time )</div></div>"));
		}
	}

//データベース接続切断
	$mysqli->close();
?>


</body>
</html>