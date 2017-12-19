<?php
    //データベースに接続
    //データベース情報
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    //オブジェクト作成
    try{
        $pdo = new PDO($dsn, $user, $password);
    }catch (PDOException $e){
        print('Error:'.$e->getMessage());
        die();
    }
    //追記部終わり
    
    //名前取得
    session_start();
    $id = $_SESSION['id'];
    $stmt = $pdo -> prepare("SELECT name FROM UserMaster3 WHERE id = :id");
    $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
    $stmt -> execute();
    foreach ($stmt as $row) { 
            $userName = $row['name'];
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>mission 3_main</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    
    <body>     
        <form action="mission3_main.php" method="post" enctype="multipart/form-data">
            <p>名前:<?php echo $userName; ?></p>
            <p>コメントを記入</p>
            <p><textarea name="comment" cols="40" rows="8" > <?php echo $f_content; ?></textarea></p>
            <p><input type="file" name="insertimage"></p>
            <p><button type="submit">投稿</button></p>
        </form>
        
        <form>
            <input type="button" value="投稿を削除" onclick="location.href='http://co-420.it.99sv-coco.com/mission3/deletecontent.php'">
        </form>
        <p></p>
        <form>
            <input type="button" value="投稿を編集" onclick="location.href='http://co-420.it.99sv-coco.com/mission3/editcontent.php'">
        </form>
        <p></p>
        <p></p>
    </body>
</html>

<?php
    //新規投稿処理
    if(!empty($_POST['comment']) && ($_POST['editFlag'] == 0)){//編集モードでないとき
        //データベースへ書き込み
        //書き込みデータ準備
        $img_path = $_FILES["insertimage"]["tmp_name"];
        $uploaddata = file_get_contents($img_path);  
        $ext = pathinfo($img_path, PATHINFO_EXTENSION); //画像の拡張子
        echo $ext; //test
        $contentdata = htmlspecialchars($_POST['comment']);
        $postdate = date("Y/m/d H:i");
        //書き込み処理
        $stmt = $pdo -> prepare("INSERT INTO PostContent2 (id,content,uploaddata,ext,postdate) VALUES (:id, :content, :uploaddata, :ext, :postdate)");
        $stmt -> bindValue(':id', $id, PDO::PARAM_STR);
        $stmt -> bindValue(':content', $contentdata, PDO::PARAM_STR);
        $stmt -> bindValue(':uploaddata', $uploaddata, PDO::PARAM_STR);
        $stmt -> bindValue(':ext', $ext, PDO::PARAM_STR);
        $stmt -> bindValue(':postdate', $postdate, PDO::PARAM_STR);
        $stmt -> execute();
    }
        
    //データベースから読み込んで表示
    $sql = "SELECT * FROM PostContent2 ORDER BY postnum";
 
    $stmt = $pdo->query($sql);  // SQLステートメントを実行し、結果を変数に格納

    // foreach文で配列の中身を一行ずつ出力
    foreach ($stmt as $row) {
        $postnum = $row['postnum'];
        echo $row['postnum']."<br>";
        //echo $userName."<br>";
        //名前取得
        $id = $row['id'];
        $stmt = $pdo -> prepare("SELECT name FROM UserMaster3 WHERE id = :id");
        $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
        $stmt -> execute();
        foreach ($stmt as $row) { 
                $name = $row['name'];
        }
        echo $name."<br>";
        echo $row['content']."<br>";
        //echo $row['ext']."<br>";
        echo "<img src='image_source.php?postnum=$postnum'>"."<br>";
        echo $row['postdate']."<br>";
        echo "<br>";
    }    
?>

