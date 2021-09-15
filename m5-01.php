<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>

    <?php
          //DB接続設定
          $dsn='mysql:dbname=tb230403db;host=localhost';
          $user='tb-230403';
          $password='x6Rsn3d9yV';
          $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

          //テーブル作成
          $sql="CREATE TABLE IF NOT EXISTS mission_5"
          ." ("
          ."id INT AUTO_INCREMENT PRIMARY KEY,"
          ."name char(32),"
          ."comment TEXT,"
          ."date TEXT,"
          ."password TEXT"
          .");";
          $stmt=$pdo->query($sql);

          //POSTでの受け取り
          if(!empty($_POST["str1"]) && !empty($_POST["str2"])){
              $str1=$_POST["str1"];
              $str2=$_POST["str2"];
              $password1=$_POST["password1"];
              if(!empty($_POST["str3"])){
                  $str3=$_POST["str3"];
              }
          }elseif(!empty($_POST["delete"]) && !empty($_POST["password2"])){
              $delete=$_POST["delete"];
              $password2=$_POST["password2"];
          }elseif(!empty($_POST["edit"]) && !empty($_POST["password3"])){
              $edit=$_POST["edit"];
              $password3=$_POST["password3"];
          }

          //新規投稿処理(データ入力)
          if(!empty($_POST["str1"]) && !empty($_POST["str2"]) && empty($_POST["str3"])){
              $sql=$pdo->prepare("INSERT INTO mission_5(name,comment,date,password) VALUES(:name,:comment,:date,:password)");
              $sql->bindParam(':name',$name,PDO::PARAM_STR);
              $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
              $sql->bindParam(':date',$date,PDO::PARAM_STR);
              $sql->bindParam(':password',$password,PDO::PARAM_STR);
              $name=$str1;
              $comment=$str2;
              $date=date("Y/m/d H:i:s");
              $password=$password1;
              $sql->execute();
          
          //削除処理(データレコード削除)
          }elseif(!empty($_POST["delete"]) && !empty($_POST["password2"])){
              $id=$delete;
              $sql='SELECT * FROM mission_5 WHERE id=:id';
              $stmt=$pdo->prepare($sql);
              $stmt->bindParam(':id',$id,PDO::PARAM_INT);
              $stmt->execute();
              $results=$stmt->fetchAll();
              foreach($results as $row){
                  if($delete==$row[0] && $password2==$row[4]){
                      $sql='delete from mission_5 where id=:id';
                      $stmt=$pdo->prepare($sql);
                      $stmt->bindParam(':id',$id,PDO::PARAM_INT);
                      $stmt->execute();
                  }elseif($delete==$row[0] && $password2!=$row[4]){
                      echo "パスワードが違います。";
                  }
              }
  
          //編集選択処理(データレコード編集)
          }elseif(!empty($_POST["edit"]) && !empty($_POST["password3"])){
              $id=$edit;
              $sql='SELECT * FROM mission_5 WHERE id=:id';
              $stmt=$pdo->prepare($sql);
              $stmt->bindParam(':id',$id,PDO::PARAM_INT);
              $stmt->execute();
              $results=$stmt->fetchAll();
              foreach($results as $row){
                  if($edit==$row[0] && $password3==$row[4]){
                      $editnumber=$row[0];
                      $editname=$row[1];
                      $editcomment=$row[2];
                      $editpassword=$row[4];
                  }elseif($edit==$row[0] && $password3!=$row[4]){
                      echo "パスワードが違います。";
                  }
              }

          //編集実行処理(データレコード編集2)
          }elseif(!empty($_POST["str1"]) && !empty($_POST["str2"]) && !empty($_POST["str3"])){
              $id=$str3;
              $sql='SELECT * FROM mission_5 WHERE id=:id';
              $stmt=$pdo->prepare($sql);
              $stmt->bindParam(':id',$id,PDO::PARAM_INT);
              $stmt->execute();
              $results=$stmt->fetchAll();
              foreach($results as $row){
                  if($str3==$row[0]){
                      $name=$str1;
                      $comment=$str2;
                      $date=date("Y/m/d H:i:s");
                      $password=$password1;
                      $sql='UPDATE mission_5 SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
                      $stmt=$pdo->prepare($sql);
                      $stmt->bindParam(':name',$name,PDO::PARAM_STR);
                      $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
                      $stmt->bindParam(':date',$date,PDO::PARAM_STR);
                      $stmt->bindParam(':password',$password,PDO::PARAM_STR);
                      $stmt->bindParam(':id',$id,PDO::PARAM_INT);
                      $stmt->execute();
                  }
              }
          }
    ?>

    <form action=""method="post">
    <input type="text" name="str1" value="<?php if(isset($editname)){echo $editname;}?>" placeholder="名前"><br>
    <input type="text" name="str2" value="<?php if(isset($editcomment)){echo $editcomment;}?>" placeholder="コメント"><br>
    <input type="hidden" name="str3" value="<?php if(isset($editnumber)){echo $editnumber;}?>">
    <input type="text" name="password1" value="<?php if(isset($editpassword)){echo $editpassword;}?>" placeholder="パスワード">
    <input type="submit" name="submit" value="送信"><br>
    <br>
    <input type="number" name="delete" placeholder="削除対象番号"><br>
    <input type="text" name="password2" placeholder="パスワード">
    <input type="submit" name="submit" value="削除"><br>
    <br>
    <input type="number" name="edit" placeholder="編集対象番号"><br>
    <input type="text" name="password3" placeholder="パスワード">
    <input type="submit" name="submit" value="編集">
    <br>
    </form>
    
    <?php
          //表示処理(データレコード抽出、処理)
          echo "<br>";
          $sql='SELECT * FROM mission_5';
          $stmt=$pdo->query($sql);
          $results=$stmt->fetchAll();
          foreach($results as $row){
              for($i=0;$i<count($row)-6;$i++){
                echo $row[$i]." ";
              }
              echo '<br>';
          }
          
    ?>

</body>
</html>