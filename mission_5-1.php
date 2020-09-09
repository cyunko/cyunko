<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>mission_5-1</title>
</head>
<body>
    <font size="4">書き込みお願いします！バグは知らせていただけると嬉しいです＾ ＾<br></font>
    <font size="3">
    ・名前、コメント、パスワードを書いて投稿してください<br>
    ・削除は削除したい番号とパスワードを入れて削除ボタンを押してください<br>
    ・編集は編集したい番号とパスワードを入れて編集ボタンを押すと、<br>
      名前、コメント、パスワードが表示されるので変更後、編集ボタンを押してください<br></font>
    <?php
    
    //データベース作る
    $dsn = 'データーベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	//データベース接続
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//テーブル作る
	$sql = "CREATE TABLE IF NOT EXISTS tbmission"
	." ("
	. "id  INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32) NOT NULL,"
	. "comment TEXT NOT NULL,"
	. "date DATETIME,"
	. "mypassword TEXT NOT NULL"
	.");";
	$stmt = $pdo->query($sql);
	    
        //データを新規入力
        //もし送信ボタンが押されたら
        if ( isset( $_POST['soushin'] ) === true ) {
            //name,comment,passwordをPOST送信、時間を決める
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $date = date('Y-m-d H:i:s');
            $mypassword = $_POST['mypassword'];
            
            //もし名前とコメントが白紙でなかったら
            if (!empty($name) && !empty($comment)) {
                //データを新規入力
	            $sql = $pdo -> prepare("INSERT INTO tbmission (id, name, comment,date, mypassword) 
	            VALUES (:id, :name, :comment, :date, :mypassword)");
	            $sql -> bindParam(':id', $id, PDO::PARAM_STR);
	            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	            $sql -> bindParam(':mypassword', $mypassword, PDO::PARAM_STR);
	            //実際にSQLを実行する
	            $sql -> execute();
            }
        
        }
            
        //データを削除
        //削除ボタンを押したとき
        if(isset($_POST['deletebotan']) === true ){
            
            //削除番号と削除パスワードをPOST送信
            $delete = $_POST["delete"];
            $deletepw = $_POST["deletepw"];
              
            //削除番号が空じゃなかったら
            if(!empty($delete) && !empty($deletepw)){
           
                //IDと削除番号が,パスワードが一致したら削除
                $sql = "delete from tbmission where id= '$delete' AND mypassword= '$deletepw'";
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':delete', $delete, PDO::PARAM_INT);
	            $stmt->bindParam(':deletepw', $deletepw, PDO::PARAM_INT);
	            //実際にSQLを実行する
	            $stmt->execute();
            }else{
                echo"パスワードが間違っています。";
            }
        
        }
        
        //データを編集
        //編集ボタンを押したとき
        if(isset($_POST["hennsyuu"])){
            //編集番号,編集パスワード,POST送信
            $edit=$_POST["edit"];
            $editpw=$_POST["editpw"];
            $edit_hidden=$_POST["edit_hidden"];
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $date = date('Y-m-d H:i:s');
            $mypassword = $_POST['mypassword'];
            //名前とコメントが空で、編集番号が埋まっている場合
            if(empty($name) && empty($comment) && !empty($edit)){
                
                $sql2    = 'SELECT * FROM tbmission';
                $results = $pdo ->query($sql2);
                $data    = $results->fetchAll();
                foreach($data as $row){
                        if($row['id']==$edit){
                            if($row['mypassword'] == $editpw){
                                $editdata[1] =$row['name'] ;
                                $editdata[2] =$row['comment'] ;
                                $editdata[4] =$row['mypassword'];
                            }else{
                            echo"パスワードが間違っています。";
                            }
                        }
                }
            
            }
                
            
            //名前とコメントが埋まっていたら
            if(!empty($name) && !empty($comment)){
                
                //IDと編集予定番号が一致したら
                //コメントと名前を差し替える
	            //変更したい名前、変更したいコメントは自分で決めること、ここを変数で入れる
	            $id = $edit_hidden;
	            $sql = "UPDATE tbmission SET name=:name,comment=:comment ,date=:date ,mypassword=:mypassword 
	            where id=:id ";
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
	            $stmt-> bindParam(':mypassword', $mypassword, PDO::PARAM_STR);
	            $stmt->execute();
            }
            
        }
    
	?>
	
	<form action="#" method="post">
        <p> 名前    : <br>
        <input type="text1" name="name" 
        value="<?php echo $editdata[1]; ?>"></p>
        <p> コメント: <br>
        <textarea type="text2" name="comment" cols="30" rows="5"><?php echo $editdata[2]; ?></textarea><br>
        <p> パスワード： <br>
        <input type="text" name="mypassword" value="<?php echo $editdata[4]; ?>" ></p>
        <input type="submit" name="soushin" value ="送信"/><br>
        <p> 削除対象番号： <br>
        <input type ="text" name="delete"></p>
        <p> パスワード：<br>
        <input type="text" name="deletepw"></p>
        <input type="submit" name="deletebotan" value ="削除">
        <p> 編集対象番号: <br>
        <input type="hidden" name = "edit_hidden" value="<?php echo $edit?>">
        <input type ="text" name="edit"></p>
        <p>パスワード：<br>
        <input type="text" name="editpw"></p>
        <p><input type="submit" name="hennsyuu" value ="編集"></p>
    </form>
	<?php
	
	//データを表示
	$sql = 'SELECT * FROM tbmission';
	//結果を取得する
	$result = $pdo->query($sql);
	$results = $result->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';
		echo $row['date'].'<br>';
	    echo "<hr>";
	
	}
	//データベースから切断する
	unset($pdo);
	
    ?>
	
</body>
</html>