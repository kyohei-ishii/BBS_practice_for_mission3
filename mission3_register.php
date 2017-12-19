<!DOCTYPE html>
<html>
    <head>
        <title>mission 3_register</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
</html>  

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

    if(($_POST['name']) && ($_POST['mail']) && ($_POST['passWord'])){//全て入力済み

        //データベースへ書き込み
        //書き込みデータ準備
        $id = uniqid();
        //重複回避処理
        $name = htmlspecialchars($_POST['name']);
        $mail = htmlspecialchars($_POST['mail']);
        $pass = htmlspecialchars($_POST['passWord']);
        $Rdate = date("Y/m/d H:i");
        $Rflg = 0;

        //追記　ハッシュ化
        include 'dsn.php';
        $dsnsalt1 = $dsn['salt1'];
        $dsnsalt2 = $dsn['salt2'];

        //------------SALT作成-----------
        $salt1 = pack('H*', $dsnsalt1);
        $salt = $id . $salt1;

        //------------STRETCHING--------
        $hash = '';
        for($i = 0; $i < $dsnsalt2 ; $i++){
            $hash = hash('sha256', $hash.$pass.$salt);
        }

        //書き込み処理
        $stmt = $pdo -> prepare("INSERT INTO UserMaster3 (id,name,mail,pass,Rdate,Rflg) VALUES (:id, :name, :mail, :pass, :Rdate, :Rflg)");
        $stmt -> bindValue(':id', $id, PDO::PARAM_STR);
        $stmt -> bindValue(':name', $name, PDO::PARAM_STR);
        $stmt -> bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt -> bindValue(':pass', $hash, PDO::PARAM_STR);
        $stmt -> bindValue(':Rdate', $Rdate, PDO::PARAM_STR);
        $stmt -> bindValue(':Rflg', $Rflg, PDO::PARAM_BOOL);
        $stmt -> execute();

        //echo "登録完了";

        //メール送信
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $to      = $_POST['mail'];
        $subject = '認証メール';
        $message = 'http://co-420.it.99sv-coco.com/mission3/mission3_compregister.php?id='.$id;
        $headers = 'From: from@hoge.co.jp' . "\r\n";

        mb_send_mail($to, $subject, $message, $headers);
        }
?>