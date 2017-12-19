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
        <form action="deletecontent.php" method="post">
            <p>投稿を削除します。</p>
            <p>削除番号:<input type="number" name="delNumber" size="1">&nbsp;&nbsp;</p>
            <p>パスワード:<input type="text" name="password"> </p>
            <p><input type="submit" value="削除"></p>
        </form>
        
        <form>
            <input type="button" value="戻る" onclick="location.href='http://co-420.it.99sv-coco.com/mission3/mission3_main.php'"><br>
        </form>
        
        <p></p>
        
        <?php
            include 'functions.php';
            //削除処理
            if(!empty($_POST['delNumber']) && !empty($_POST['password'])){
                //パスワード認証
                $pw = getPassWord($pdo, $_POST['delNumber']);
                $hash = getHash($pdo, $_POST['delNumber'], $_POST['password']);
                //削除処理
                if($pw == $hash){    
                    $stmt = $pdo -> prepare("DELETE FROM PostContent2 WHERE postnum = :delnumber");
                    $stmt -> bindValue(':delnumber', $_POST['delNumber'], PDO::PARAM_INT);
                    $stmt -> execute();
                    echo htmlspecialchars($_POST['delNumber'])."番の投稿を削除しました。<br><br>";
                }else{
                    echo "パスワードが違います<br>";
                }
            }
        ?>
    </body>
</html>

<?php
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
        echo $name;
        echo $row['content']."<br>";
        //echo $row['ext']."<br>";
        echo "<img src='image_source.php?postnum=$postnum'>"."<br>";
        echo $row['postdate']."<br>";
        echo "<br>";
    }    
?>

