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
        die();
    }

    // データベースから対象のデータを取得
    $stmt = $pdo->prepare('SELECT ext, uploaddata FROM PostContent2 WHERE postnum = :postnum');
    $postnum = $_GET['postnum'];
    $stmt->bindValue(':postnum', $postnum);
    $stmt->execute();

    // Content-typeテーブル
    $contents_type = array(
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'bmp'  => 'image/bmp',
    );

    // 出力
    $img = $stmt->fetchObject();
    header('Content-type: ' . $contents_type[$img->ext]);
    //header('Content-type: image/png');
    echo $img->uploaddata;
?>