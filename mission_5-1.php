<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body> 

    <?php
        //DB接続
        $dsn = 'DB名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        //編集機能_表示
        if(!empty($_POST["editnumber"])){
            $editnum=$_POST["editnumber"];
            $editpass=$_POST["editpass"];
            //データ取得
            $sql = 'SELECT * FROM tb_mission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($row["id"] == $editnum && $row["pass"] == $editpass){
                    //既存の投稿フォームに名前とコメントとパスワードの内容が既に入った状態で表示させる
                    $ednum=$row["id"];
                    $edname=$row["name"];
                    $edcom=$row["comment"];
                    $edpass=$row["pass"];
                }
            }
        }

        //編集機能_差し替え
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"]) && !empty($_POST["editnum"])){
            $editchecknum=$_POST["editnum"];
            //データ取得
            $sql = 'SELECT * FROM tb_mission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //データ更新
                if($row["id"] == $editchecknum){
                	$id = $editchecknum;
    	            $name = $_POST["name"];
                	$comment = $_POST["comment"];
                    $date = date("Y/m/d H:i:s");
                    $pass = $_POST["pass"];
                	$sql = 'UPDATE tb_mission5 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
            	    $stmt = $pdo->prepare($sql);
                	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
                	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                	$stmt->execute();
                }
            }
        }

    ?>

    <form action="" method="post">
        <span style="font-size:30px;">
        <span style="background-color:yellow">
            皆さんが飼っているペットを教えてください！
        </span>
        </span>
        <br><br>
        【新規投稿フォーム】<br>
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($edname)){echo $edname;} ?>">
        <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($edcom)){echo $edcom;} ?>">
        <input type="password" name="pass"  placeholder="パスワード" value="<?php if(!empty($edpass)){echo $edpass;} ?>">
        <input type="submit" name="submit" value="投稿">
        <br>
        【削除フォーム】<br>
        <input type="number" name="deletenumber" placeholder="投稿番号">
        <input type="password" name="deletepass"  placeholder="パスワード">
        <input type="submit" name="delete" value="削除">
        <br>
        【編集フォーム】<br>
        <input type="number" name="editnumber" placeholder="投稿番号">
        <input type="password" name="editpass"  placeholder="パスワード">
        <input type="submit" name="edit" value="編集">
        <input type="hidden" name="editnum" value="<?php if(!empty($ednum)){echo $ednum;} ?>">
    </form>

    <?php
        //新規投稿
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"]) && empty($_POST["editnum"])){
            //データ登録
    	    $sql = $pdo -> prepare("INSERT INTO tb_mission5 (name,comment,date,pass) VALUES (:name,:comment,:date,:pass)");
        	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name=$_POST["name"];
            $comment=$_POST["comment"];
            $date=date("Y/m/d H:i:s");
            $pass=$_POST["pass"];
	        $sql -> execute();            
            echo "投稿を受け付けました。<br><br>";
        }

        //削除機能
        if(!empty($_POST["deletenumber"]) && !empty($_POST["deletepass"])){
            $delnum=$_POST["deletenumber"];
            $delpass=$_POST["deletepass"];
            //データ取得
            $sql = 'SELECT * FROM tb_mission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //データ削除
                if($row["id"] == $delnum && $row["pass"] == $delpass){
                $id = $delnum;
                $sql = 'delete from tb_mission5 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                echo $delnum."を削除しました。<br><br>";
                }
            }
        }

        echo "<br>";

        //テーブルに登録されたデータを取得し、表示
        $sql = 'SELECT * FROM tb_mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            echo "<hr>";
        }

    ?>

</body>
</html>