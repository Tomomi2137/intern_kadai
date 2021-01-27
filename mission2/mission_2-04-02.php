<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_2-04</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="str" placeholder="名前">
        <input type="submit" name="submit">
    </form>
    <?php
        $str = $_POST["str"];
        $filename="mission_2-4.txt";
        // $str is't null
        if ($str!=''){
            $str2 = $str . PHP_EOL;
            $fp = fopen($filename,"a");
            fwrite($fp, $str2);
            fclose($fp);
        }

        if(file_exists($filename)){
            $names = file($filename, FILE_IGNORE_NEW_LINES);
            foreach($names as $name){
                if (isset($name)){
                    echo 'おめでとう！by ' . $name . '<br>';
                } 
            }
        }
    ?>
</body>
</html>