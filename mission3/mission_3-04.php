<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-04</title>
</head>
<body>
    <?php
        $filename="mission3-4.txt";
        $copyfile = 'copyfile3-4.txt';
        $edit_number = '';
        $edit_name = '';
        $edit_comment = '';
        // hidden_valueがあるかの判定
        if (isset($_POST['formtype'])) {
            $sendform = $_POST['formtype'];

            // 投稿フォームの処理
            if ($sendform==='postform' && !empty($_POST['name']) && !empty($_POST['comment'])) {

                // 変数定義
                $name = $_POST["name"];
                $comment = $_POST["comment"];   
    
                date_default_timezone_set('Asia/Tokyo');
                $date = date("Y/m/d H:i:s");

                // editNoがないときは新規投稿
                if (empty($_POST['editNo'])) {
                    $count = 1;
                    if(file_exists($filename)){
                        $items = file($filename, FILE_IGNORE_NEW_LINES);
                        // 投稿番号の付与
                        if (isset($items[0])){
                            // itemsに含まれる一番最後の行を指定
                            $split_enditem = explode('<>', end($items));
                            // 番号は配列の一番最初.また型がstring型なのでint型にキャスト
                            $end_num = (int)$split_enditem[0];
                            $count = $end_num + 1;
                        } else {
                            // itemsがない最初の処理
                            $count = $count;
                        }
                    } 
                    //変数定義終了

                    // 書き込み処理:追記
                    if ($name!='' && $comment!='') {
                        $fp = fopen($filename,"a");
                        $add_value = $count . '<>' . $name . '<>' . $comment . '<>' . $date . PHP_EOL;
                        fwrite($fp, $add_value);
                        fclose($fp);
                    } 
                } else {
                    // 変数定義
                    $editNo = $_POST['editNo'];

                    // 処理開始
                    if (file_exists($filename)){
                        // file_open
                        $fp = fopen($filename,"a");

                        $copyfile = 'copyfile3-4.txt';

                        // $filenameを$copyfileにコピー
                        copy($filename, $copyfile);
                        // print_r($filename);
    
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
                                if ($post_num == $editNo) {
                                    // 編集したいnameとcommentの値を取得
                                    $edit_number2 = $editNo;
                                    $edit_name2 = $name;
                                    $edit_comment2 = $comment;
                                    
                                    $change_data = $edit_number2 . '<>' . $edit_name2 . '<>' . $edit_comment2 . 
                                    '<>' . $split_item[3];
                                    file_put_contents($filename, $change_data . PHP_EOL, FILE_APPEND);
                                } else {
                                    file_put_contents($filename, $item . PHP_EOL, FILE_APPEND);
                                }
                            } 
                        }
                        // file_close
                        fclose($fp);
                    }
                }
            // 削除フォームの処理
            } elseif ($sendform==='deleteform') {
                // 変数定義
                $delete_num = $_POST["delete_num"];

                // 処理開始
                if(file_exists($filename)){
                    // file_open
                    $fp = fopen($filename,"a"); 
                    $copyfile = 'copyfile3-4.txt';

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
                            // $post_num と $delete_numが一致しなければ書き込み
                            if ($post_num != $delete_num) {
                                file_put_contents($filename, $item . PHP_EOL, FILE_APPEND);
                            }
                        } 
                    }
                    // file_close
                    fclose($fp);
                } else {
                    // echo 'no file'; //fileがない場合にここに入る(確認済み)
                }
            //編集フォーム
            } elseif ($sendform=='editform'&& !empty($_POST['edit_num'])) {
                // 変数定義
                $edit_num = $_POST['edit_num'];

                // 処理開始
                if (file_exists($filename)){
                    // file_open
                    $fp = fopen($filename,"r"); 
                    // $copyfileの中身を読み込んでいく
                    $items = file($filename, FILE_IGNORE_NEW_LINES);
                    // 読み込んだファイルを１行ずつ取得
                    foreach($items as $item){
                        if (isset($item)) {
                            // <>で切り分け
                            $split_item = explode('<>', $item);
                            // 投稿番号を取得
                            $post_num = $split_item[0];
                            if ((int)$post_num === (int)$edit_num) {
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
            } else {
                //  formが追加されたらここに処理を書く
            }
        } else {
            // 何もしない
        }
    ?>
    <!--<p>【投稿フォーム】</p>-->
    <form action="" method="post" name="postform">
        【　投稿フォーム　】<br>
        名前 :　　　　<input type="text" name="name" value="<?php echo $edit_name; ?>"><br>
        コメント :　　<input type="text" name="comment" value="<?php echo $edit_comment; ?>"><br>
        <input type="hidden" name="editNo" value="<?php echo $edit_number; ?>">
        <input type="submit" name="submit" value="送信">
        <input type="hidden" name="formtype" value="postform" checked="checked">
    </form>
    <!--<p>【削除フォーム】</p>-->
    <form action="" method="post" name="deleteform">
        【　削除フォーム　】<br>
        削除対象番号：<input type="text" name="delete_num" placeholder="投稿番号"><br>
        <!-- <input type="text" name="comment" placeholder="コメント"><br> -->
        <input type="submit" name="delete_submit" value="削除">
        <input type="hidden" name="formtype" value="deleteform" checked="checked">
    </form>
    <!-- 編集番号指定用フォーム -->
    <form action="" method="post" name="editform">
        【　編集番号指定用フォーム　】<br>
        編集対象番号：<input type="text" name="edit_num" placeholder="編集番号"><br>
        <!-- <input type="text" name="comment" placeholder="コメント"><br> -->
        <input type="submit" name="edit_submit" value="編集">
        <input type="hidden" name="formtype" value="editform" checked="checked">
    </form>
    <?php
        // ブラウザへの表示
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
        }
    ?>
</body>
</html>
