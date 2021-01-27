<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-01</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前">
        <input type="text" name="comment" placeholder="コメント">
        <input type="submit" name="submit">
    </form>
    <?php
        $filename="mission_3-1.txt";
        // 変数の定義
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $name = 'ねこ';
        $comment = '神';       
        date_default_timezone_set('Asia/Tokyo');
        $date = date("Y/m/d H:i:s");
        // fopenとfcloseはいれておかないとだめっぽい
        $count = 1;
        if(file_exists($filename)){
            $items = file($filename, FILE_IGNORE_NEW_LINES);
            print_r($items);
            // echo end($items). '\n';
            var_dump(isset($items[0]));
            if (isset($items[0])){
                $split_items = explode('<>', end($items));
                print_r(gettype($split_items[0]));
                print_r(gettype((int)$split_items[0]));
                $num = $split_items[0];
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
    ?>
</body>
</html>