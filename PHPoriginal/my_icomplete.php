
<?php
session_start();

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

  if($_POST) {
    //ファイルの取得
    $file = $_FILES['image'];
    $filename = basename($file['name']);
    $tmp_path = $file['tmp_name'];
    $file_err = $file['error'];
    $filesize = $file['size'];
    $upload_dir = 'php.Original/css/img/';
    $save_path = $upload_dir.$filename;
    move_uploaded_file($tmp_path, $save_path);

    //バリデーションに引っかからなければ登録
    $message = $user->validate2($_POST);
    if(isset($message['title']) && isset($message['age']) && isset($message['image']) && isset($message['category']) && isset($message['idea_comment'])){
    header('Location:my_i&favo.php');
    exit();
  } else {
    move_uploaded_file($tmp_path, $save_path);
    //編集処理
    $user->update($_POST, $filename);
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
    <link rel="stylesheet" type="text/css" href="php.Original/css/i_post_conplete.css">
    <link rel="stylesheet" type="text/css" href="php.Original/css/base.css">
  </head>
  <body>

    <script src="jquery.js"></script>
    <script src="h-navi.js"></script>

    <div id="wrapper">
    <?php
    require ("g-navi.html");
    ?>
<!-- <?php print_r($_POST) ?> -->
   <h3>Myアイデアの編集が完了しました！</h3>
   <a href="my_i&favo.php"><button id="mypage" name="mypage">Myアイデア一覧へ</button></a>
    <?php
    require ("footer.html");
    ?>
</div>
  </body>
</html>
