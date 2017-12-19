<?php
    //パスワード取得用関数
    function getPassWord($pdo, $postnum){
        //id取得
        $stmt = $pdo -> prepare("SELECT id FROM PostContent2 WHERE postnum = :postnum");
        $stmt -> bindValue(':postnum', $postnum, PDO::PARAM_INT);
        $stmt -> execute();
        foreach ($stmt as $row) { 
                $id = $row['id'];
        }
    
        $stmt = $pdo -> prepare("SELECT pass FROM UserMaster3 WHERE id = :id");
        $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
        $stmt -> execute();
        // foreach文で配列の中身を一行ずつ出力
        foreach ($stmt as $row) { 
            $pw = $row['pass']; 
        }
        return $pw;
    }

    //ハッシュ値作成関数
    function getHash($pdo, $postnum, $pass){
        //id取得
        $stmt = $pdo -> prepare("SELECT id FROM PostContent2 WHERE postnum = :postnum");
        $stmt -> bindValue(':postnum', $postnum, PDO::PARAM_INT);
        $stmt -> execute();
        foreach ($stmt as $row) { 
                $id = $row['id'];
        }
        
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
        $hash = substr($hash, 0, 30);
        return $hash;
    }
?>