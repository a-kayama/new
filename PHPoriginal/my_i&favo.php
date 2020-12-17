
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
//ログインしていた場合
  if(isset($_SESSION['User'])) {
    //参照処理
    $idea = $user->myidea($_SESSION['User']['id']);
    $favo = $user->favorite($_SESSION['User']['id']);

    // print_r($favo);
  //削除処理
  if(isset($_GET['delete'])) {
    $user->myideadel($_GET['delete']);
  //参照処理
    $idea = $user->myidea($_SESSION['User']['id']);
  } elseif (isset($_GET['update'])) {
    //参照処理(選択された1件のみ抜き出して表示)
    $result['editing'] = $user->findidea($_GET['update']);
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
    <link rel="stylesheet" type="text/css" href="php.Original/css/my_i&favo.css">
    <link rel="stylesheet" type="text/css" href="php.Original/css/base.css">
    <script type="text/javascript" src="jquery.js"></script>
    <script>

      $(function(){
      $(".favorite_btn").click(function() {
        var user_id = <?php echo $_SESSION["User"]["id"]; ?>;
        var post_id = $(this).attr('id');
        // alert(post_id);

        if($(this).hasClass("on")){
          $(this).removeClass("on");
          $(this).text("♡Cancel");
          $(this).addClass("off");
          $.ajax({
            url:'php.Original/model/Ajax.php',
            type:'POST',
            // dataType:'json',
            data:{
              "user_id" : user_id,
              "post_id" : post_id
            }
          }).done(function(data){
            alert("登録しました");
             location.reload();
          }).fail(function(data){
            alert("失敗");
          });

        }else{
          $(this).removeClass("off");
          $(this).addClass("on");
          $(this).text("♥Favorite!");
          $.ajax({
            url:'php.Original/model/Ajax2.php',
            type:'POST',
            data:{
              "user_id" : user_id,
              "post_id" : post_id
            },
            // dataType:'json'
          }).done(function(data){
            alert("解除しました");
             location.reload();
          }).fail(function(data){
            alert("失敗");
          });
        }
      });
          });
    </script>


  </head>
  <body>

    <script src="jquery.js"></script>
    <script src="h-navi.js"></script>

    <div id="wrapper">
    <?php
    require ("g-navi.html");
    ?>
    <!-- updateキーがあった場合のみ表示 -->
    <?php if(isset($_GET['update'])): ?>
      <h3>アイデア編集</h3>
      <p id="upd">※編集内容をご確認の上、編集ボタンをクリックしてください。</p>

      <div id="toukou">
        <form action="my_icomplete.php" enctype="multipart/form-data" method="post">
          <input type="hidden" type="text" name="user_id" value="">
          <input type="hidden" name="id" value="<?php if(isset($result['editing'])) echo $result['editing']['id']; ?>">
          <p>タイトルを入力してください</p>
          <input id="title" type="text" name="title"  value="<?php if(isset($result['editing'])) echo $result['editing']['title']; ?>">

          <p>何歳児向けのアイデアですか？</p>
          <div id="center">
            <!-- <input type="hidden" name="age" value="<?php if(isset($result['editing'])) echo $result['editing']['age_id']; ?>"> -->
            <input id="b-zero" type="radio" name="age" value="0">
            <label for="b-zero">0歳児</label>
            <input id="b-one" type="radio" name="age" value="1">
            <label for="b-one">1歳児</label>
            <input id="b-two" type="radio" name="age" value="2">
            <label for="b-two">2歳児</label>
          </div>

          <p>カテゴリーを選択してください</p>
          <!-- <input type="hidden" name="category" value="<?php if(isset($result['editing'])) echo $result['editing']['category_id']; ?>"> -->
          <select id="ctg" name="category">
            <option value="1">手作りおもちゃ</option>
            <option value="2">製作</option>
            <option value="3">絵本</option>
          </select>

          <p>画像ファイルを選択してください</p>
          <input id="file" type="file" name="image" accept="image/*" multiple value="">

          <p>アイデアの内容</p>
          <textarea id="idea_comment" name="idea_comment" placeholder="作り方や、子どもたちの反応などご自由にご記入ください。"><?php if(isset($result['editing'])) echo $result['editing']['comment']; ?></textarea>
          <input type="hidden" name="user_id" value="<?php echo $_SESSION['User']['id'] ?>">
          <input id="t-btn" type="submit" onclick = "if(!confirm('アイデアを編集します。宜しいでしょうか？')) return false;" value="編集する">
        </form>
      </div>

<!-- 通常表示されるページ     -->
  <?php else: ?>
   <div id="my_iarea">
    <img id="tro_logo" src="php.Original/image/my_i.logo.png" alt="myアイデアロゴ">
   <div class="idea-box">
    <?php foreach ($idea as $row): ?>
      <table id="myidea">
       <tr><th>タイトル</th></tr>
       <tr><td class="ct"><?php echo $row['title'] ?></td></tr>
       <tr><th>年齢</th></tr>
       <tr><td class="ct">
         <?php
          if($row['age_id'] =="0") {
            echo "0歳児";
          } elseif ($row['age_id'] =="1") {
            echo "1歳児";
          } else {
            echo "2歳児";
          }
          ?>
       </td></tr>
       <tr><th>カテゴリー</th></tr>
       <tr><td class="ct">
         <?php
           if($row['category_id'] =="1") {
             echo "手作りおもちゃ" ;
           } elseif ($row['category_id'] =="2") {
             echo "製作" ;
           } else {
             echo "絵本" ;
           }
           ?>
       </td></tr>
       <tr><th>写真</th></tr>
       <tr><td class="ct"><img id ="photo" src="php.Original/css/img/<?php echo $row['image']; ?>" ></td></tr>
       <tr><th>アイデア内容</th></tr>
       <tr><td class="left-c"><?php echo $row['comment'] ?></td></tr>
       <tr><td>
         <a href = "?update=<?=$row['id']?>">&emsp;&emsp;編集&emsp;</a>
         <a href = "?delete=<?=$row['id']?>" onclick = "if(!confirm('投稿したアイデアを削除します。宜しいでしょうか？')) return false;">|&emsp; 削除</a>
       </tr></td>

      </table>
     <?php endforeach; ?>
    </div>
   </div>
   <!-- お気に入りページ -->
   <div id="favo_area">
     <img id="tro_logo" src="php.Original/image/favo.logo.png" alt="お気に入りロゴ">
     <div class="idea-box">
     <?php foreach ($favo as $row): ?>
       <table id="myidea">
        <tr><th>タイトル</th></tr>
        <tr><td class="ct"><?php echo $row['title'] ?></td></tr>
        <tr><th>年齢</th></tr>
        <tr><td class="ct">
          <?php
           if($row['age_id'] =="0") {
             echo "0歳児";
           } elseif ($row['age_id'] =="1") {
             echo "1歳児";
           } else {
             echo "2歳児";
           }
           ?>
        </td></tr>
        <tr><th>カテゴリー</th></tr>
        <tr><td class="ct">
          <?php
            if($row['category_id'] =="1") {
              echo "手作りおもちゃ" ;
            } elseif ($row['category_id'] =="2") {
              echo "製作" ;
            } else {
              echo "絵本" ;
            }
            ?>
        </td></tr>
        <tr><th>写真</th></tr>
        <tr><td class="ct"><img id ="photo" src="php.Original/css/img/<?php echo $row['image']; ?>" ></td></tr>
        <tr><th>アイデア内容</th></tr>
        <tr><td class="left-c"><?php echo $row['comment'] ?></td></tr>
        <tr><td>
        <button type="button" id ="<?php echo $row['id'] ?>" name="favorite" class="favorite_btn">
          ♡Cancel
        </button>
        </tr></td>


       </table>
      <?php endforeach; ?>
    </div>
   </div>
  <?php endif; ?>

    <?php
    require ("footer.html");
    ?>
    </div>

  </body>
</html>
