
<?php
session_start();
// print_r($_SESSION['User']);
require_once("php.Original/config/config.php");
require_once("php.Original/model/User.php");



//ログアウト処理
if(isset($_GET['logout'])) {
  //セッション情報を破棄する
  $_SESSION['User'] = array();
}

//ログイン画面を経由しているか確認
if(empty($_SESSION['User'])) {
  header('Location:login.php');
  exit();
}
if($_SESSION['User']['role'] === '0') {
  header('Location:role-mypage.php');
  exit();

}
//データベース接続
try{
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDB();

  //削除処理
  if(isset($_GET['del'])) {
    $user->delete($_GET['del']);
  //参照処理
  $result = $user->findAll();
}  else {
  //参照処理
  $result = $user->findAll();
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
    <link rel="stylesheet" type="text/css" href="php.Original/css/mypage.css">
    <link rel="stylesheet" type="text/css" href="php.Original/css/base.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">

  </head>
  <body>

    <div id="wrapper">
    <?php
    require ("g-navi.html");
    ?>

      <h3>あなたの担当クラスは？</h3>
  <div class="box">
    <div class="box1">
      <img id="zeropic" src="php.Original/image/egg.png" alt="たまご">
      <img id="agepic2" src="php.Original/image/zero.jpg" alt="０歳">
    </div>
    <div class="box2">
      <img id="onepic" src="php.Original/image/hiyoko.png" alt="ひよこ">
      <img id="agepic2" src="php.Original/image/one.jpg" alt="１歳">
    </div>
    <div class="box3">
      <img id="twopic" src="php.Original/image/niwatori.png" alt="にわとり">
      <img id="agepic2" src="php.Original/image/two.jpg" alt="２歳">
    </div>
  </div>
<!-- 0歳児カテゴリー -->
<div id="child-area0">
  <img id="agepic3" src="php.Original/image/zero.jpg" alt="０歳">
  <img id="link" src="php.Original/image/categorylink.png" alt="カテゴリーリンク">
  <div id="zero">
    <p id="setumei">左右の矢印をクリックすると<br>アイデアのサンプル写真をご覧頂けます</p>
    <a href="zero_o.php"><p class="mt-100">手作りおもちゃ</p></a>
      <div class="slider">
        <div>
         <img id="category" src="php.Original/image/0omocha1.jpg" alt="０歳おもちゃ１">
       </div>
       <div>
         <img id="category" src="php.Original/image/0omocha3.jpg" alt="０歳おもちゃ２">
       </div>
     </div>
   <a href="zero_s.php"><p>製作</p></a>
   <div class="slider">
     <div>
       <img id="category" src="php.Original/image/0seisaku1.jpg"  alt="０歳製作１">
     </div>
     <div>
       <img id="category" src="php.Original/image/0seisaku2.jpg" alt="０歳製作２">
     </div>
   </div>
   <a href="zero_e.php"><p>絵本</p></a>
   <div class="slider">
     <div>
       <img id="category" src="php.Original/image/0ehon1.jpg" alt="０歳絵本１">
     </div>
     <div>
       <img id="category" src="php.Original/image/0ehon2.jpg" alt="０歳絵本２">
     </div>
   </div>
   <button class=top>ページトップへ</button>
 </div>
</div>
 <!-- 1歳児カテゴリー -->
<section id="child-area1">
   <img id="agepic3" src="php.Original/image/one.jpg" alt="１歳">
   <img id="link" src="php.Original/image/categorylink.png" alt="カテゴリーリンク">
   <div id="one">
    <p id="setumei">左右の矢印をクリックすると<br>アイデアのサンプル写真をご覧頂けます</p>
    <a href="one_o.php"><p>手作りおもちゃ</p></a>
      <div class="slider">
        <div>
         <img id="category" src="php.Original/image/1omocha1.jpg" alt="1歳おもちゃ１">
       </div>
       <div>
         <img id="category" src="php.Original/image/1omocha2.jpg" alt="1歳おもちゃ２">
       </div>
      </div>

    <a href="one_s.php"><p>製作</p></a>
      <div class="slider">
        <div>
         <img id="category" src="php.Original/image/1seisaku1.jpg" alt="1歳製作１">
       </div>
       <div>
         <img id="category" src="php.Original/image/1seisaku2.jpg" alt="1歳製作２">
       </div>
      </div>

    <a href="one_e.php"><p>絵本</p></a>
      <div class="slider">
        <div>
         <img id="category" src="php.Original/image/1ehon1.jpeg" alt="1歳絵本１">
       </div>
       <div>
         <img id="category" src="php.Original/image/1ehon2.jpg" alt="1歳絵本２">
       </div>
      </div>
      <button class=top>ページトップへ</button>
   </div>
 </section>
 <!-- 2歳児カテゴリー -->
<section id="child-area2">
   <img id="agepic3" src="php.Original/image/two.jpg" alt="2歳">
   <img id="link" src="php.Original/image/categorylink.png" alt="カテゴリーリンク">
   <div id="two">
    <p id="setumei">左右の矢印をクリックすると<br>アイデアのサンプル写真をご覧頂けます</p>
    <a href="two_o.php"><p>手作りおもちゃ</p></a>
    <div class="slider">
        <div>
         <img id="category" src="php.Original/image/2omocha3.jpeg" alt="2歳おもちゃ１">
       </div>
       <div>
         <img id="category" src="php.Original/image/2omocha2.jpg" alt="2歳おもちゃ２">
       </div>
      </div>

      <a href="two_s.php"><p>製作</p></a>
      <div class="slider">
          <div>
           <img id="category" src="php.Original/image/2seisaku1.jpg" alt="2歳製作１">
         </div>
         <div>
           <img id="category" src="php.Original/image/2seisaku2.jpg" alt="2歳製作２">
         </div>
        </div>

      <a href="two_e.php"><p>絵本</p></a>
        <div class="slider">
          <div>
           <img id="category" src="php.Original/image/2ehon1.jpg" alt="2歳絵本１">
         </div>
         <div>
           <img id="category" src="php.Original/image/2ehon2.jpg" alt="2歳絵本２">
         </div>
        </div>
        <button class=top>ページトップへ</button>
      </div>
 </section>
 </div>


<?php
require ("footer.html");
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="h-navi.js"></script>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

<script>

$(document).ready(function(){
  $('.slider').bxSlider({
  });
});

$(function(){

var windowWidth = window.innerWidth;
var position = $("#child-area0").offset().top;
var position1 = $("#child-area1").offset().top;
var position2 = $("#child-area2").offset().top;
var toppage = $("#wrapper").offset().top;


  $('#zeropic').on('click',function(){
    $("html,body").animate({
      scrollTop:position
    });
    return false;
  });

  $('#onepic').on('click',function(){
    $("html,body").animate({
      scrollTop:position1
    });
    return false;
  });

  $('#twopic').on('click',function(){
    $("html,body").animate({
      scrollTop:position2
    });
    return false;
  });

  $('.top').on('click',function(){
    $("html,body").animate({
      scrollTop:toppage
    });
    return false;
  });


});
</script>

<!-- セッションデータを初期化 -->
<?php
// $_SESSION = array();
?>
  </body>
</html>
