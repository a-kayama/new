
<?php
session_start();
// print_r($_POST);

require_once("php.Original/config/config.php");
require_once("php.Original/model/User.php");

//ログアウト処理
if(isset($_GET['logout'])) {
  //セッション情報を破棄する
  $_SESSION['User'] = array();
}
//新規登録、ログインページを経由していなかったら
if(empty($_SESSION["User"])){
  header("Location:login.php");
  exit;
}

//サニタイジング
function h($post){
  return htmlspecialchars($post,ENT_QUOTES,'UTF-8');
}

  //データベース接続
try{
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDB();

  //バリデーションに引っかからなければ登録
 if($_POST) {
   $message = $user->validate3($_POST);
   if(empty($message['name']) && empty($message['title']) && empty($message['category']) && empty($message['tro_comment'])){
   $user->trouble($_POST);
   header('Location:tro_complete.php');

   exit();
     }
   }

  } catch(PDOException $e) {
    echo "エラー:".$e->getMessage()."<br>";
  }
?>


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>保育士の為のアイデアサイト'Happily Work'</title>
    <link rel="stylesheet" type="text/css" href="php.Original/css/tro_post.css">
    <link rel="stylesheet" type="text/css" href="php.Original/css/base.css">
  </head>
  <body>
<div id="wrapper">

  <script src="jquery.js"></script>
  <script src="h-navi.js"></script>

    <?php
    require ("g-navi.html");
    ?>

    <h3>相談内容を入力してください</h3>
    <?php if(isset($message["name"])) echo "<p class='red'>".$message["name"]."</p>" ?>
    <?php if(isset($message["title"])) echo "<p class='red'>".$message["title"]."</p>" ?>
    <?php if(isset($message["category"])) echo "<p class='red'>".$message["category"]."</p>" ?>
    <?php if(isset($message["tro_comment"])) echo "<p class='red'>".$message["tro_comment"]."</p>" ?>

    <div id="toukou">
    <form action=""  method="post">
      <p>お名前を入力してください(ニックネーム可)</p>
      <input id="name-t" type="text" name="name"  value="">
      <p>タイトルを入力してください</p>
      <input id="title" type="text" name="title"  value="">


      <p>カテゴリーを選択してください</p>
      <select name="category" value="">
        <option value="食事">食事</option>
        <option value="製作">製作</option>
        <option value="生活習慣">生活習慣</option>
        <option value="あそび">あそび</option>
        <option value="その他">その他</option>
      </select>

      <p>相談の内容</p>
      <textarea id="tro_comment" name="tro_comment"></textarea>
      <input id="t-btn" type="submit" onclick = "if(!confirm('相談内容を投稿します。宜しいでしょうか？')) return false;" value="投稿する">
      <input type="hidden" name="user_id" value="<?php  echo $_SESSION['User']['id'] ?>">
    </form>
  </div>

    <?php
    require ("footer.html");
    ?>

</div>
  </body>
</html>
