<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_2-02</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="str" placeholder="コメント">
        <input type="submit" name="submit">
    </form>
    <?php
        $str = $_POST["str"];
        $str = '';
        $filename="mission_2-2.txt";
        // $str is't null
        if (isset($str)){
            $str2 = $str . PHP_EOL;
        }
        $fp = fopen($filename,"w+");
        flock($fp, LOCK_EX);
        //2番目の引数:ファイルサイズを0にして空にする
        ftruncate($fp,0);
        flock($fp, LOCK_UN);
        // 値の書き込み
        fwrite($fp, $str2);
        fclose($fp);

        // echo "書き込み成功！<>";
        if(file_exists($filename)){
            $contents = file($filename,FILE_IGNORE_NEW_LINES);
            foreach($contents as $content){
                if ($content==''){
                    // echo '';
                } else {
                    if ($content=='Hello World'){
                        echo '完成！<br>';
                    } else {
                        echo 'おめでとう！<br>';
                    }
                }
            }
        }
        // 判定をしてからファイルを閉じる
        // fclose($fp);
    ?>
</body>
</html>