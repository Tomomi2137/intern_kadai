<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    <?php
        // DB設定
        $dsn = 'mysql:dbname=xxxxxxxxxxxxxxdb;host=localhost';
        $user = 'xxxxxxxxxxxxxx';
        $password = 'xxxxxxxxxxxxxx';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
        // テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS mission5"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "datetime DATETIME,"
        . "password TEXT"
        .");";

        $stmt = $pdo->query($sql);
        //初期化
        $edit_number = '';
        $edit_name = '';
        $edit_comment = '';

        // hidden_valueがあるかの判定
        if (isset($_POST['formtype'])) {
            $sendform = $_POST['formtype'];

            // 投稿フォームの処理
            if ($sendform==='postform' && !empty($_POST['name']) && !empty($_POST['comment'] && !empty($_POST['post_pass']))) {

                // 変数定義
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $postpass = $_POST["post_pass"];
    
                date_default_timezone_set('Asia/Tokyo');
                $date = date("Y/m/d H:i:s");

                // editNoがないときは新規投稿
                if (empty($_POST['editNo'])) {

                    // 書き込み処理:追記
                    if ($name!='' && $comment!='' && $postpass!='') {

                        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, datetime, password) VALUES (:name, :comment, :datetime, :password)");
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $sql -> bindParam(':datetime', $date, PDO::PARAM_STR);
                        $sql -> bindParam(':password', $postpass, PDO::PARAM_STR);
                    
                        $sql -> execute();
                    } 
                // 編集
                } else {
                    $editNo = $_POST['editNo'];
                    $editpass = $_POST['post_pass'];
                    $id = $editNo; //変更する投稿番号
                    
                    // 既存のパスワード取得
                    if (isset($id)) {
                        $sql = 'SELECT * FROM mission5 WHERE id=:id ';
                        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                        $stmt->execute();                             // ←SQLを実行する。
                        $results = $stmt->fetchAll();
                        foreach ($results as $row){
                            $existpass = $row['password'];
                            if ($existpass == $editpass) {
                                //更新
                                $sql = 'UPDATE mission5 SET name=:name,comment=:comment WHERE id=:id';
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                $stmt->execute();
                            }
                        }
                    }else {
                        echo 'id is not';
                    }
                }
            // 削除フォームの処理
            } elseif ($sendform==='deleteform' && !empty($_POST['delete_pass'] && !empty($_POST['delete_num']))){
                $delete_num = $_POST["delete_num"];
                $deletepass = $_POST["delete_pass"];
                $id = $delete_num;

                // passwordの取得
                if (isset($id)) {
                    $sql = 'SELECT * FROM mission5 WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                    $stmt->execute();                             // ←SQLを実行する。
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        $existpass = $row['password'];
                        if ($deletepass === $existpass) {
                            //削除
                            $sql = 'delete from mission5 where id=:id';
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }else {
                    echo 'id is not';
                }
            //編集フォーム
            } elseif ($sendform==='editform' && !empty($_POST['edit_num']) && !empty($_POST['edit_pass'])) {
                $edit_num = $_POST['edit_num'];
                $editpass = $_POST['edit_pass'];
                $id = $edit_num;

                // passwordの取得
                if (isset($id)) {
                    $sql = 'SELECT * FROM mission5 WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                    $stmt->execute();                             // ←SQLを実行する。
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        $existpass = $row['password'];
                        if ($editpass === $existpass) {
                            // 編集したい値を取得
                            $edit_number = $row['id'];
                            $edit_name = $row['name'];
                            $edit_comment = $row['comment'];
                        }
                    }
                }     
            } else {
                // 入力が足りなければここにはいる
                // echo 'Error!';
            }
        } else {
            // 何もしない
        }
    ?>
    <!-- actionは特に記述がない場合は自己ファイルに投げられる -->
    <!--投稿フォーム-->
    <form action="" method="post" name="postform">
        【　投稿フォーム　】<br>
        名前 :　　　　<input type="text" name="name" placeholder="名前" value="<?php echo $edit_name; ?>"><br>
        コメント :　　<input type="text" name="comment" placeholder="コメント" value="<?php echo $edit_comment; ?>"><br>
        password:　　<input type="text" name="post_pass" placeholder="パスワード"><br>
        <input type="hidden" name="editNo" value="<?php echo $edit_number; ?>">
        <input type="submit" name="submit" value="送信">
        <input type="hidden" name="formtype" value="postform" checked="checked">
    </form>
    <!--削除フォーム-->
    <form action="" method="post" name="deleteform">
        【　削除フォーム　】<br>
        削除対象番号:<input type="text" name="delete_num" placeholder="投稿番号"><br>
        password:　<input type="text" name="delete_pass" placeholder="パスワード"><br>
        <!-- <input type="text" name="comment" placeholder="コメント"><br> -->
        <input type="submit" name="delete_submit" value="削除">
        <input type="hidden" name="formtype" value="deleteform" checked="checked">
    </form>
    <!-- 編集番号指定用フォーム -->
    <form action="" method="post" name="editform">
        【　編集番号指定用フォーム　】<br>
        編集対象番号:<input type="text" name="edit_num" placeholder="編集番号"><br>
        <!-- <input type="text" name="comment" placeholder="コメント"><br> -->
        password:　<input type="text" name="edit_pass" placeholder="パスワード"><br>
        <input type="submit" name="edit_submit" value="編集">
        <input type="hidden" name="formtype" value="editform" checked="checked">
    </form>


    <?php
        // ブラウザへの表示
        // DB設定
        $dsn = 'mysql:dbname=xxxxxxxxxxxxxxdb;host=localhost';
        $user = 'xxxxxxxxxxxxxx';
        $password = 'xxxxxxxxxxxxxx';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].' '. $row['name'].' '. $row['comment'].' '. date("Y/m/d H:i:s", strtotime($row['datetime'])). '<br>';
        }
    ?>
</body>
</html>