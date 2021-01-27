<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-02</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前">
        <input type="text" name="comment" placeholder="コメント">
        <input type="submit" name="submit">
    </form>
    <?php
        $filename="test2.txt";
        // 変数の定義
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        // $name = 'ねこ';
        // $comment = '神';
        $count = 1;
        date_default_timezone_set('Asia/Tokyo');
        $date = date("Y/m/d H:i:s");
        if(file_exists($filename)){
            $items = file($filename, FILE_IGNORE_NEW_LINES);
            // echo end($items). '\n';
            // var_dump(isset($items[0]));
            if (isset($items[0])){
                $split_items = explode('<>', end($items));
                $num = (int)$split_items[0];
                // echo gettype((int)$a[0]);
                $count = $num + 1;
            } else {
                $count = $count;
            }
        }
    
        // 書き込み
        if ($name!='' && $comment!=''){
            $fp = fopen($filename,"a");
            $ans = $count . '<>' . $name . '<>' . $comment . '<>' . $date . PHP_EOL;
            fwrite($fp, $ans);
            fclose($fp);
        }
        // 表示
        if(file_exists($filename)){
            $items = file($filename, FILE_IGNORE_NEW_LINES);
            foreach($items as $item){
                if ($item==''){
                    // echo '';
                } else {
                    $result = explode('<>', $item);
                    echo $result[0] .' '. $result[1] .' '. $result[2] .' '. $result[3] . '<br>';
                }
            }
        }
    ?>
</body>
</html>