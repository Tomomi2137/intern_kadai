<head>
    <meta charset="UTF-8">
    <title>mission_3-03</title>
</head>
<body>
    <!--<p>【投稿フォーム】</p>-->
    <form action="" method="post" name="postform">
        【　投稿フォーム　】<br>
        名前 :　　　　<input type="text" name="name" placeholder="名前"><br>
        コメント :　　<input type="text" name="comment" placeholder="コメント"><br>
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
    <?php
        $filename="mission3-3.txt";
        // hidden_valueがあるかの判定
        if (isset($_POST['formtype'])) {
            $sendform = $_POST['formtype'];
            // 投稿フォームの処理
            if ($sendform=='postform') {

                // 変数定義
                $name = $_POST["name"];
                $comment = $_POST["comment"];   

                date_default_timezone_set('Asia/Tokyo');
                $date = date("Y/m/d H:i:s");

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
            // 削除フォームの処理
            } elseif ($sendform=='deleteform') {
                $delete_num = $_POST["delete_num"];

                // 処理開始
                if(file_exists($filename)){
                    // file_open
                    $fp = fopen($filename,"a"); 
                    $copyfile = 'copyfile.txt';

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
            } else {
                //  formが追加されたらここに処理を書く
            }
        } else {
            // 何もしない
        }
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