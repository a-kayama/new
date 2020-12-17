
<?php
session_start();
// print_r($_SESSION['User']);
require_once("php.Original/config/config.php");
require_once("php.Original/model/User.php");


//サニタイジング
function h($post){
  return htmlspecialchars($post,ENT_QUOTES,'UTF-8');
}
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

//データベース接続
try{
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDB();


//コメント登録処理
  if($_POST) {
  //入力チェック
    $message = $user->validate4($_POST);
  //入力チェックに引っかからなければ完了画面に遷移
    if(empty($message['name']) && empty($message['comment'])){
    $user->comment($_POST);
    header('Location:cm_complete.php');
    exit();
      }
    }
  //コメントフォーム表示
  if(isset($_GET['edit'])) {
    //参照処理(選択された1件のみ抜き出して表示)
    $result['trouble'] = $user->findById($_GET['edit']);

  } elseif(isset($_GET['com'])) {
    //参照処理(選択された1件のみ抜き出して表示)
    $result['trouble'] = $user->findById($_GET['com']);
    //コメントキーがあったらコメントを表示
    $result2 = $user->commentAll($_GET['com']);



  } else {
    //全て参照
    $result = $user->troubleAll();
    //コメント数をカウント
    // $result3 = $user->countcom();
    // print_r($result3);
  }

  //削除キーがあったら削除処理
  if(isset($_GET['del'])) {
    $user->troubledel($_GET['del']);
  //全て参照
    $result = $user->troubleAll();
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
    <link rel="stylesheet" type="text/css" href="php.Original/css/trouble.css">
    <link rel="stylesheet" type="text/css" href="php.Original/css/base.css">
  </head>
  <body>
   <div id="wrapper">

     <script src="jquery.js"></script>
     <script src="h-navi.js"></script>

    <?php
    require ("g-navi.html");
    ?>

  <div id="image">
    <img id="tro_logo" src="php.Original/image/trouble.jpg" alt="お悩み相談室">
    <a href="tro_post.php"><img id="post_logo" src="php.Original/image/postlogo.png" alt="投稿する"></a>
  </div>
  <div class="big-box">
    <div class="tro-box">
  <?php foreach ($result as $row): ?>
    <table id="trouble">
     <tr><td><?php echo h($row['create_at']) ?></td></tr>
     <tr><th>お名前(ニックネーム可)</th></tr>
     <tr><td><?php echo h($row['name']) ?></td></tr>
     <tr><th>タイトル</th></tr>
     <tr><td><?php echo h($row['title']) ?></td></tr>
     <tr><th>カテゴリー</th></tr>
     <tr><td><?php echo h($row['category']) ?></td></tr>
     <tr><th>お悩み内容</th></tr>
     <tr><td class="last"><?php echo h($row['trouble']) ?></td></tr>
     <tr>
       <td>
         <a href="?edit=<?=$row['id']?> ">コメントする</a>
         <a id="right" href="?com=<?=$row['id']?> ">&ensp;|&emsp;コメント表示</a>
       </form>
       </td>
     </tr>
     <!-- 管理者だった場合のみ表示 -->
      <?php if($_SESSION['User']['role'] === '0'): ?>
     <tr>
       <td>
         <a href = "?del=<?=$row['id']?>" onclick = "if(!confirm('ID:<?=$row['id']?>を削除します。宜しいでしょうか？')) return false;">削除</a>
       </td>
     </tr>
     <?php endif; ?>
     </tr>
 </table>

 <!-- editキーがある場合のみ表示 -->
 <?php  if(isset($_GET['edit'])): ?>
   <div id="com-color">
   <h3><?php echo $row['name'] ?>さんへのコメント入力</h3>
   <form id="advice" action="" method="post">
     <?php if(isset($message["name"])) echo "<p class='red'>".$message["name"]."</p>" ?>
     <?php if(isset($message["comment"])) echo "<p class='red'>".$message["comment"]."</p>" ?>

     <dl>
       <p>お名前(ニックネーム可):</p>
       <dt><input id="name" type="text" name="name" value=""></dt>
       <p>コメント:</p>
       <dt><textarea id="comment" type="text" name="comment"></textarea></dt>
     </dl>
     <input id="c-btn" type ="submit" name ="c-btn" onclick = "if(!confirm('コメントを送信します。宜しいでしょうか？')) return false;" value="送信">
     <input type="hidden" name="user_id" value="<?php  echo $_SESSION['User']['id'] ?>">
     <input type="hidden" name="trouble_id" value="<?php  echo $result['trouble']['id'] ?>">
     <a id="can" href="trouble.php" >キャンセル</a>
   </form>
 </div>
 <?php endif; ?>
  <?php  endforeach; ?>

  <!-- comキーがある場合のみ表示 -->
  <?php  if(isset($_GET['com'])): ?>
    <?php foreach ($result2 as $row2): ?>
      <table id="comment-t">
        <tr><td><?php echo $row2['create_at'] ?>&emsp;に返信されたコメント</td></tr>
        <tr><th>お名前(ニックネーム可)</th></tr>
        <tr><td><?php echo $row2['name'] ?></td></tr>
        <tr><th>コメント</th></tr>
        <tr><td><?php echo $row2['comment'] ?></td></tr>
      </table>
      <?php  endforeach; ?>
      <?php if($result2 == null): ?>
        <div id="non">
        <p>このお悩みへのコメントはまだ投稿されていません</p>
        <a href="trouble.php"><p>戻る</p></a>
      </div>
      <?php endif; ?>
    <?php endif; ?>
 </div>
</div>

    <?php
    require ("footer.html");
    ?>

  </div>
  </body>
</html>
