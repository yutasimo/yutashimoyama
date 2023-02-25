<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_3-5</title>
    </head>
    <body>
    <?php
        $filename = "mission_3-5.txt";
        $date = date("Y年m月d日 H:i:s");
        if(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["password1"]) && empty($_POST["delete"]) && empty($_POST["edit"]) && empty($_POST["editNO"])) {
            $name = $_POST["name"];
            $str = $_POST["str"];
            $password = $_POST["password1"];
            $count = count(file($filename));
            $count += 1;
            $gyou = $count. "<>". $name."<>". $str. "<>". $date. "<>". $password. "<>";
            $fp = fopen($filename, "a");
            fwrite($fp, $gyou.PHP_EOL);
            fclose($fp);
        }
        if (!empty($_POST["delete"]) && !empty($_POST["password2"])) {
            $delete = $_POST["delete"];
            $lines = file($filename,FILE_IGNORE_NEW_LINES);//ファイル読み込み関数で、ファイルの中身を1行1要素として配列変数に代入する、という準備をする
            $fp = fopen($filename, "w");//ファイルを一度空にする
            
            $passwordOK = false;
            if ($passwordOK) {
                echo "パスワードが違います";
            }   
            foreach($lines as $line) {//ファイルを開き、先ほどの配列の要素数（＝行数）だけループさせる
                $elements = explode("<>",$line);//ループ処理内：区切り文字「<>」で分割して、投稿番号を取得
                $passwordOK = $elements[4];
                $password2 = $_POST["password2"];
                
                if($elements[0] == $delete && $passwordOK == $password2) {//ループ処理内：投稿番号と削除対象番号を比較。等しくない場合は、ファイルに書き込みを行う=つまり、削除番号以外の要素数をfwriteに書き込む
                    $passwordOK = false;
                } elseif($elements[0] != $delete && $passwordOK == $password2) {//ループ処理内：投稿番号と削除対象番号を比較。等しくない場合は、ファイルに書き込みを行う=つまり、削除番号以外の要素数をfwriteに書き込む
                    $passwordOK = false;
                    fwrite($fp, $line.PHP_EOL);
                } elseif ($elements[0] == $delete && $passwordOK != $password2) {
                    $passwordOK = true;
                    fwrite($fp, $line.PHP_EOL);
                } elseif ($elements[0] != $delete && $passwordOK != $password2) {
                    $passwordOK = true;
                    fwrite($fp, $line.PHP_EOL);
                }
            }
            fclose($fp);//ファイルを閉じる
            if ($passwordOK) {
                echo "パスワードが違います";
            } 
            
        } elseif (!empty($_POST["edit"])&& !empty($_POST["password3"])) {
            $edit = $_POST["edit"];
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            $fp = fopen($filename, "w");
            
            foreach($lines as $line) {
                $elements = explode("<>",$line);
                $editnum = $elements[0];//74行目で使うために
                $passwordOK = $elements[4];
                $elements[4] = $_POST["password3"];
                if($editnum == $edit && $passwordOK == $elements[4]) {
                    $editname = $elements[1];//71行目で使うために
                    $editstr = $elements[2];//73行目で使うために
                } 
                fwrite($fp, $line.PHP_EOL);//テキストファイルのlinesを《まったく変えずに》【editの編集選択機能】を実行するために、if関数の外でfwriteの書き込みを行う
            }
            fclose($fp);
            if ($passwordOK != $elements[4]) {//fwrite関数・fclose関数の前に持っていくと、【ブラウザ表示】の際に、ループをしてしまう
                echo "パスワードが違います";
            }
        } elseif (!empty($_POST["editNO"])) {
            $editNO = $_POST["editNO"];
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            $fp = fopen($filename, "w");
            
            foreach($lines as $line) {//※テキストファイルの一行一行をlineでみていくため、$elements[0]がif関数に該当すれば、その【1行丸々】が、対象となる。
                $elements = explode("<>",$line);
                $editnum = $elements[0];
                $passwordOK = $elements[4];
                $elements[4] = $_POST["password1"];
                if($editnum == $editNO && $passwordOK == $elements[4]) {
                    $elements[1] = $_POST["name"];//ブラウザ上に表示されているPOST関数のnameを、$elements[1]に代入する
                    $elements[2] = $_POST["str"];//ブラウザ上に表示されているPOST関数のstrを、$elements[2]に代入する
                    $line = $editnum . "<>" . $elements[1] . "<>" . $elements[2] . "<>" . $date . "<>". $elements[4]. "<>". "\n";//<br>を使うと、テキストファイルに<br>が表示されてしまう。だから\nを使う
                    fwrite($fp, $line);
                } else {//一致しなかったところはそのまま書き込む
                    fwrite($fp,$line.PHP_EOL);
                }
            }
            fclose($fp);
            if ($passwordOK != $elements[4]){//fwrite関数・fclose関数の前に持っていくと、【ブラウザ表示】の際に、ループをしてしまう
                echo "パスワードが違います";
            }
        }
    ?>
    
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"><br>
        <input type="text" name="str" placeholder="コメント" value="<?php if(isset($editstr)) {echo $editstr;} ?>"><br>
        <input type="hidden" name="editNO" placeholder="編集" value="<?php if(isset($editnum)) {echo $edit;} ?>">
        <input type="password"  name="password1" placeholder="パスワード">
        <input type="submit" name="submit" value="送信">
        <br><br>
        <input type="text" name="delete" placeholder="削除対象番号"><br>
        <input type="password" name="password2" placeholder="パスワード">
        <input type="submit" name="submit" value="削除">
        <br><br>
        <input type="text" name="edit" placeholder="編集対象番号" ><br>
        <input type="password" name="password3" placeholder="パスワード">
        <input type="submit" value="編集">
    </form>
            
    <?php
        echo "<br>";
        $filename = "mission_3-5.txt";
           //77行目以降は、【ブラウザに表示させる】ための、コードを書く
        if(file_exists($filename)) {
            $lines = file($filename,FILE_IGNORE_NEW_LINES);//ファイル読み込み関数で、ファイルの中身を1行1要素として配列変数に代入するという準備をする
            foreach($lines as $line) {//ファイルを開き、先ほどの配列の要素数（＝行数）だけループさせる
                $elements = explode("<>",$line);//ループ処理内：区切り文字「<>」で分割して、投稿番号を取得
                    for($i = 0 ; $i < 4 ; $i++) { //explode関数によって分割された$elementsの[1つ目の変数]を出力➡[スペースを出力]➡[2つ目の変数]を出力➡[スペースを出力]
                           echo $elements[$i]. " "; //ループ処理内：上記で取得した値をecho等を用いて表示
                    }
                    echo "<br>";
               }
           }
    ?>
    </body>
</html>