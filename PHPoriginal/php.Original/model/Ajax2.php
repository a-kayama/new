<?php
$host = 'localhost';
$name = 'original_php';
$user = 'a.kayama';
$pass = 'jmag5624';

$user_id = $_POST["user_id"];
$post_id = $_POST["post_id"];

//データベース接続
try{
$db = new PDO("mysql:host={$host};dbname={$name};charset=utf8mb4", $user,$pass);


}catch (PDOException $e) {
  echo "エラー!: " . $e->getMessage() . "<br/gt;";
  exit;
}
$sql = 'DELETE FROM favorites WHERE user_id=? AND idea_id=?';
$stmt = $db->prepare($sql);
$result = $stmt->execute(array($user_id, $post_id));
