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
        <title>mission 3_editcontent</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    
    <body>     
        <form action="editcontent.php" method="post">
            <p>投稿を編集します。</p>
            <p>編集番号:<input type="number" name="editNumber" size="1">&nbsp;&nbsp;</p>
            <p><textarea name="comment" cols="40" rows="8" > <?php echo $f_content; ?></textarea></p>
            <p><input type="file" name="insertimage"></p>
            <p>パスワード:<input type="text" name="password"> </p>
            <p><button type="submit">編集</button></p>
        </form>
        
        <form>
            <input type="button" value="戻る" onclick="location.href='http://co-420.it.99sv-coco.com/mission3/mission3_main.php'"><br>
        </form>
        
        <?php
            include 'functions.php';
            //編集処理
            if(!empty($_POST['password']) && !empty($_POST['comment'])){
                //パスワード認証
                $pw = getPassWord($pdo, $_POST['editNumber']);
                $hash = getHash($pdo, $_POST['editNumber'], $_POST['password']);

                if($pw == $hash){
                    //データベースの更新
                    $stmt = $pdo -> prepare("UPDATE PostContent SET content =:content WHERE postnum = :editnumber");     
                    $stmt-> bindValue(':content', $_POST['comment'], PDO::PARAM_STR);
                    $stmt-> bindValue(':editnumber', $_POST['editNumber'], PDO::PARAM_INT);
                    $stmt-> execute();

                    echo $_POST['editnumber']."番の投稿を編集しました。<br><br>";
                }else{
                    echo "パスワードが違います<br>";
                }
            }
        ?>
        <p></p>
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
        echo $name."<br>";
        echo $row['content']."<br>";
        echo "<img src='image_source.php?postnum=$postnum'>"."<br>";
        echo $row['postdate']."<br>";
        echo "<br>";
    }    
?>

