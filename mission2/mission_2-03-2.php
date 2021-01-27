<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_2-03</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="str" placeholder="コメント">
        <input type="submit" name="submit">
    </form>
    <?php
        $str = $_POST["str"];
        $filename="mission_2-3.txt";
        // $str is't null
        if ($str!=''){
            $str2 = $str . PHP_EOL;
        }
        $fp = fopen($filename,"a");
        fwrite($fp, $str2);
        fclose($fp);
        
        if(file_exists($filename)){
            $contents = file($filename,FILE_IGNORE_NEW_LINES);
            foreach($contents as $content){
                if ($content=='Hello World'){
                    echo $content .' 完成！<br>';
                } else {
                    echo $content .' おめでとう！<br>';
                }
            }
        }
    ?>
</body>
</html>