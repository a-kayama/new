<?php
session_start();
// print_r($_SESSION['User']);
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

 if($_POST) {
   //ファイルの取得
   $file = $_FILES['image'];
   $filename = basename($file['name']);
   $tmp_path = $file['tmp_name'];
   $file_err = $file['error'];
   $filesize = $file['size'];
   $upload_dir = 'php.Original/css/img/';
   $save_path = $upload_dir.$filename;
   // print_r($filename);
   //バリデーションに引っかからなければ登録
   $message = $user->validate2($_POST);
   if(empty($message['title']) && empty($message['age']) && empty($message['image']) && empty($message['category']) && empty($message['idea_comment'])){
   $user->idea($_POST, $filename);
   move_uploaded_file($tmp_path, $save_path);
   header('Location:i_post_complete.php');
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
    <link rel="stylesheet" type="text/css" href="php.Original/css/i_post.css">
    <link rel="stylesheet" type="text/css" href="php.Original/css/base.css">
  </head>
  <body>
    <script src="jquery.js"></script>
    <script src="h-navi.js"></script>

<div id="wrapper">

    <?php
    require ("g-navi.html");
    ?>

    <h3>アイデア投稿</h3>

    <?php if(isset($message["title"])) echo "<p class='red'>".$message["title"]."</p>" ?>
    <?php if(isset($message["age"])) echo "<p class='red'>".$message["age"]."</p>" ?>
    <?php if(isset($message["category"])) echo "<p class='red'>".$message["category"]."</p>" ?>
    <?php if(isset($message["image"])) echo "<p class='red'>".$message["image"]."</p>" ?>
    <?php if(isset($message["idea_comment"])) echo "<p class='red'>".$message["idea_comment"]."</p>" ?>

    <div id="toukou">
    <form action="" enctype="multipart/form-data" method="post">
      <input type="hidden" type="text" name="user_id" value="">
      <p>タイトルを入力してください</p>
      <input id="title" type="text" name="title"  value="">

      <p>何歳児向けのアイデアですか？</p>
     <div id="center">
      <input id="b-zero" type="radio" name="age" value="0">
      <label for="b-zero">0歳児</label>
      <input id="b-one" type="radio" name="age" value="1">
      <label for="b-one">1歳児</label>
      <input id="b-two" type="radio" name="age" value="2">
      <label for="b-two">2歳児</label>
     </div>

      <p>カテゴリーを選択してください</p>
      <select name="category">
        <option value="1">手作りおもちゃ</option>
        <option value="2">製作</option>
        <option value="3">絵本</option>
      </select>

      <p>画像ファイルを選択してください</p>
      <input id="file" type="file" name="image" accept="image/*" multiple value="">

      <p>アイデアの内容</p>
      <textarea id="idea_comment" name="idea_comment" placeholder="作り方や、子どもたちの反応などご自由にご記入ください。"></textarea>
      <input type="hidden" name="user_id" value="<?php echo $_SESSION['User']['id'] ?>">
      <input id="t-btn" type="submit" onclick = "if(!confirm('アイデアを投稿します。宜しいでしょうか？')) return false;" value="投稿する">
    </form>
  </div>

    <?php
    require ("footer.html");
    ?>
</div>


</script>
  </body>
</html>
