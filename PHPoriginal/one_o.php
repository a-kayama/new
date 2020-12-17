
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

  //データベース接続
try{
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDB();

  //参照処理
  $result = $user->one_o();
  foreach ($result as $row) {
    $post_id = $row['id'];
    // print_r($post_id);
  }

  //お気に入りの重複チェック
  $check = $user->favoriteAdd($_SESSION['User']['id'],$post_id);


  } catch(PDOException $e) {
    echo "エラー:".$e->getMessage()."<br>";
  }
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>保育士の為のアイデアサイト'Happily Work'</title>
    <link rel="stylesheet" type="text/css" href="php.Original/css/agecategory.css">
    <link rel="stylesheet" type="text/css" href="php.Original/css/base.css">
    <script type="text/javascript" src="jquery.js"></script>
  </head>
  <body>

    <script src="jquery.js"></script>
    <script src="h-navi.js"></script>

    <div id="wrapper">
    <?php
    require ("g-navi.html");
    ?>

    <img id="tro_logo" src="php.Original/image/1-omo-logo.jpg" alt="1歳児おもちゃロゴ">

    <div class="idea-box">
    <?php foreach ($result as $row): ?>
      <!-- <?php print_r($result) ?> -->

      <table id="idea-1">
       <tr><th>タイトル</th></tr>
       <tr><td class="ct"><?php echo $row['title'] ?></td></tr>
       <tr><th>対象年齢</th></tr>
       <tr><td class="ct"><?php  if($row['age_id'] =="1") { echo "1歳児" ;}?></td></tr>
       <tr><th>カテゴリー</th></tr>
       <tr><td class="ct"><?php  if($row['category_id'] =="1") { echo "手作りおもちゃ" ;}?></td></tr>
       <tr><th>写真</th></tr>
       <tr><td class="ct"><img id ="photo" src="php.Original/css/img/<?php echo $row['image']; ?>" ></td></tr>
       <tr><th>コメント</th></tr>
       <tr><td class="left-c"><?php echo $row['comment'] ?></td></tr>
       <tr><td>
         <input type="hidden" name="post_id" class="post_id">
         <button type="button" id ="<?php echo $row['id'] ?>" name="favorite" class="favorite_btn">
         <?php if(!$user->favoriteAdd($_SESSION['User']['id'],$row['id'])): ?>
           ♡Cancel
         <?php else: ?>
           ♥Favorite!
         <?php endif; ?>
       </button>
       </tr></td>
     </table>
    <?php endforeach; ?>

   </div>

    <?php
    require ("footer.html");
    ?>
  </div>

  <script>

    $(function(){
    $(".favorite_btn").click(function() {
      var user_id = <?php echo $_SESSION["User"]["id"]; ?>;
      var post_id = $(this).attr('id');
      // alert(post_id);

      if($(this).hasClass("on")){
        $(this).removeClass("on");
        $(this).text("♥Favorite!");
        $(this).addClass("off");
        $.ajax({
          url:'php.Original/model/Ajax2.php',
          type:'POST',
          data:{
            "user_id" : user_id,
            "post_id" : post_id
          },
        }).done(function(data){
          alert("解除しました");
        }).fail(function(data){
          alert("失敗");
        });

      }else{
        $(this).removeClass("off");
        $(this).addClass("on");
        $(this).text("♡Cancel");
        $.ajax({
          url:'php.Original/model/Ajax.php',
          type:'POST',
          data:{
            "user_id" : user_id,
            "post_id" : post_id
          }
        }).done(function(data){
          alert("登録しました");
        }).fail(function(data){
          alert("失敗");
        });

      }
    });
        });
  </script>

  </body>
</html>
