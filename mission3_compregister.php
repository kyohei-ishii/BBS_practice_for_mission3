<!DOCTYPE html>
<html>
    <head>
        <title>mission 3_register</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
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

            //更新データ準備
            $Rflg = 1;

            //データベースの更新
            $stmt = $pdo -> prepare("UPDATE UserMaster3 SET Rflg =:Rflg WHERE id = :id");     
            $stmt-> bindValue(':Rflg', $Rflg, PDO::PARAM_STR);
            $stmt-> bindValue(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt-> execute();

            echo "本登録が完了しました。<br>ログインできます。"
        ?>
        
        <form>
            <input type="button" value="ログイン画面へ" onclick="location.href='http://co-420.it.99sv-coco.com/mission3/mission3_loginform.html'">
        </form>
    
    </body>
</html>
