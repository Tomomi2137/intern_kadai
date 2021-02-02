<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    <?php
        // DB設定
        $dsn = 'mysql:dbname=xxxxxxxxxxxxxxxdb;host=localhost';
        $user = 'xxxxxxxxxxxxxxx';
        $password = 'xxxxxxxxxxxxxxx';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        // テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS mission5"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT"
        . "datetime DATETIME"
        . "password TEXT"
        .");";

        $stmt = $pdo->query($sql);

        // テーブルの表示(デバッグ用)
        /*
        $sql ='SHOW TABLES';
        $result = $pdo -> query($sql);
        foreach ($result as $row){
            echo $row[0];
            echo '<br>';
        }
        echo "<hr>";*/

        // $filename="mission5-1.txt";
        // $copyfile = 'copyfile5-1.txt';
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

                    /*
                    $count = 1;
                    if(file_exists($filename)){
                        $items = file($filename, FILE_IGNORE_NEW_LINES);
                        // 投稿番号の付与
                        if (isset($items[0])){
                            $split_enditem = explode('<>', end($items));
                            $end_num = (int)$split_enditem[0];
                            $count = $end_num + 1;
                        } else {
                            $count = $count;
                        }
                    } */

                    // 書き込み処理:追記
                    if ($name!='' && $comment!='' && $postpass!='') {
                        /*
                        $fp = fopen($filename,"a");
                        $add_value = $count . '<>' . $name . '<>' . $comment . '<>' . $date . '<>' . $postpass . PHP_EOL;
                        fwrite($fp, $add_value);
                        fclose($fp);
                        */

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
                    $existpass = 'SELECT password FROM mission5 WHERE id=:id ';

                    if ($existpass == $editpass) {
                        $sql = 'UPDATE mission5 SET name=:name,comment=:comment WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                    }

                    /*

                    // 処理開始
                    if (file_exists($filename)){
                        // file_open
                        $fp = fopen($filename,"a");

                        $copyfile = 'copyfile5-1.txt';

                        // $filenameを$copyfileにコピー
                        copy($filename, $copyfile);
    
                        // $fpを空にする
                        flock($fp, LOCK_EX);
                        ftruncate($fp,0);
                        flock($fp, LOCK_UN);

                        // $copyfileの中身を読み込んでいく
                        $items = file($copyfile, FILE_IGNORE_NEW_LINES);
                        foreach($items as $item){
                            if (isset($item)) {
                                $split_item = explode('<>', $item);
                                // 投稿番号を取得
                                $post_num = $split_item[0];
                                if ($post_num == $editNo && $split_item[4] == $editpass) {
                                    // 編集したいnameとcommentの値を取得
                                    $edit_number2 = $editNo;
                                    $edit_name2 = $name;
                                    $edit_comment2 = $comment;
                                    
                                    $change_data = $edit_number2 . '<>' . $edit_name2 . '<>' . $edit_comment2 . 
                                    '<>' . $split_item[3] . '<>' . $split_item[4];
                                    file_put_contents($filename, $change_data . PHP_EOL, FILE_APPEND);
                                } else {
                                    file_put_contents($filename, $item . PHP_EOL, FILE_APPEND);
                                }
                            } 
                        }
                        // file_close
                        fclose($fp);
                    }*/


                }
            // 削除フォームの処理
            } elseif ($sendform==='deleteform' && !empty($_POST['delete_pass'] && !empty($_POST['delete_num']))){
                $delete_num = $_POST["delete_num"];
                $deletepass = $_POST["delete_pass"];

                $id = $delete_num;

                $existpass = 'SELECT password FROM mission5 WHERE id=$id ';
                if ($deletepass === $existpass) {
                    $sql = 'delete from mission5 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }

                /*
                // 処理開始
                if(file_exists($filename)){
                    // file_open
                    $fp = fopen($filename,"a"); 

                    // $filenameを$copyfileにコピー
                    copy($filename, $copyfile);

                    // $fpを空にする
                    flock($fp, LOCK_EX);
                    ftruncate($fp,0);
                    flock($fp, LOCK_UN);

                    // $copyfileの中身を読み込んでいく
                    $items = file($copyfile, FILE_IGNORE_NEW_LINES);
                    foreach($items as $item){
                        if (isset($item)) {
                            $split_item = explode('<>', $item);
                            $post_num = $split_item[0];
                            if ($post_num == $delete_num && $deletepass === $split_item[4]) {
                                // echo
                            } else {
                                file_put_contents($filename, $item . PHP_EOL, FILE_APPEND);
                            }
                        } 
                    }
                    // file_close
                    fclose($fp);
                } else {
                    // echo 'no file'; //fileがない場合にここに入る(確認済み)
                }
                */
            //編集フォーム
            } elseif ($sendform==='editform' && !empty($_POST['edit_num']) && !empty($_POST['edit_pass'])) {
                $edit_num = $_POST['edit_num'];
                $editpass = $_POST['edit_pass'];
                $id = $edit_num;

                $existpass = 'SELECT password FROM mission5 WHERE id=$id ';
                if ($editpass === $existpass) {
                    // 編集したいnameとcommentの値を取得
                    $edit_number = $id;
                    $edit_name = 'SELECT name FROM mission5 WHERE id=$id ';
                    $edit_comment = 'SELECT comment FROM mission5 WHERE id=$id ';
                }

                
                /*
                // 処理開始
                if (file_exists($filename)){
                    // file_open
                    $fp = fopen($filename,"r"); 
                    // $copyfileの中身を読み込んでいく
                    $items = file($filename, FILE_IGNORE_NEW_LINES);
                    foreach($items as $item){
                        if (isset($item)) {
                            $split_item = explode('<>', $item);
                            // 投稿番号を取得
                            $post_num = $split_item[0];
                            if ((int)$post_num === (int)$edit_num && $editpass === $split_item[4]) {
                                // 編集したいnameとcommentの値を取得
                                $edit_number = $split_item[0];
                                $edit_name = $split_item[1];
                                $edit_comment = $split_item[2];
                            }
                        } 
                    }
                    // file_close
                    fclose($fp);
                } else {
                    // echo 'no file'; //fileがない場合にここに入る(確認済み)
                }
                */
            } else {
                //  formが追加されたらここに処理を書く
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
        password:  <input type="text" name="post_pass" placeholder="パスワード"><br>
        <input type="hidden" name="editNo" value="<?php echo $edit_number; ?>">
        <input type="submit" name="submit" value="送信">
        <input type="hidden" name="formtype" value="postform" checked="checked">
    </form>
    <!--削除フォーム-->
    <form action="" method="post" name="deleteform">
        【　削除フォーム　】<br>
        削除対象番号:<input type="text" name="delete_num" placeholder="投稿番号"><br>
        password:  <input type="text" name="delete_pass" placeholder="パスワード"><br>
        <!-- <input type="text" name="comment" placeholder="コメント"><br> -->
        <input type="submit" name="delete_submit" value="削除">
        <input type="hidden" name="formtype" value="deleteform" checked="checked">
    </form>
    <!-- 編集番号指定用フォーム -->
    <form action="" method="post" name="editform">
        【　編集番号指定用フォーム　】<br>
        編集対象番号:<input type="text" name="edit_num" placeholder="編集番号"><br>
        <!-- <input type="text" name="comment" placeholder="コメント"><br> -->
        password:  <input type="text" name="edit_pass" placeholder="パスワード"><br>
        <input type="submit" name="edit_submit" value="編集">
        <input type="hidden" name="formtype" value="editform" checked="checked">
    </form>


    <?php
        // ブラウザへの表示
        // DB設定
        $dsn = 'mysql:dbname=xxxxxxxxxxxxxxxdb;host=localhost';
        $user = 'xxxxxxxxxxxxxxx';
        $password = 'xxxxxxxxxxxxxxx';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            // echo $row['id'].',';
            // echo $row['name'].',';
            // echo $row['comment'].'<br>';
            $date = SELECT DATE_FORMAT($row['datetime'], '%Y/%m/%d %k:%i:%s');
            echo $row['id'].' '. $row['name'].' '. $row['comment'].' '. $date. '<br>';
        echo "<hr>";
        }
        /*
        if(file_exists($filename)){
            $items = file($filename, FILE_IGNORE_NEW_LINES);
            foreach($items as $item){
                if (isset($item)){
                    $result = explode('<>', $item);
                    echo $result[0] .' '. $result[1] .' '. $result[2] .' '. $result[3] . '<br>';
                } else {
                    //値がなければなにもしない
                }
            }
        }*/
    ?>
</body>
</html>