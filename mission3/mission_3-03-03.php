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
        // test_value → 分岐は確認済み
        // $_POST['formtype'] = 'postform';
        $_POST['formtype'] = 'deleteform';
        $filename="mission3-3-test.txt";
        // hidden_valueがあるかの判定
        if (isset($_POST['formtype'])) {
            $sendform = $_POST['formtype'];
            // 投稿フォームの処理
            if ($sendform=='postform') {

                // 変数定義
                $name = $_POST["name"];
                $comment = $_POST["comment"];   
                // test_value in postform
                $name = 'neko';
                $comment = 'god';

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
                } //変数定義終了

                // var_dump(isset($name)); 
                // var_dump(isset($comment));
                // $name and commentがある場合ファイルに追記で書き込み
                if ($name!='' && $comment!='') {
                    // echo 'OK';
                    $fp = fopen($filename,"a");
                    $add_value = $count . '<>' . $name . '<>' . $comment . '<>' . $date . PHP_EOL;
                    fwrite($fp, $add_value);
                    fclose($fp);
                } else {
                    // name or commentがなければerror
                    echo 'error';
                }
            // 削除フォームの処理
            } elseif ($sendform=='deleteform') {
                // echo 'deleteform'; // test
                $delete_num = $_POST["delete_num"];
                $delete_num = '4'; // test_value in deleteform

                if(file_exists($filename)){
                    $fp = fopen($filename,"a");
                    $copyfile = 'copyfile.txt';
                    if (copy($filename, $copyfile)) {
                        // echo 'copy success';
                    } else {
                        // echo 'copy failure';
                    }
                    // ファイルを空にする
                    flock($fp, LOCK_EX);
                    //2番目の引数でファイルサイズを0にして空にする
                    ftruncate($fp,0);
                    flock($fp, LOCK_UN);
                    // fclose($fp);
                    $items = file($copyfile, FILE_IGNORE_NEW_LINES);
                    // $add_items = array();
                    foreach($items as $item){
                        if (isset($item)) {
                            $split_item = explode('<>', $item);
                            $post_num = $split_item[0];
                            if ($post_num != $delete_num) {
                                // echo 'postnum '. $post_num . ' delete_num ' . $delete_num . '<br>';
                                // $add_items[] = $item;
                                // unset($items[(int)$delete_num+1]);
                                // $contents = file_get_contents($filename);
                                file_put_contents($filename, $item . PHP_EOL, FILE_APPEND);
                            }
                        } else {
                            // $itemの判定False
                            echo 'error';
                        }
                    }
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
                    // echo '';
                }
            }
        }
    ?>
</body>
</html>