<!DOCTYPE html>
<html>
    <head>
        <title>mission 3_login</title>
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

    include 'dsn.php';
    $dsnsalt1 = $dsn['salt1'];
    $dsnsalt2 = $dsn['salt2']; 

    //---------------------------------------
    //ログイン認証
    //---------------------------------------
    if (!empty($_POST["id"]) && !empty($_POST["passWord"])){
        $id = trim($_POST["id"]);
        $pass = trim($_POST["passWord"]);
        
        //------------SALT作成-----------
        $salt1 = pack('H*', $dsnsalt1);
        $salt = $id . $salt1;
        
        //------------STRETCHING--------
        $hash = '';
        for($i = 0; $i < $dsnsalt2 ; $i++){
            $hash = hash('sha256', $hash.$pass.$salt);
        }
        $hash = substr($hash, 0, 30);
                
        //--------------------------------------
        //テーブル情報呼び出し変数代入
        //--------------------------------------
        $error = "";
        if ($error == "" ){
            setcookie("logid",$id,time()+(7 * 24 * 60 * 60));
            setcookie("logpass",$pass,time()+(7 * 24 * 60 * 60));

            $sql = 'SELECT * FROM UserMaster3 WHERE id= :logid AND pass= :logpass;';
            //$sql = 'SELECT * FROM UserMaster3 ;';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':logid', $id, PDO::PARAM_STR);
            $stmt -> bindParam(':logpass', $hash, PDO::PARAM_STR);
            $stmt -> execute();
           
            if ($table = $stmt -> fetch(PDO::FETCH_ASSOC)) {

                session_start();
                $_SESSION['loginDate'] = date("Y/m/d H:i");
                $_SESSION['id'] = $table['id'];
                
                header('Location: mission3_main.php');
                exit;
            } else {
                $error = '<p class="er">IDかPASSWORDが違います。</p>';
            }
       }
    }else{
        echo "IDとパスワードを両方入力してください";
    }
?>
